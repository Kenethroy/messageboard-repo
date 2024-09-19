<h1>Register</h1>

<?php
if ($this->Session->check('Message.flash')) {
    echo '<div class="alert alert-danger">' . $this->Session->flash('flash') . '</div>';
}

echo $this->Form->create('User', array('id' => 'registrationForm'));
echo $this->Form->input('name', array('type' => 'text', 'label' => 'Name', 'id' => 'name'));
echo $this->Form->input('email', array('type' => 'text', 'label' => 'Email', 'id' => 'email'));
echo $this->Form->input('password', array('type' => 'password', 'label' => 'Password', 'id' => 'password'));
echo $this->Form->input('password_confirm', array('type' => 'password', 'label' => 'Password Confirmation', 'id' => 'password_confirm'));
echo $this->Form->end('Register');
?>

<script>
    $(document).ready(function() {
    $('#registrationForm').on('submit', function(e) {
        e.preventDefault(); // Prevent default form submission

        let isValid = true;
        let name = $('#name').val().trim();
        let email = $('#email').val().trim();
        let password = $('#password').val().trim();
        let passwordConfirm = $('#password_confirm').val().trim();

        // Clear previous error messages
        $('.error').remove();

        // Name validation: 5-20 characters
        if (name.length < 5 || name.length > 20) {
            $('#name').after('<span class="error" style="color:red;">Name must be between 5 and 20 characters.</span>');
            isValid = false;
        }

        // Email validation: Simple email regex
        let emailPattern = /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/;
        if (!emailPattern.test(email)) {
            $('#email').after('<span class="error" style="color:red;">Please enter a valid email address.</span>');
            isValid = false;
        }

        // Password validation: Must not be empty
        if (password.length === 0) {
            $('#password').after('<span class="error" style="color:red;">Please enter a password.</span>');
            isValid = false;
        }

        // Password confirmation validation: Must match the password
        if (password !== passwordConfirm) {
            $('#password_confirm').after('<span class="error" style="color:red;">Password confirmation does not match.</span>');
            isValid = false;
        }

        // If form is valid, submit via AJAX
        if (isValid) {
            $.ajax({
                type: 'POST',
                url: "<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'register')); ?>",
                data: $('#registrationForm').serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            title: 'Thank you for registering!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'Back to Home'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "<?php echo $this->Html->url(array('controller' => 'conversations', 'action' => 'index')); ?>";
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'Registration Failed',
                            text: response.message,
                            icon: 'error',
                            confirmButtonText: 'Close'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: 'Error',
                        text: 'Something went wrong. Please try again later.',
                        icon: 'error',
                        confirmButtonText: 'Close'
                    });
                }
            });
        }
    });
});

</script>
