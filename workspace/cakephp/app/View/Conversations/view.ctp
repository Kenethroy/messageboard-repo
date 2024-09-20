<div class="conversation-header">
   
    <div> 
     <button onclick="window.history.back();" class="btn btn-secondary" style="margin-bottom: 20px; margin-top: 20px; float: right">Back</button>
     <h1 style="text-align: center;">Message Details</h1>
    </div>
    <?php echo $this->Form->create('Conversation', array('url' => array('action' => 'reply'), 'type' => 'post', 'id' => 'replyForm')); ?>
    <?php echo $this->Form->hidden('conversation_id', array('value' => $conversation['Conversation']['id'])); ?>
    <?php echo $this->Form->hidden('user_id', array('value' => $userId)); ?>
    <?php echo $this->Form->input('message', array('type' => 'textarea', 'rows' => '3', 'label' => false, 'placeholder' => 'Type your message here...')); ?>
    <?php echo $this->Form->button('Reply', array('type' => 'button', 'class' => 'btn btn-primary', 'id' => 'sendMessageButton')); ?>
    <?php echo $this->Form->end(); ?>
    <div id="messages" class="messages">
    </div>
    <div class="showMoreButtonContainer">
        <button id="showMoreButton" class="btn btn-primary">Show More</button>
    </div>
</div>
<style>
    .conversation-header  {
        width: 100%;
        max-width: 800px;
        background-color: #fff;
        border-radius: 5px;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        margin: 0 auto;
    }
    .showMoreButtonContainer {
        display: flex;
        justify-content: center;
        margin-top: 10px;
        margin-bottom: 10px;
    }

    .message {
        margin-bottom: 10px;
        padding: 10px;
        border-radius: 5px;
        max-width: 60%;
        position: relative;
    }
    .message.left {
        background-color: #f1f1f1;
        margin-left: 0;
        margin-right: auto;
        text-align: left;
    }
    .message.right {
        background-color: #d1ffd1;
        margin-left: auto;
        margin-right: 0;
        text-align: right;
    }
    .message-content {
        font-size: 14px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-height: 1.2em; /* Limit the height to one line */
        cursor: pointer;
    }
    .message-content.expanded {
        white-space: normal; /* Allow text to wrap */
        max-height: none;
    }
    .message-date {
        font-size: 12px;
        color: #777;
        float: right;
        margin-top: -15px;
    }
    textarea {
        width: 100%;
    }
    .delete-btn.right{
        position: absolute;
        top: 10px;
        right: 10px;
        background: transparent;
        border: none;
        color: red;
        cursor: pointer;
    }
    .delete-btn.left{
        position: absolute;
        top: -1px;
        left: 0px;
        background: transparent;
        border: none;
        color: red;
        cursor: pointer;
    }

    .message img {
        max-width: 100%;
    }
    .details.left {
        text-align: justify;
        margin-left: 70px;
    }
    .details.right {
        text-align: justify;
        margin-right: 70px;
    }

    .profile-picture.right {
        float: right;
    }
    .profile-picture.left {
        float: left;
    }
    .sendMessageButton {
        float: right;
    }

