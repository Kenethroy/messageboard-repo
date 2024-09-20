<h1>Start a New Conversation</h1>

<div class="conversation-container">
    <?php echo $this->Form->create('Conversation', array('url' => array('action' => 'start'), 'type' => 'post', 'id' => 'newConversationForm')); ?>
    <div class="error-message" id="error-message"></div>
    <?php echo $this->Form->input('receiver_id', array('type' => 'hidden', 'id' => 'receiverId')); ?>
    <div class="form-group">
        <label for="userSearch" class="form-label"></label>
        <?php echo $this->Form->input('search name', array('type' => 'text', 'id' => 'userSearch', 'class' => 'form-control search-input')); ?>
    </div>

    <div class="form-group">
        <label for="receiverName" class="form-label">Send To:</label>
        <?php echo $this->Form->input('', array('type' => 'text', 'id' => 'receiverName', 'class' => 'form-control', 'readonly' => true)); ?>

    </div>
    <div class="form-group">
        <label for="message" class="form-label">Message</label>
        <?php echo $this->Form->textarea('message', array('rows' => '3', 'id' => 'message', 'class' => 'form-control textarea-input')); ?>
    </div>

    <!-- Submit Button -->
    <div class="form-group">
        <?php echo $this->Form->button('Send Message', array('type' => 'submit', 'class' => 'btn btn-primary submit-btn', 'id' => 'sendMessageButton')); ?>
    </div>

    <?php echo $this->Form->end(); ?>
</div>

<!-- Back to List Button -->
<div class="back-link">
    <?php echo $this->Html->link('Back to List', array('action' => 'index'), array('class' => 'btn btn-secondary')); ?>
</div>
<style>

    /* Conversation Container */
.conversation-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    font-family: Arial, sans-serif;
}

/* Form Labels */
.form-label {
    font-size: 16px;
    font-weight: bold;
    margin-bottom: 5px;
}

/* Input Fields */
.form-control {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 14px;
}

/* Search Input */
.search-input {
    padding-left: 40px;
    position: relative;
}

/* Textarea Input */
.textarea-input {
    min-height: 100px;
    resize: none;
}

/* Submit Button */
.submit-btn {
    width: 100%;
    padding: 10px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
}

.submit-btn:hover {
    background-color: #45a049;
}

/* Back Button */
.back-link {
    text-align: center;
    margin-top: 20px;
}

/* Back to List Button */
.btn-secondary {
    background-color: #555;
    color: white;
    padding: 8px 15px;
    text-decoration: none;
    border-radius: 5px;
}

.btn-secondary:hover {
    background-color: #333;
    text-decoration: none;
}

/* Error Message */
.error-message {
    color: red;
    font-size: 14px;
    margin-bottom: 10px;
    text-align: center;
}

/* Select2 CSS */
</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

<!-- jQuery and Select2 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

<script>
   $(document).ready(function() {
    // Initialize Select2 with AJAX search
    $('#userSearch').select2({
        placeholder: 'Search for a user',
        ajax: {
            url: '<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'search')); ?>',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    q: params.term // search term
                };
            },
            processResults: function(data) {
                return {
                    results: $.map(data.users, function(user) {
                        return {
                            text: user.name,
                            id: user.id,
                            profile_picture: user.profile_picture // Add profile_picture to the results
                        };
                    })
                };
            },
            cache: true
        },
        templateResult: formatUser, // For custom display of user results
        templateSelection: formatUserSelection // For selected display
    });

    // Custom function to format user in the search results
    function formatUser(user) {
        if (!user.id) { 
            return user.text; 
        }

        const profilePictureUrl = user.profile_picture 
            ? '<?php echo Router::url("/uploads/"); ?>' + user.profile_picture 
            : '<?php echo Router::url("/uploads/default.jpeg"); ?>';

        var $user = $(
            '<div class="user-result">' +
                '<img src="' + profilePictureUrl + '" class="user-avatar" style="width: 30px; height: 30px; border-radius: 50%; margin-right: 5px;" /> ' +
                '<span>' + user.text + '</span>' +
            '</div>'
        );
        return $user;
    }

    // Format selected user in the input box
    function formatUserSelection(user) {
        return user.text || user.id;
    }

    // When a user is selected in Select2
    $('#userSearch').on('select2:select', function(e) {
        var data = e.params.data;
        $('#receiverId').val(data.id); // Set the hidden receiver_id field
        $('#receiverName').val(data.text); // Set the receiver name in the "Send To" field
    });

    // Handle form submission via AJAX
    $('#newConversationForm').submit(function(event) {
        event.preventDefault(); // Prevent default form submission
        const receiverId = $('#receiverId').val();
        const message = $('#message').val();

        // Check if both fields are filled
        if (!receiverId || !message) {
            alert('Please select a user and enter a message.');
            return;
        }

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: {
                receiver_id: receiverId,
                message: message
            },
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    // Redirect to the conversation list on success
                    window.location.href = '<?php echo $this->Html->url(array('action' => 'index')); ?>';
                } else {
                    $('#error-message').stop(true, true).text(data.message).show().fadeOut(2000);
                   
                }
            },
            error: function(xhr, status, error) {
                alert('Failed to send the message. Please try again.');
            }
        });
    });
});

</script>
