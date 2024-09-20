<?php

class ConversationsController extends AppController {
    public $uses = array('Conversation', 'Message', 'User'); // Ensure you use User model if needed

    // List conversations for the logged-in user
    public function index() {
        $userId = $this->Auth->user('id');
       
        $limit = isset($this->request->query['limit']) ? (int)$this->request->query['limit'] : 3;
        $offset = isset($this->request->query['offset']) ? (int)$this->request->query['offset'] : 0;
    
        // Subquery to get the latest message for each conversation
        $latestMessagesSubquery = $this->Message->query("
            SELECT conversation_id, MAX(created) as latest_created
            FROM messages
            GROUP BY conversation_id
        ");
        
        $latestMessagesConditions = [];
        foreach ($latestMessagesSubquery as $row) {
            $latestMessagesConditions[] = $row['messages']['conversation_id'];
        }
    
        // Fetching conversations without search term
        $conversations = $this->Conversation->find('all', array(
            'fields' => array(
                'Conversation.id',
                'Conversation.sender_id',
                'Conversation.receiver_id',
                'Message.user_id',
                'Message.message',
                'Message.created',
                'SenderUser.name',
                'ReceiverUser.name',
                'SenderUser.profile_picture',
                'ReceiverUser.profile_picture'
            ),
            'joins' => array(
                array(
                    'table' => 'messages',
                    'alias' => 'Message',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Message.conversation_id = Conversation.id',
                        'Message.created = (SELECT MAX(created) FROM messages WHERE conversation_id = Conversation.id)'
                    )
                ),
                array(
                    'table' => 'users',
                    'alias' => 'SenderUser',
                    'type' => 'INNER',
                    'conditions' => array(
                        'SenderUser.id = Conversation.sender_id'
                    )
                ),
                array(
                    'table' => 'users',
                    'alias' => 'ReceiverUser',
                    'type' => 'INNER',
                    'conditions' => array(
                        'ReceiverUser.id = Conversation.receiver_id'
                    )
                )
            ),
            'conditions' => array(
                'Conversation.id' => $latestMessagesConditions,
                'OR' => array(
                    'Conversation.sender_id' => $userId,
                    'Conversation.receiver_id' => $userId
                )
            ),
            'order' => array('Message.created' => 'DESC'),
            'limit' => $limit,
            'offset' => $offset
        ));
        
        $totalConversations = $this->Conversation->find('count', array(
            'conditions' => array(
                'Conversation.id' => $latestMessagesConditions,
                'OR' => array(
                    'Conversation.sender_id' => $userId,
                    'Conversation.receiver_id' => $userId
                )
            )
        ));
        
        $hasMore = ($totalConversations > ($offset + $limit));
        
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $this->response->type('json');
            echo json_encode(array(
                'conversations' => $conversations,
                'hasMore' => $hasMore
            ));
            return;
        }
        
