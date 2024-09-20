
<?php
if ($this->Session->check('Message.flash')) {
    echo '<div class="alert alert-danger">' . $this->Session->flash('flash') . '</div>';
}
echo '<h1>Register</h1>';
echo $this->Form->create('User', array('id' => 'registrationForm', 'class' => 'form-register'));

echo '<div class="form-group">';
echo $this->Form->input('name', array('type' => 'text', 'label' => 'Name', 'id' => 'name', 'class' => 'form-control', 'placeholder' => 'Enter your name'));
echo '</div>';

echo '<div class="form-group">';
echo $this->Form->input('email', array('type' => 'text', 'label' => 'Email', 'id' => 'email', 'class' => 'form-control', 'placeholder' => 'Enter your email'));
echo '</div>';

echo '<div class="form-group">';
echo $this->Form->input('password', array('type' => 'password', 'label' => 'Password', 'id' => 'password', 'class' => 'form-control', 'placeholder' => 'Enter your password'));
echo '</div>';

echo '<div class="form-group">';
echo $this->Form->input('password_confirm', array('type' => 'password', 'label' => 'Confirm Password', 'id' => 'password_confirm', 'class' => 'form-control', 'placeholder' => 'Confirm your password'));
echo '</div>';

echo $this->Form->end(array('label' => 'Register', 'class' => 'btn btn-primary btn-block'));

// Add a link to the login page
echo '<p class="text-center">Already have an account? <a href="' . $this->Html->url(array('controller' => 'users', 'action' => 'login')) . '">Log in here</a>.</p>';
?>

<!-- SweetAlert2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

<!-- jQuery (Ensure jQuery is included before this script) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

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
                            // Show SweetAlert success confirmation
                            Swal.fire({
                                title: 'Thank you for registering!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'Back to Home',
                                allowOutsideClick: false, // Prevent closing by clicking outside the modal
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Redirect to home page
                                    window.location.href = "<?php echo $this->Html->url(array('controller' => 'conversations', 'action' => 'index')); ?>";
                                }
                            });

                            // Fallback: Redirect to login after 5 seconds if the user closes the SweetAlert modal accidentally
                            setTimeout(function() {
                                window.location.href = "<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'login')); ?>";
                            }, 20000); // 20 seconds
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


<style>
    .form-register {
        max-width: 500px;
        margin: auto;
        padding: 1em;
        background: #f7f7f7;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    .form-group {
        margin-bottom: 1.5em;
    }
    .form-control {
        width: 100%;
        padding: 0.75em;
        font-size: 1em;
        border-radius: 5px;
        border: 1px solid #ddd;
    }
    .btn-block {
        width: 100%;
        padding: 0.75em;
        font-size: 1em;
    }
    .error {
        margin-top: 0.5em;
        display: block;
    }
    .text-center {
        text-align: center;
        margin-top: 1.5em;
    }
</style>
