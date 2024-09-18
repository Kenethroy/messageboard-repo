<h1>Edit Profile</h1>

<?php
// Display any flash messages if present
if ($this->Session->check('Message.flash')) {
    echo $this->Session->flash();
}
?>

<?php
// Create the form and allow file uploads
echo $this->Form->create('User', array('type' => 'file'));
echo $this->Form->input('name', array('label' => 'Name', 'required' => true));
echo $this->Form->input('email', array('label' => 'Email', 'type' => 'email', 'required' => true));
echo $this->Form->input('gender', array('type' => 'radio', 'options' => array('Male' => 'Male', 'Female' => 'Female'), 'label' => 'Gender'));
echo $this->Form->input('hubby', array('label' => 'Hubby'));
echo $this->Form->input('birthdate', array('label' => 'Birthdate', 'type' => 'text', 'id' => 'datepicker', 'required' => true));
echo $this->Form->input('profile_picture', array('type' => 'file', 'label' => 'Profile Picture'));

// Image preview container (empty initially)
?>
<div id="imagePreview" style="margin-top: 10px;"></div>

<?php
// Close the form with a save button
echo $this->Form->end('Save');
?>

<!-- Include jQuery and jQuery UI from CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<script>
$(function() {
    // jQuery date picker for the birthdate field
    $("#datepicker").datepicker({
        dateFormat: 'yy-mm-dd' // Adjust the date format as per requirement
    });

    // Preview the image when a file is selected
    $("input[type='file']").change(function(event) {
        var reader = new FileReader();
        reader.onload = function(event) {
            // Create an image element and set its source to the selected file
            var img = $("<img>").attr("src", event.target.result).css("width", "100px");
            // Replace the content of #imagePreview with the new image
            $("#imagePreview").html(img);
        };
        // Read the selected file and trigger the onload event to display the image
        reader.readAsDataURL(event.target.files[0]);
    });
});
</script>