</style>
<script>
$(document).ready(function() {
    const conversationId = <?php echo json_encode($conversation['Conversation']['id']); ?>;
    const userId = <?php echo json_encode($userId); ?>;
    let limit = 10;
    let offset = parseInt(localStorage.getItem('offset')) || 0;
    
    // Log the initial offset value from localStorage
    console.log('Initial offset from localStorage:', offset);
    
    // Function to load messages
    function loadMessages(loadMore = false) {
        console.log('Loading messages with offset:', offset); // Log the current offset value
        
        $.ajax({
            url: '<?php echo $this->Html->url(array('action' => 'fetchMessages')); ?>',
            type: 'GET',
            dataType: 'json',
            data: {
                conversation_id: conversationId,
                offset: offset,
                limit: limit
            },
            success: function(data) {
                const messagesContainer = $('#messages');
                if (loadMore) {
                    appendMessages(data.messages);
                } else {
                    messagesContainer.empty();
                    appendMessages(data.messages);
                   
                }
                if (data.hasMore) {
                        $('#showMoreButton').show();
                        
                    } else {
                        $('#showMoreButton').hide(); 
                    }
                if (data.messages.length < limit) {
                    $('#showMoreButton').hide();
                }
            },
            error: function() {
                $('#messages').html('<div class="alert alert-danger">Failed to load messages.</div>');
            }
        });
    }

    // Append messages to the container
    function appendMessages(messages) {
        const messagesContainer = $('#messages');
        messages.forEach(function(message) {
            const messageAlignment = message.user_id === userId ? 'right' : 'left';
            const deleteAlignment = message.user_id === userId ? 'left' : 'right';
            const profileAlignment = message.user_id === userId ? 'right' : 'left';
            const profilePictureUrl = message.profile_picture 
                ? '<?php echo Router::url("/uploads/"); ?>' + message.profile_picture 
                : '<?php echo Router::url("/uploads/default.jpeg"); ?>'; // Absolute URL for the placeholder

            // URL for viewing profile
            const profileUrl = '<?php echo $this->Html->url(array("controller" => "users", "action" => "viewProfile",)); ?>/' + message.user_id;

            const messageHtml = `
                <div class="message ${messageAlignment}" id="message-${message.id}">
                    <div class="profile-picture ${profileAlignment}">
                        <a href="${profileUrl}">
                            <img src="${profilePictureUrl}" alt="User Profile Picture" width="50px" height="50px" />
                        </a>
                    </div>
                    <button class="delete-btn ${deleteAlignment}" data-id="${message.id}">&times;</button>
                    <div class="details ${messageAlignment}"> 
                        <div class="message-content">${message.message}</div>
                        <hr>
                        <br>
                        <div class="message-date">${message.created}</div>
                    </div>
                </div>
            `;
            messagesContainer.append(messageHtml);
        });
    }

    // Handle "Show More" button click
    $('#showMoreButton').click(function() {
        offset += limit;
        localStorage.setItem('offset', offset); // Store updated offset in localStorage
        console.log('Updated offset in localStorage:', offset); // Log the updated offset
        loadMessages(true);
    });

    // Handle form submission via AJAX for sending a message
    $('#sendMessageButton').click(function() {
        const message = $('#replyForm textarea').val();
        if (message.trim() !== '') {
            $.ajax({
                url: '<?php echo $this->Html->url(array('action' => 'reply')); ?>',
                type: 'POST',
                dataType: 'json',
                data: { message: message, conversation_id: conversationId, user_id: userId },
                success: function(data) {
                    if (data.success) {
                        $('#replyForm textarea').val('');
                        offset = 0; // Reset offset after sending a reply
                        localStorage.setItem('offset', offset); // Reset offset in localStorage
                        console.log('Reset offset in localStorage:', offset); // Log the reset offset
                        loadMessages(); // Reload messages from the beginning
                    } else {
                        alert('Failed to send the message.');
                    }
                },
                error: function() {
                    alert('Failed to send the message.');
                }
            });
        }
    });

   // Handle form submission via AJAX for deleting a message
$(document).on('click', '.delete-btn', function() {
    const button = $(this);
    const messageId = button.data('id');

    $.ajax({
        url: '<?php echo $this->Html->url(array('action' => 'deleteMessage')); ?>',
        type: 'POST',
        dataType: 'json',
        data: { id: messageId },
        success: function(data) {
            if (data.success) {
                $(`#message-${messageId}`).fadeOut(300, function() {
                    $(this).remove();
                    if (data.conversationDeleted) {
                        // Redirect to the conversation index if the conversation was deleted
                        window.location.href = '<?php echo $this->Html->url(array('action' => 'index')); ?>';
                    } else {
                        // Reload the remaining messages if the conversation still exists
                        loadMessages();
                    }
                });
            } else {
                alert('Failed to delete the message.');
                loadMessages();
            }
        },
        error: function() {
            alert('Error: Failed to delete the message.');
        }
    });
});


    // Initial load of messages
    loadMessages();

    // Reset offset in localStorage when reloading the page
    $(window).on('beforeunload', function() {
        localStorage.removeItem('offset');
    });
});
</script>
