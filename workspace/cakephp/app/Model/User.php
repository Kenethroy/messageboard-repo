<?php
App::uses('AppModel', 'Model');

class User extends AppModel
{
    public $validate = array(
        'name' => array(
            'notBlank' => array(
                'rule' => 'notBlank',
                'message' => 'Please provide a name.'
            ),
            'between' => array(
                'rule' => array('lengthBetween', 5, 20),
                'message' => 'Name must be between 5 and 20 characters.'
            )
        ),
        'email' => array(
            'email' => array(
                'rule' => 'email',
                'message' => 'Please provide a valid email address.',
                'required' => true
            ),
            'unique' => array(
                'rule' => 'isUnique',
                'message' => 'This email is already in use.',
                'on' => 'create', // Only check on create action, not update
                'last' => true // This will make it run after the other validations
            )
        ),
        'profile_picture' => array(
            'rule' => array('extension', array('jpg', 'jpeg', 'gif', 'png')),
            'message' => 'Please upload a valid image (jpg, gif, png).',
            'allowEmpty' => true
        ),
        'password' => array(
            'rule' => 'notBlank',
            'message' => 'Please provide a password.',
            'required' => true
        ),
        'password_confirm' => array(
            'rule' => array('validatePasswordConfirm'),
            'message' => 'Password confirmation does not match.',
            'last' => true // Ensure this runs after the password rule
        )
    );

    public function validatePasswordConfirm($check)
    {
        $passwordConfirm = array_values($check)[0];
        return $this->data[$this->alias]['password'] === $passwordConfirm;
    }

    public function beforeSave($options = array())
    {
        // Hash the password before saving
        if (!empty($this->data[$this->alias]['password'])) {
            $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
        }
        return true;
    }
}