        $this->set(compact('conversations', 'userId', 'totalConversations', 'limit'));
    }

    // View specific conversation
    public function view($conversationId = null) {
        if (!$conversationId) {
            throw new NotFoundException(__('Invalid conversation'));
        }
    
        // Fetch the logged-in user ID
        $userId = $this->Auth->user('id');
        if (!$userId) {
            throw new UnauthorizedException(__('User not logged in'));
        }
    
        // Fetch conversation details
        $conversation = $this->Conversation->findById($conversationId);
        if (!$conversation) {
            throw new NotFoundException(__('Invalid conversation'));
        }
    
        // Fetch messages for the selected conversation
        $messages = $this->Conversation->Message->find('all', array(
            'conditions' => array(
                'Message.conversation_id' => $conversationId
            ),
            'order' => array('Message.created' => 'DESC')
        ));
    
        // Pass variables to the view
        $this->set(compact('conversation', 'messages', 'userId'));
    }
    
    public function reply() {
        if ($this->request->is('post')) {
            $this->Message->create();
    
            if ($this->Message->save($this->request->data)) {
                $response = array('success' => true);
            } else {
                $response = array('success' => false, 'errors' => $this->Message->validationErrors);
            }
    
            // Ensure proper content type and JSON encoding
            $this->autoRender = false;
            $this->response->type('application/json');
            $this->response->body(json_encode($response));
        } else {
            throw new MethodNotAllowedException('Invalid request method');
        }
    }

    public function fetchMessages() {
        $this->autoRender = false;
        $this->layout = 'ajax';
    
        if ($this->request->is('ajax')) {
            $conversationId = $this->request->query('conversation_id');
            $offset = isset($this->request->query['offset']) ? (int)$this->request->query['offset'] : 0;
            $limit = isset($this->request->query['limit']) ? (int)$this->request->query['limit'] : 10;
    
            // Adjust the recursive level or use containable behavior to join the User table
            $this->Message->bindModel(array(
                'belongsTo' => array(
                    'User' => array(
                        'className' => 'User',
                        'foreignKey' => 'user_id'
                    )
                )
            ));
    
            $this->Message->recursive = 1; // Set recursive to 1 to fetch related User data
            $messages = $this->Message->find('all', array(
                'conditions' => array('Message.conversation_id' => $conversationId),
                'order' => array('Message.created' => 'desc'),
                'limit' => $limit,
                'offset' => $offset
            ));
    
            $response = array();
            foreach ($messages as $message) {
                $response[] = array(
                    'id' => $message['Message']['id'],
                    'user_id' => $message['Message']['user_id'],
                    'message' => $message['Message']['message'],
                    'created' => $message['Message']['created'],
                    'profile_picture' => $message['User']['profile_picture']
                );
            }
    
            // Check if there are more messages to load
            $totalMessages = $this->Message->find('count', array(
                'conditions' => array('Message.conversation_id' => $conversationId)
            ));
            $hasMore = ($offset + $limit) < $totalMessages;
    
            echo json_encode(array(
                'messages' => $response,
                'hasMore' => $hasMore
            ));
        } else {
            throw new MethodNotAllowedException('Invalid request method');
        }
    }
    

    public function deleteMessage() {
        $this->autoRender = false;
        $messageId = $this->request->data['id'];
        
        if ($this->Message->delete($messageId)) {
            $response = array('success' => true);
        } else {
            $response = array('success' => false, 'errors' => $this->Message->validationErrors);
        }
        
        // Ensure proper content type and JSON encoding
        $this->autoRender = false;
        $this->response->type('application/json');
        $this->response->body(json_encode($response));

    }
    
    public function start() {
        $this->autoRender = false; // Disable view rendering
        $this->response->type('json');
    
        if ($this->request->is('post')) {
            $this->loadModel('Conversation');
            $this->loadModel('Message');
    
            $senderId = $this->Auth->user('id'); // Get logged-in user ID
            $receiverId = $this->request->data['receiver_id'];
            $messageContent = $this->request->data['message'];
    
            // Check if a conversation already exists between the sender and receiver
            $existingConversation = $this->Conversation->find('first', array(
                'conditions' => array(
                    'OR' => array(
                        array('Conversation.sender_id' => $senderId, 'Conversation.receiver_id' => $receiverId),
                        array('Conversation.sender_id' => $receiverId, 'Conversation.receiver_id' => $senderId)
                    )
                )
            ));
    
            if ($existingConversation) {
                // If conversation already exists, return an error message
                $response = array('success' => false, 'message' => 'You already have an existing conversation with this user.');
            } else {
                // Create new conversation
                $conversation = $this->Conversation->create();
                $conversation['Conversation'] = array(
                    'sender_id' => $senderId,
                    'receiver_id' => $receiverId
                );
    
                if ($this->Conversation->save($conversation)) {
                    $conversationId = $this->Conversation->id;
    
                    // Save message
                    $message = $this->Message->create();
                    $message['Message'] = array(
                        'conversation_id' => $conversationId,
                        'user_id' => $senderId,
                        'message' => $messageContent
                    );
    
                    if ($this->Message->save($message)) {
                        $response = array('success' => true);
                    } else {
                        $response = array('success' => false, 'message' => 'Unable to send the message.');
                    }
                } else {
                    $response = array('success' => false, 'message' => 'Unable to start the conversation.');
                }
            }
    
            $this->response->body(json_encode($response));
        } else {
            $this->response->body(json_encode(array('success' => false, 'message' => 'Invalid request.')));
        }
    }
    
    public function newConversation() { 
    
    }
    public function delete() {
        $this->request->allowMethod('post'); // Ensure only POST requests are accepted

         if ($this->request->is('post')) {
             $conversationId = $this->request->data['id'];
             $this->Conversation->id = $conversationId;

            if (!$this->Conversation->exists()) {
            throw new NotFoundException(__('Invalid conversation'));
            }

            if ($this->Conversation->delete()) {
            echo json_encode(['success' => true]);
            } else {
            echo json_encode(['success' => false, 'message' => 'Unable to delete conversation']);
            }

            $this->autoRender = false; // Prevent CakePHP from rendering a view
            } else {
            throw new MethodNotAllowedException();
        }
    }

    public function search() {
        $userId = $this->Auth->user('id');
        $searchTerm = isset($this->request->query['search']) ? trim($this->request->query['search']) : '';
    
        if (!$searchTerm) {
          $this->autoRender = false;
          $this->response->type('json');
            echo json_encode(array(
            'conversations' => [],
            'hasMore' => false
            ));
             return;
        }

    // Subquery to get the latest message for each conversation
        $latestMessagesSubquery = $this->Message->query("
         SELECT conversation_id, MAX(created) as latest_created
         FROM messages
         GROUP BY conversation_id
        ");
    
         $latestMessagesConditions = [];
         foreach ($latestMessagesSubquery as $row) {
         $latestMessagesConditions[] = $row['messages']['conversation_id'];
        }

    // Fetch conversations that match the search term
        $conversations = $this->Conversation->find('all', array(
        'fields' => array(
            'Conversation.id',
            'Conversation.sender_id',
            'Conversation.receiver_id',
            'Message.user_id',
            'Message.message',
            'Message.created',
            'SenderUser.name',
            'ReceiverUser.name',
            'SenderUser.profile_picture',
            'ReceiverUser.profile_picture'
        ),
        'joins' => array(
            array(
                'table' => 'messages',
                'alias' => 'Message',
                'type' => 'INNER',
                'conditions' => array(
                    'Message.conversation_id = Conversation.id',
                    'Message.created = (SELECT MAX(created) FROM messages WHERE conversation_id = Conversation.id)'
                )
            ),
            array(
                'table' => 'users',
                'alias' => 'SenderUser',
                'type' => 'INNER',
                'conditions' => array(
                    'SenderUser.id = Conversation.sender_id'
                )
            ),
            array(
                'table' => 'users',
                'alias' => 'ReceiverUser',
                'type' => 'INNER',
                'conditions' => array(
                    'ReceiverUser.id = Conversation.receiver_id'
                )
            )
        ),
        'conditions' => array(
            'Conversation.id' => $latestMessagesConditions,
            'OR' => array(
                'Conversation.sender_id' => $userId,
                'Conversation.receiver_id' => $userId
            ),
            'AND' => array(
                'OR' => array(
                    'SenderUser.name LIKE' => '%' . $searchTerm . '%',
                    'ReceiverUser.name LIKE' => '%' . $searchTerm . '%',
                    'Message.message LIKE' => '%' . $searchTerm . '%'
                )
            )
        ),
        'order' => array('Message.created' => 'DESC'),
        ));
    
        $this->autoRender = false;
        $this->response->type('json');
        echo json_encode(array(
        'conversations' => $conversations
        ));
        return;

    }

}
