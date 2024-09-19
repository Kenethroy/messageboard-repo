<!DOCTYPE html>
<html>
<head>
    <title><?php echo $this->fetch('title'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo $this->Html->css('styles'); ?>
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" />
   <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <?php echo $this->Html->script('conversation'); ?>
</head>
<body>

    <!-- Header Section -->
    <div class="header">
        <h1>Message Board</h1>
        
        <?php if ($this->Session->check('Auth.User')): ?>
            <div class="user-info">
                <span>Welcome, <?php echo h($this->Session->read('Auth.User.name')); ?>!</span>
                <a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'viewProfile')); ?>">View Profile</a>
                <a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'changeEmailPassword')); ?>">Change Email/Password</a>
                <a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'logout')); ?>">Logout</a>
            </div>
        <?php else: ?>
            <div class="user-info">
                <a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'login')); ?>">Login</a>
            </div>
        <?php endif; ?>

    </div>

    <!-- Main Content -->
    <div class="content">
        <?php echo $this->fetch('content'); ?>
    </div>

</body>
</html>
