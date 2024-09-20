

<?php
// Display flash messages if available
if ($this->Session->check('Message.flash')) {
    echo $this->Session->flash();
}
?>

<div class="container">
     <div> 
     <button onclick="window.history.back();" class="btn btn-secondary" style="margin-bottom: 20px; margin-top: 20px; float: right">Back</button>
     <h1 style="text-align: center;">Edit Profile</h1>
     <div> 
         <?php
         if ($this->Session->check('Message.flash')) {
           echo $this->Session->flash();
            }
        ?>  
    </div>
    </div>
    <div style="margin-right: 20px;">
        <div id="imagePreview" style="border: 1px solid #000; width: 100px; height: 100px; display: flex;">
            <?php
            $userProfilePicture = $this->Session->read('Auth.User.profile_picture');
            $profilePictureFile = WWW_ROOT . 'uploads/' . h($userProfilePicture);
            $profilePictureUrl = '/uploads/' . h($userProfilePicture);
            $defaultPictureUrl = '/uploads/default.jpeg';

            if (!empty($userProfilePicture) && file_exists($profilePictureFile)) {
                echo $this->Html->image($profilePictureUrl, array('width' => '100%', 'height' => '100%'));
            } else {
                echo $this->Html->image($defaultPictureUrl, array('width' => '100%', 'height' => '100%'));
            }
            ?>
        </div>

        <!-- Profile picture upload field -->
       
    </div>

    <!-- Form fields section -->
    <div>
        <?php
        echo $this->Form->create('User', array('type' => 'file'));
        ?>

        <!-- Name field -->
         <div class="form-group" style="margin-top: 20px;">
         <?php
        echo $this->Form->input('profile_picture', array('type' => 'file', 'label' => 'Upload Profile Picture', 'id' => 'uploadPic'));
        ?>
        </div>
        <div class="form-group" style="margin-top: 20px;">
            <?php
            echo $this->Form->input('name', array('label' => 'Name', 'required' => true));
            ?>
        </div>

        <!-- Birthdate field -->
        <div class="form-group" style="margin-top: 20px;">
            <?php
            echo $this->Form->input('birthdate', array('label' => 'Birthdate', 'type' => 'text', 'id' => 'datepicker', 'required' => true));
            ?>
        </div>

        <!-- Gender radio buttons -->
        <div class="form-group" style="margin-top: 20px;">
            <label>Gender</label>
            <?php
            echo $this->Form->radio('gender', array(
                'Male' => 'Male',
                'Female' => 'Female'
            ));
            ?>
        </div>

        <!-- Hubby textarea field -->
        <div class="form-group" style="margin-top: 20px;">
            <label>Hubby</label>
            <?php
            echo $this->Form->input('hubby', array('type' => 'textarea', 'label' => false));
            ?>
        </div>

        <!-- Submit button -->
        <div style="margin-top: 20px;">
            <?php
            echo $this->Form->end('Update');
            ?>
        </div>
    </div>
</div>

<!-- CSS for styling the container -->
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

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        font-weight: bold;
        margin-bottom: 5px;
    }

    .form-group input,
    .form-group textarea {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
</style>

<!-- Include jQuery and jQuery UI from CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<!-- JS for handling the datepicker and image preview -->
<script>
$(function() {
    // jQuery date picker for the birthdate field
    $("#datepicker").datepicker({
        dateFormat: 'yy-mm-dd' // Adjust the date format as needed
    });

    // Preview the image when a new file is selected
    $("#uploadPic").change(function(event) {
        var reader = new FileReader();
        reader.onload = function(event) {
            // Display the selected image in the preview container
            var img = $("<img>").attr("src", event.target.result).css("max-width", "100%");
            $("#imagePreview").html(img);
        };
        reader.readAsDataURL(event.target.files[0]);
    });
});
</script>
