// webroot/js/searchContacts.js
$(document).ready(function() {
    $('#search').on('keyup', function() {
        var keyword = $(this).val();
        $.ajax({
            url: 'index', // Ensure this URL is correct
            type: 'GET',
            data: { search: keyword },
            dataType: 'html',
            success: function(response) {
                $('#contact-list').html(response);
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
            }
        });
    });
});
