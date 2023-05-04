jQuery(document).ready(function($) {
    // Handle form submission
    $('#my-shortener-form').on('submit', function(e) {
        e.preventDefault();

        // Get the link from the input field
        let link = $('#my-shortener-link').val();

        // Send an AJAX request to generate a shortened link
        $.ajax({
            url: my_shortener.ajax_url,
            type: 'POST',
            data: {
                action: 'my_shortener_generate_short_link',
                link: link,
                nonce: my_shortener.nonce
            },
            success: function(response) {
                if (response.success) {
                    // Display the shortened link to the user
                    let shortenedLink = response.data.shortened_link;
                    $('#my-shortener-short-link').val(shortenedLink);
                    $('#my-shortener-success').show();
                    $('#my-shortener-error').hide();
                } else {
                    // Display an error message to the user
                    let error = response.data;
                    $('#my-shortener-error').text(error).show();
                    $('#my-shortener-success').hide();
                }
            },
            error: function(xhr, status, error) {
                // Display an error message to the user
                $('#my-shortener-error').text('An error occurred while generating the shortened link.').show();
                $('#my-shortener-success').hide();
            }
        });
    });
});
