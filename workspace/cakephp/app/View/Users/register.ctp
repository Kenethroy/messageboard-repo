<h1>Register</h1>

<?php
// Display flash messages (if any)
if ($this->Session->check('Message.flash')) {
    echo '<div class="alert alert-danger">' . $this->Session->flash('flash') . '</div>';
}

echo $this->Form->create('User');
echo $this->Form->input('name', array('type' => 'text', 'label' => 'Name', 'required' => true));
echo $this->Form->input('email', array('type' => 'text', 'label' => 'Email', 'required' => true));
echo $this->Form->input('password', array('type' => 'password', 'label' => 'Password', 'required' => true));
echo $this->Form->input('password_confirm', array('type' => 'password', 'label' => 'Password Confirmation', 'required' => true));
echo $this->Form->end('Register');
?>
