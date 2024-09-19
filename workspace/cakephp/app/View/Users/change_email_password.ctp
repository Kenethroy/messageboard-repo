<h1>Change Email/Password</h1>

<?php
// Display any flash messages if present
if ($this->Session->check('Message.flash')) {
    echo $this->Session->flash();
}
?>

<?php
// Create the form and allow file uploads
echo $this->Form->create('User', array(
    'url' => array('controller' => 'users', 'action' => 'changeEmailPassword'),
    'type' => 'post',
    'id' => 'changeEmailPasswordForm'
));
echo $this->Form->input('email', array('label' => 'Email', 'type' => 'email', 'required' => true));
echo $this->Form->input('password', array('label' => 'Password', 'type' => 'password', 'required' => true));
echo $this->Form->input('password_confirm', array('label' => 'Confirm Password', 'type' => 'password', 'required' => true));
echo $this->Form->button('Update', array('type' => 'submit', 'id' => 'submitBtn'));
echo $this->Form->end();
?>

<div id="formMessage" style="color: red; display: none;"></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#changeEmailPasswordForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        $.ajax({
            url: "<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'changeEmailPassword')); ?>",
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#formMessage').text(response.message).css('color', 'green').show();

                    setTimeout(function() {
                        window.location.href = "<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'viewProfile')); ?>"; 
                    }, 2000);
                } else {
                    $('#formMessage').text(response.message).css('color', 'red').show();
                }
            },
            error: function() {
                $('#formMessage').text('An error occurred while updating your profile.').css('color', 'red').show();
            }
        });
    });
});
</script>
