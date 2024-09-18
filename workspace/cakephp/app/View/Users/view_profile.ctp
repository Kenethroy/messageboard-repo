<?php
function calculateAge($birthdate) {
    $birthdate = new DateTime($birthdate);
    $today = new DateTime();
    $age = $today->diff($birthdate);
    return $age->y; // Returns the number of full years
}
?>
<h1 class="profile-title">User Profile</h1>
<div class="container">
    <div class="user-profile">
        <div class="profile-pic-section">
            <?php
            $profilePictureFile = WWW_ROOT . 'uploads/' . h($user['User']['profile_picture']);
            $profilePictureUrl = '/uploads/' . h($user['User']['profile_picture']);
            $defaultPictureUrl = '/uploads/default.jpeg'; // Path to the default image
            $imageExists = file_exists($profilePictureFile);

            if (!$imageExists || empty($user['User']['profile_picture'])) {
                $profilePictureUrl = $defaultPictureUrl;
            }
            ?>
            <?php echo $this->Html->image($profilePictureUrl, array('width' => '150px', 'alt' => 'Profile Picture', 'class' => 'profile-pic')); ?>
        </div>
        
        <div class="profile-details">
            <h2><?php echo h($user['User']['name']) . ", " . calculateAge($user['User']['birthdate']); ?></h2>
            <div><strong>Gender:</strong> <?php echo h($user['User']['gender']); ?></div>
            <div><strong>Birthdate:</strong> <?php echo date('F j, Y', strtotime($user['User']['birthdate'])); ?></div>
            <div><strong>Joined:</strong> <?php echo date('F j, Y, g:i A', strtotime($user['User']['created'])); ?></div>
            <div><strong>Last Login:</strong> <?php echo date('F j, Y, g:i A', strtotime($user['User']['last_login_time'])); ?></div>
            
            <div><strong>Hubby:</strong> 
                <p><?php echo h($user['User']['hubby']); ?></p>
            </div>

            <!-- Show Edit Profile button only if logged-in user is viewing their own profile -->
            <?php if ($this->Session->read('Auth.User.id') == $user['User']['id']) : ?>
                <br />
                <?php echo $this->Html->link('Edit Profile', array('action' => 'EditProfile'), array('class' => 'btn btn-primary')); ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
   .container {
       width: 100%;
       max-width: 600px;
       background-color: #fff;
       border-radius: 5px;
       padding: 20px;
       box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
       margin: 20px auto;
   }

   .profile-title {
       text-align: center;
       font-size: 32px;
       margin-bottom: 20px;
   }

   .user-profile {
       display: flex;
       flex-direction: row;
       align-items: center;
   }

   .profile-pic-section {
       flex: 1;
       text-align: center;
   }

   .profile-pic {
       border: 1px solid #ccc;
       padding: 5px;
       border-radius: 5px;
   }

   .profile-details {
       flex: 2;
       padding-left: 20px;
   }

   .profile-details h2 {
       font-size: 24px;
       margin-bottom: 10px;
   }

   .profile-details div {
       margin-bottom: 5px;
       font-size: 16px;
   }

   .profile-details strong {
       font-weight: bold;
   }

   .btn.btn-primary {
       background-color: #007bff;
       border-color: #007bff;
       color: #fff;
       padding: 10px 20px;
       text-decoration: none;
   }

   .btn.btn-primary:hover {
       background-color: #0056b3;
       border-color: #0056b3;
   }
</style>
