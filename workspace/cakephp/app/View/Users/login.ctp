<h1>Login</h1>
<?php
// Display the flash message directly in the form
if ($this->Session->check('Message.flash')) {
    echo '<div class="alert alert-danger">' . $this->Session->flash('flash') . '</div>';
}
echo $this->Form->create('User');
echo $this->Form->input('email', array('type' => 'text', 'label' => 'Email'));
echo $this->Form->input('password', array('type' => 'password', 'label' => 'Password'));
echo $this->Form->end('Login');
?>
