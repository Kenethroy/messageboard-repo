<?php
App::uses('AppController', 'Controller');

class UsersController extends AppController
{
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('register', 'login');
    }
     
    public function viewProfile($id = null) {
        // Check if the user exists
        if (!$id) {
            $id = $this->Auth->user('id'); // Default to logged-in user
        }
        
        $user = $this->User->findById($id);
        if (!$user) {
            $this->Session->setFlash('Invalid user.');
            $this->redirect(array('action' => 'profile'));
        }
        
        $this->set('user', $user);
    }
    
    public function register() {
        if ($this->request->is('post')) {
            $this->User->create();
            $email = $this->request->data['User']['email'];
            $checkEmail = $this->User->find('first', array(
                'conditions' => array('User.email' => $email)
            ));
    
            // If email already exists
            if (!empty($checkEmail)) {
                $this->Session->setFlash(__('The email address is already registered. Please use a different email.'));
                return;
            }
    
            // Save user data
            if ($this->User->save($this->request->data)) {
                // Fetch the newly registered user's data
                $newUser = $this->User->findById($this->User->getLastInsertId());
    
                // Auto-login the user after successful registration
                if ($this->Auth->login($newUser['User'])) {
                    // Update the last login time
                    $this->User->id = $newUser['User']['id'];
                    $this->User->saveField('last_login_time', date('Y-m-d H:i:s'));
    
                    $this->Session->setFlash(__('Registration successful! Welcome, ' . $newUser['User']['name']));
                    return $this->redirect($this->Auth->redirectUrl());
                } else {
                    $this->Session->setFlash(__('Failed to log you in. Please try logging in manually.'));
                    return $this->redirect(array('action' => 'login'));
                }
            }
    
            // If registration fails
            $this->Session->setFlash(__('Failed to register. Please check your details and try again.'));
        }
    }
    
    

    public function login() {
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                // Update the last login time
                $userId = $this->Auth->user('id');
                $this->User->id = $userId;
                $this->User->saveField('last_login_time', date('Y-m-d H:i:s'));
    
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Session->setFlash(__('Invalid email or password, try again.'));
        }
    }

    public function logout()
    {
        return $this->redirect($this->Auth->logout());
    }
    public function editProfile() {
        // Check if the form is submitted as POST or PUT
        if ($this->request->is('post') || $this->request->is('put')) {
            // Get the logged-in user's ID
            $this->User->id = $this->Auth->user('id');
            $loginName = $this->Auth->user('name'); // Assuming 'username' is stored in Auth session
        
            // Profile picture handling
            if (!empty($this->request->data['User']['profile_picture']['name'])) {
                $file = $this->request->data['User']['profile_picture'];
                $allowedExtensions = array('jpg', 'gif', 'png', 'jpeg');
                $extension = pathinfo($file['name'], PATHINFO_EXTENSION); // Get file extension
        
                // Validate file extension
                if (in_array(strtolower($extension), $allowedExtensions)) {
                    // Append current date to the filename
                    $date = date('Ymd_His'); // Current date and time
                    $filename = $loginName . '_' . $date . '.' . $extension; // Use login name and date in file name
                    $uploadPath = WWW_ROOT . 'uploads/'; // Define the upload path
                    $uploadFile = $uploadPath . $filename;
        
                    // Move file to the uploads folder
                    if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
                        $this->request->data['User']['profile_picture'] = $filename; // Save file name to database
                    } else {
                        $this->Session->setFlash('Error uploading file.');
                        return; // Stop execution if there's an error in file upload
                    }
                } else {
                    $this->Session->setFlash('Invalid file type. Only .jpg, .gif, and .png are allowed.');
                    return; // Stop execution if the file type is invalid
                }
            } else {
                // Retain the existing profile picture if no new file is uploaded
                unset($this->request->data['User']['profile_picture']);
            }
        
            // Validate the email to be unique and in a valid format
            $this->User->validate = array(
                'email' => array(
                    'validEmail' => array(
                        'rule' => 'email',
                        'message' => 'Please enter a valid email address.'
                    ),
                    'uniqueEmail' => array(
                        'rule' => 'isUnique',
                        'message' => 'This email address is already taken.'
                    )
                ),
                'name' => array(
                    'rule' => array('between', 5, 20),
                    'message' => 'Name must be between 5 to 20 characters long.'
                )
            );
        
            // Try saving the user data
            if ($this->User->save($this->request->data)) {
                // Update the Auth session with the new user data
                $updatedUser = $this->User->findById($this->Auth->user('id'));
                if ($updatedUser) {
                    $this->Auth->login($updatedUser['User']); // Refresh the Auth session with updated data
                }
        
                // Set success flash message and redirect to profile page
                $this->Session->setFlash('Profile updated successfully.');
                $this->redirect(array('action' => 'viewProfile')); // Redirect to profile page
            } else {
                // Set error flash message if unable to save
                $this->Session->setFlash('Unable to update profile. Please correct the errors below.');
            }
        } else {
            // If not POST or PUT, load the user's existing data
            $this->request->data = $this->User->findById($this->Auth->user('id'));
            if (!$this->request->data) {
                throw new NotFoundException(__('Invalid user'));
            }
        }
    }
    
    
    public function search() {
        $this->autoRender = false; // Disable view rendering for AJAX call
        $query = $this->request->query('q'); // Get the search term
        $currentUserId = $this->Auth->user('id'); // Get the logged-in user's ID
    
        $this->loadModel('User'); // Load the User model
    
        // Find users whose names match the search term, excluding the logged-in user
        $users = $this->User->find('all', [
            'conditions' => [
                'User.name LIKE' => '%' . $query . '%',
                'User.id !=' => $currentUserId // Exclude the logged-in user
            ],
            'fields' => ['User.id', 'User.name', 'User.profile_picture'], // Include profile_picture
            'limit' => 10
        ]);
    
        // Prepare the response data
        $results = [];
        foreach ($users as $user) {
            $results[] = [
                'id' => $user['User']['id'],
                'name' => $user['User']['name'],
                'profile_picture' => $user['User']['profile_picture'] // Add profile_picture
            ];
        }
    
        // Return the response in JSON format
        echo json_encode(['users' => $results]);
    }
    
    
    
    
    
}
