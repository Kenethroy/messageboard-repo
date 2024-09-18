<div class="container">
    <div>
        <h2>Messages List</h2>
        <button class="messagesButton"><?php echo $this->Html->link('New Messages', array('controller' => 'conversations', 'action' => 'newConversation')); ?></button>
     
    </div>

    <div class="conversation-list">
        <?php foreach ($conversations as $conversation): ?>
            <?php
                $conversationId = $conversation['Conversation']['id'];
                $latestMessage = $conversation['Message']['message'];
                $messageDate = $conversation['Message']['created'];
                $latestMessageSenderId = $conversation['Message']['user_id'];

                $senderId = $conversation['Conversation']['sender_id'];
                $receiverId = $conversation['Conversation']['receiver_id'];
                $loggedInUserId = $this->Session->read('Auth.User.id');

                $senderProfilePic = $conversation['SenderUser']['profile_picture'];
                $receiverProfilePic = $conversation['ReceiverUser']['profile_picture'];
                $senderName = h($conversation['SenderUser']['name']);
                $receiverName = h($conversation['ReceiverUser']['name']);

                if ($latestMessageSenderId == $loggedInUserId) {
                    $messageDisplay = 'You: ' . h($latestMessage);
                    $profilePicUrl = ($latestMessageSenderId == $senderId ? $senderProfilePic : $receiverProfilePic); 
                    $profilePicAlt = 'Your profile picture';
                } else {
                    $messageDisplay = ($latestMessageSenderId == $senderId ? $senderName : $receiverName) . ': ' . h($latestMessage);
                    $profilePicUrl = ($latestMessageSenderId == $senderId ? $senderProfilePic : $receiverProfilePic);
                    $profilePicAlt = ($latestMessageSenderId == $senderId ? $senderName : $receiverName) . "'s profile picture";
                }

                $profilePictureUrl = '/uploads/' . h($profilePicUrl);
                $profilePicturePath = WWW_ROOT . 'uploads/' . h($profilePicUrl);
                $imageExists = file_exists($profilePicturePath);
            ?>

            <div class="conversation-item" data-id="<?php echo $conversationId; ?>">
                <!-- Wrap only the conversation content in an anchor -->
                <a href="<?php echo $this->Html->url(array('action' => 'view', $conversationId)); ?>" class="conversation-link">
                    <div class="profile-pic">
                        <?php
                            $placeholderUrl = 'profile-placeholder.png';
                            echo $this->Html->image($imageExists ? $profilePictureUrl : $placeholderUrl, array('width' => '100px', 'alt' => h($profilePicAlt)));
                        ?>
                    </div>
                    <div class="conversation-details">
                        <div class="latest-message">
                           <?php echo $messageDisplay; ?>
                        </div>
                        <div class="message-date">
                           <?php echo h($messageDate); ?>
                        </div>
                    </div>
                </a>
                <!-- Delete button outside the anchor but inside conversation item -->
                <div class="delete-button">
                    <button class="btn btn-danger delete-conversation" data-id="<?php echo $conversationId; ?>">Delete</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
    .container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f5f5f5;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }
   
    .messagesButton {
        padding: 5px 10px;
        background-color: #007bff;
        color: #fff;
        text-decoration: none;
        border-radius: 4px;
    }

    .conversation-list {
        margin: 0;
        padding: 0;
        list-style: none;
    }
    .conversation-item {
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-bottom: 10px;
        padding: 10px;
        background-color: #f9f9f9;
        display: flex;
        align-items: center;
    }
    a {
        color: inherit;
        text-decoration: none;
        display: flex;
        align-items: center;
        width: 100%;
        max-width: 800px;
    }
    .profile-pic {
        margin-right: 10px;
    }
    .conversation-details {
        flex-grow: 1;
    }
    .conversation-item div {
        margin-bottom: 5px;
    }
    .btn-primary {
        padding: 5px 10px;
        background-color: #007bff;
        color: #fff;
        text-decoration: none;
        border-radius: 4px;
    }
    .btn-primary:hover {
        background-color: #0056b3;
    }
    .delete-button {
        margin-left: 10px;
    }
    .btn-danger {
        padding: 5px 10px;
        background-color: #dc3545;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    .btn-danger:hover {
        background-color: #c82333;
    }
</style>

