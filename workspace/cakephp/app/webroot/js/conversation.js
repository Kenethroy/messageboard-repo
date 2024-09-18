// webroot/js/conversations.js
$(document).ready(function() {
    $('.delete-conversation').click(function() {
        var button = $(this);
        var conversationId = button.data('id');
        
        $.ajax({
            url: '/cakephp/conversations/delete', // Adjust if you use a different URL format
            type: 'POST',
            data: {
                id: conversationId
            },
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    button.closest('.conversation-item').fadeOut(); // Fade out the deleted conversation
                } else {
                    alert('Error: ' + data.message); // Show error message
                }
            },
            error: function(xhr, status, error) {
                alert('Failed to delete the conversation. Please try again.'); // Handle errors
            }
        });
    });

    // Add your additional functions
   /* $(document).on('click', '.delete-btn', function() {
        const messageId = $(this).data('id');
        $.ajax({
            url: '/cakephp/conversations/deleteMessage',  //'<?php echo $this->Html->url(array('action' => 'deleteMessage')); ?>',
            type: 'POST',
            data: { id: messageId },
            success: function(data) {
                if (data.success) {
                    $(`#message-${messageId}`).fadeOut(300, function() {
                        $(this).remove();
                    
                    });
                } else {
                    alert('Failed to delete the message.');
                }
            },
            error: function() {
                alert('Error occurred while deleting the message.');
            }
        });
    });
  */
    // Add your additional functions
    $(document).on('click', '.message-content', function() {
        $(this).toggleClass('expanded');
    });

    

  
});
