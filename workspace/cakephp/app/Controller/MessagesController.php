<?php
App::uses('AppController', 'Controller');

class MessagesController extends AppController
{
    public $uses = array('Message', 'User', 'Conversation');
    
    public function index()
    {
        // Check if user is logged in
        if (!$this->Auth->loggedIn()) {
            // Redirect to login if not logged in
            $this->redirect($this->Auth->loginAction);
        }

        // Get the logged-in user's ID
        $userId = $this->Auth->user('id');

        // Retrieve messages associated with the logged-in user
        $messages = $this->Message->find('all', array(
            'conditions' => array('Message.sender_id' => $userId), // Adjust column name if necessary
            'contain' => array('User'), // Optional: Include user data if needed
            'order' => array('Message.created' => 'desc') // Optional: Order messages by creation date
        ));

        // Set the messages data to be available in the view
        $this->set('messages', $messages);
    }

    public function view($id = null)
    {
        // Check if user is logged in
        if (!$this->Auth->loggedIn()) {
            // Redirect to login if not logged in
            $this->redirect($this->Auth->loginAction);
        }

        // Retrieve a specific message
        $message = $this->Message->find('first', array(
            'conditions' => array('Message.id' => $id),
            'contain' => array(
                'User', // Include user details
                'Conversation' => array('User') // Include conversation and related user details
            )
        ));

        if (!$message) {
            throw new NotFoundException(__('Invalid message'));
        }

        // Set the message data to be available in the view
        $this->set('message', $message);
    }
}
