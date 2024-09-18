<?php
$conversationId = $conversation['Conversation']['id'];
$latestMessage = $conversation['Message']['message'];
$messageDate = $conversation['Message']['created'];
$latestMessageSenderId = $conversation['Message']['user_id'];

$senderId = $conversation['Conversation']['sender_id'];
$receiverId = $conversation['Conversation']['receiver_id'];
$loggedInUserId = $this->Session->read('Auth.User.id');

$senderProfilePic = $conversation['SenderUser']['profile_picture'];
$receiverProfilePic = $conversation['ReceiverUser']['profile_picture'];
$senderName = h($conversation['SenderUser']['name']);
$receiverName = h($conversation['ReceiverUser']['name']);

if ($latestMessageSenderId == $loggedInUserId) {
    $messageDisplay = 'You: ' . h($latestMessage);
    $profilePicUrl = ($latestMessageSenderId == $senderId ? $senderProfilePic : $receiverProfilePic); 
    $profilePicAlt = 'Your profile picture';
} else {
    $messageDisplay = ($latestMessageSenderId == $senderId ? $senderName : $receiverName) . ': ' . h($latestMessage);
    $profilePicUrl = ($latestMessageSenderId == $senderId ? $senderProfilePic : $receiverProfilePic);
    $profilePicAlt = ($latestMessageSenderId == $senderId ? $senderName : $receiverName) . "'s profile picture";
}

$profilePictureUrl = '/uploads/' . h($profilePicUrl);
$profilePicturePath = WWW_ROOT . 'uploads/' . h($profilePicUrl);
$imageExists = file_exists($profilePicturePath);
?>

<div class="conversation-item" data-id="<?php echo $conversationId; ?>">
    <a href="<?php echo $this->Html->url(array('action' => 'view', $conversationId)); ?>" class="conversation-link">
        <div class="profile-pic">
            <?php
                $placeholderUrl = 'profile-placeholder.png';
                echo $this->Html->image($imageExists ? $profilePictureUrl : $placeholderUrl, array('width' => '100px', 'alt' => h($profilePicAlt)));
            ?>
        </div>
        <div class="conversation-details">
            <div class="latest-message">
                <?php echo $messageDisplay; ?>
            </div>
            <div class="message-date">
                <?php echo h($messageDate); ?>
            </div>
        </div>
    </a>
    <div class="delete-button">
        <button class="btn btn-danger delete-conversation" data-id="<?php echo $conversationId; ?>">Delete</button>
    </div>
</div>
