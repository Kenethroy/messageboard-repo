<?php
class ContactsController extends AppController {
    public $helpers = array('Html', 'Form', 'Session', 'Paginator');
    public $components = array('Session', 'Paginator', 'RequestHandler');

    // List all contacts (Read)
    public function index() {
        // Default pagination settings
        $this->Paginator->settings = array(
            'limit' => 5,  // Number of contacts per page
            'order' => array(
                'Contact.name' => 'asc'
            )
        );

        // Handle AJAX requests
        if ($this->request->is('ajax')) {
            $keyword = $this->request->query('search');
            $this->Paginator->settings['conditions'] = array(
                'OR' => array(
                    'Contact.name LIKE' => '%' . $keyword . '%',
                    'Contact.email LIKE' => '%' . $keyword . '%',
                    'Contact.phone LIKE' => '%' . $keyword . '%'
                )
            );
            $contacts = $this->Paginator->paginate('Contact');
            $this->set('contacts', $contacts);
            $this->render('/Elements/contact_list');
        } else {
            // Fetch all contacts with pagination
            $contacts = $this->Paginator->paginate('Contact');
            $this->set('contacts', $contacts);
        }
    }

    // View a specific contact (Read)
    public function view($id = null) {
        if (!$id) {
            throw new NotFoundException(__('Invalid contact'));
        }

        $contact = $this->Contact->findById($id);
        if (!$contact) {
            throw new NotFoundException(__('Contact not found'));
        }
        $this->set('contact', $contact);
    }

    // Add a new contact (Create)
    public function add() {
        if ($this->request->is('post')) {
            $this->Contact->create();
            if ($this->Contact->save($this->request->data)) {
                $this->Session->setFlash(__('Your contact has been saved.'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('Unable to add your contact.'));
        }
    }

    // Edit a contact (Update)
    public function edit($id = null) {
        if (!$id) {
            throw new NotFoundException(__('Invalid contact'));
        }

        $contact = $this->Contact->findById($id);
        if (!$contact) {
            throw new NotFoundException(__('Contact not found'));
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->Contact->id = $id;
            if ($this->Contact->save($this->request->data)) {
                $this->Session->setFlash(__('Your contact has been updated.'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('Unable to update your contact.'));
        }

        if (!$this->request->data) {
            $this->request->data = $contact;
        }
    }

    // Delete a contact (Delete)
    public function delete($id) {
        if ($this->request->is('post')) {
            if ($this->Contact->delete($id)) {
                $this->Session->setFlash(__('The contact has been deleted.'));
            } else {
                $this->Session->setFlash(__('The contact could not be deleted.'));
            }
            return $this->redirect(array('action' => 'index'));
        }
    }
    



    
}
?>
