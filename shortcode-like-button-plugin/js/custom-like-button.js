jQuery(document).ready(function($) {
    $('button.my-like-button').on('click', function() {
        var postId = $(this).data('post-id');
        var likeCountElement = $('.like-count-' + postId);
        var currentCount = parseInt(likeCountElement.text());

        // サーバーにいいねを送信してデータベースを更新
        $.ajax({
            type: 'POST',
            url: custom_like_button_params.ajax_url,
            data: {
                action: $(this).data('action'),
                post_id: postId,
                security: custom_like_button_params.security
            },
            success: function(response) {
                likeCountElement.text(currentCount + 1);
            }
        });
    });
});
