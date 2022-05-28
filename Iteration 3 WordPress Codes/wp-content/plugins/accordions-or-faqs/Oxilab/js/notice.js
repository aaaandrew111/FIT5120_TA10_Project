jQuery.noConflict();
(function ($) {
    "use strict";
    $(document).on("click", ".oxi-accordions-support-reviews", function (e) {
        e.preventDefault();
        var _This = $(this);
        $.ajax({
            url: oxi_accordions_reviews_notice.ajaxurl,
            type: 'post',
            data: {
                action: 'oxi_accordions_reviews_notice',
                _wpnonce: oxi_accordions_reviews_notice.nonce,
                notice: _This.attr('sup-data')
            },
            success: function (response) {
               
                $('.oxi-accordions-review-notice').remove();
            },
            error: function (error) {
                console.log('Something went wrong!');
            },
        });
    });
})(jQuery);
