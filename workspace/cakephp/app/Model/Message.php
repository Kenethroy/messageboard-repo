<?php

App::uses('AppModel', 'Model');

class Message extends AppModel {
    public $name = 'Message';
    
    // Define associations
    public $belongsTo = array(
        'Conversation' => array(
            'className' => 'Conversation',
            'foreignKey' => 'conversation_id'
        )
    );

    public $validate = array(
        'conversation_id' => array(
            'rule' => 'numeric',
            'message' => 'Please provide a valid conversation ID.'
        ),
        'user_id' => array(
            'rule' => 'numeric',
            'message' => 'Please provide a valid user ID.'
        ),
        'message' => array(
            'rule' => array('minLength', 1),
            'message' => 'Message cannot be empty.'
        )
    );
}

