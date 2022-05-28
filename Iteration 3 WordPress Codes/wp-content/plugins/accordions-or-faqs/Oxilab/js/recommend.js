jQuery.noConflict();
(function ($) {
    "use strict";
    $(document).on("click", ".oxi-plugins-admin-recommended-dismiss", function (e) {

        e.preventDefault();
        var _This = $(this);
        console.log(_This.attr('sup-data'));
        $.ajax({
            url: oxi_accordions_admin_recommended.ajaxurl,
            type: 'post',
            data: {
                action: 'oxi_accordions_admin_recommended',
                _wpnonce: oxi_accordions_admin_recommended.nonce,
                notice: _This.attr('sup-data')
            },
            success: function (response) {
                $('.oxi-accordions-admin-notifications').remove();
            },
            error: function (error) {
                console.log('Something went wrong!');
            },
        });
        return false;
    });
})(jQuery);
