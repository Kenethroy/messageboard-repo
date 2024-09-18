<?php
class Contact extends AppModel {
    public $validate = array(
        'name' => array(
            'rule' => 'notBlank',
            'message' => 'Please enter a name'
        ),
        'email' => array(
            'rule' => 'email',
            'message' => 'Please enter a valid email address'
        ),
        'phone' => array(
            'rule' => 'notBlank',
            'message' => 'Please enter a phone number'
        )
    );
}
?>
