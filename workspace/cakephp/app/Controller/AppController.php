<?php
class AppController extends Controller {
    public $components = array(
        'Session',
        'Auth' => array(
            'authenticate' => array(
                'Form' => array(
                    'fields' => array('username' => 'email') // Use email for login
                )
            ),
            'loginRedirect' => array('controller' => 'conversations', 'action' => 'index'),
            'logoutRedirect' => array('controller' => 'users', 'action' => 'login'),
            'authError' => 'Please login to access this page',
            'authorize' => array('Controller')
        )
    );

    public function isAuthorized($user) {
        return true;
    }

    public function beforeFilter() {
        $this->Auth->allow('register', 'login', 'index');
    }
}
