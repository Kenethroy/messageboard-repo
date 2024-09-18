<?php
class LoginController extends AppController {
    
    // Login action
    public function login() {
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                // Redirect to the page the user was trying to access or to the home page
                return $this->redirect($this->Auth->redirectUrl());
            }
            // Display an error message if login fails
            $this->Session->setFlash(__('Invalid username or password, try again'));
        }
    }

    // Logout action
    public function logout() {
        return $this->redirect($this->Auth->logout());
    }

    // Register action
    public function register() {
        if ($this->request->is('post')) {
            // Create a new User entity
            $this->User->create();
            // Save the user data
            if ($this->User->save($this->request->data)) {
                // Success message and redirect to login
                $this->Session->setFlash(__('Registration successful'));
                return $this->redirect(array('action' => 'login'));
            }
            // Error message if registration fails
            $this->Session->setFlash(__('Registration failed. Please, try again.'));
        }
    }
}
