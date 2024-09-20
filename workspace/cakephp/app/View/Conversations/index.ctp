<div class="conversation-header">
     <h2>Messages List</h2>
    <div class="search-container" style="float: right">
        <input type="text" id="searchField" placeholder="Search conversations..." />
        <button id="searchButton">Search</button>
    </div>
    <div>
        <button class="messagesButton" style="margin-top: 10px; margin-bottom: 10px; margin-left: 300px; text-decoration:none">
            <?php echo $this->Html->link('New Messages', array('controller' => 'conversations', 'action' => 'newConversation')); ?>
        </button>
    </div>
    <div class="conversation-list" id="conversationList">
        <!-- Conversations will be loaded here -->
    </div>
    <div id="showMoreButton" class="showMoreButton" style="display: none;">
        <button class="btn btn-primary">Show More</button>
    </div>
</div>

<script>
    $(document).ready(function() {
        let limit = 3;
        let offset = parseInt(localStorage.getItem('offset')) || 0;
        console.log('Initial offset from localStorage:', offset);

        function loadConversations(loadMore = false) {
            console.log('Loading conversations with offset:', offset);
            
            $.ajax({
                url: '<?php echo $this->Html->url(array('action' => 'index')); ?>',
                type: 'GET',
                dataType: 'json',
                data: {
                    offset: offset,
                    limit: limit
                },
                success: function(data) {
                    const conversationsContainer = $('#conversationList');
                    if (loadMore) {
                        appendConversations(data.conversations);
                    } else {
                        conversationsContainer.empty();
                        appendConversations(data.conversations);
                        if (data.hasMore) {
                            $('#showMoreButton').show();
                        } else {
                            $('#showMoreButton').hide();
                        }
                    }
                    if (data.conversations.length < limit) {
                        $('#showMoreButton').hide();
                    }
                },
                error: function() {
                    $('#conversationList').html('<div class="alert alert-danger">Failed to load messages.</div>');
                }
            });
        }

        // Append messages to the container
        function appendConversations(conversations) {
            const conversationsContainer = $('#conversationList');
            conversations.forEach(function(conversation) {
                console.log('Conversation ID:', conversation.Conversation.id);

                const profilePictureUrl = conversation.SenderUser.profile_picture
                    ? '<?php echo Router::url("/uploads/"); ?>' + conversation.SenderUser.profile_picture
                    : '<?php echo Router::url("/uploads/default.jpeg"); ?>';
                    
                var html = '<div class="conversation-item" id="conversation-' + conversation.Conversation.id + '">' +
                           '<a class="url" href="' + '<?php echo $this->Html->url(array('action' => 'view')); ?>/' + conversation.Conversation.id + '" class="conversation-link">' +
                           '<div class="profile-pic">' +
                           '<img src="' + profilePictureUrl + '" alt="Profile Picture" width="100">' +
                           '</div>' +
                           '<div class="conversation-details">' +
                           '<div class="latest-message">' + 
                           (conversation.Message.user_id == <?php echo $this->Session->read('Auth.User.id'); ?> ? 'You: ' : conversation.SenderUser.name + ': ') + 
                           conversation.Message.message + 
                           '</div>' +
                           '<div class="message-date">' + conversation.Message.created + '</div>' +
                           '</div>' +
                           '</a>' +
                           '<div class="delete-button">' +
                           '<button class="btn btn-danger delete" data-id="' + conversation.Conversation.id + '">Delete</button>' +
                           '</div>' +
                           '</div>';
                           
                conversationsContainer.append(html);
            });
        }

        // Handle "Show More" button click
        $('#showMoreButton').click(function() {
            offset += limit;
            localStorage.setItem('offset', offset);
            console.log('Updated offset in localStorage:', offset);
            loadConversations(true);
        });

        // Handle delete button click
        $(document).on('click', '.delete', function() {
            var button = $(this);
            var conversationId = button.data('id');
            
            $.ajax({
                url: '<?php echo $this->Html->url(array('controller' => 'conversations', 'action' => 'delete')); ?>',
                type: 'POST',
                data: {
                    id: conversationId
                },
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        button.closest('#conversation-' + conversationId).fadeOut(300, function() {
                            $(this).remove();
                            loadConversations();
                        });
                    } else {
                        alert('Error: ' + data.message);
                    }
                },
                error: function() {
                    alert('Failed to delete the conversation. Please try again.');
                }
            });
        });

        // Perform search
        function performSearch(searchTerm) {
            $.ajax({
                url: '<?php echo $this->Html->url(['controller' => 'conversations', 'action' => 'search']); ?>',
                type: 'GET',
                data: { search: searchTerm },
                dataType: 'json',
                success: function(data) {
                    if (data && data.conversations) {
                        $('#conversationList').empty();
                        if (data.conversations.length === 0) {
                            $('#conversationList').append('<div class="no-results">No conversations found.</div>');
                        } else {
                            appendConversations(data.conversations);
                        }
                    }
                },
                error: function() {
                    alert('Error fetching conversations. Please try again.');
                }
            });
        }

        // Attach event listener to the search field
        $('#searchField').on('input', function() {
            var searchTerm = $(this).val().trim();
            if (searchTerm.length > 0) {
                performSearch(searchTerm);
            } else {
                loadConversations();
            }
        });

        // Load initial conversations
        loadConversations();

        // Reset offset in localStorage when reloading the page
        $(window).on('beforeunload', function() {
            localStorage.removeItem('offset');
        });
    });
</script>

<style>
    .conversation-header {
        width: 100%;
        max-width: 800px;
        background-color: #fff;
        border-radius: 5px;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        margin: 0 auto;
    }
   
    .messagesButton {
        padding: 5px 10px;
        background-color: #007bff;
        color: #fff;
        text-decoration: none;
        border-radius: 4px;
    }

    .conversation-list {
        margin: 0;
        padding: 0;
        list-style: none;
    }
    .conversation-item {
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-bottom: 10px;
        padding: 10px;
        background-color: #f9f9f9;
        display: flex;
        align-items: center;
    }
    .url {
        color: inherit;
        text-decoration: none;
        display: flex;
        align-items: center;
        width: 100%;
        max-width: 800px;
    }
    .profile-pic {
        margin-right: 10px;
        border-radius: 50%;
    }
    .conversation-details {
        flex-grow: 1;
    }
    .conversation-item div {
        margin-bottom: 5px;
    }
    .btn-primary {
        padding: 5px 10px;
        background-color: #007bff;
        color: #fff;
        text-decoration: none;
        border-radius: 4px;
    }
    .btn-primary:hover {
        background-color: #0056b3;
    }
    .delete-button {
        margin-left: 10px;
    }
    .btn-danger {
        padding: 5px 10px;
        background-color: #dc3545;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    .btn-danger:hover {
        background-color: #c82333;
    }
</style>