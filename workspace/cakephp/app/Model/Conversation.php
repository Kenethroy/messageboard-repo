<?php
App::uses('AppModel', 'Model');

class Conversation extends AppModel {
    public $name = 'Conversation';
    
    // Define associations
    public $hasMany = array(
        'Message' => array(
            'className' => 'Message',
            'foreignKey' => 'conversation_id',
            'dependent' => true
        )
    );
}

