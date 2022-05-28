jQuery.noConflict();
(function ($) {

    $('body').find('.oxi-accordions-wrapper').each(function () {
        var _this = $(this), id = _this.attr('id'), _explode = id.split("-"),
                _parent = _explode[3],
                _template = $('.oxi-accordions-ultimate-template-' + _parent),
                _trigger = _template.data('oxi-trigger'),
                _accordions_type = _template.data('oxi-accordions-type'),
                _auto_play = _template.data('oxi-auto-play'),
                _transition = $(".oxi-accordions-content-card-" + _parent).css('transition-duration');

        $(".oxi-accordions-content-card-" + _parent).on("hide.bs.oxicollapse", function (e) {
            $(this).parent().closest(".oxi-accordions-single-card-" + _parent).removeClass("oxi-accordions-expand");
            $(this).children(".oxi-accordions-content-body").removeClass("animate__animated");
            e.stopPropagation();
        });
        $(".oxi-accordions-content-card-" + _parent).on("show.bs.oxicollapse", function (e) {
            $(this).parent().closest(".oxi-accordions-single-card-" + _parent).addClass("oxi-accordions-expand");
            $(this).children(".oxi-accordions-content-body").addClass("animate__animated");
            e.stopPropagation();
        });
        $(".oxi-accordions-content-card-" + _parent).each(function () {
            var This = $(this);
            if (This.hasClass("show")) {
                var animation = This.children(".oxi-accordions-content-body").attr('oxi-animation');
                if (typeof animation !== typeof undefined && animation !== false && animation.length > 0) {
                    This.children(".oxi-accordions-content-body").addClass('animate__animated');
                }
                This.parent().closest(".oxi-accordions-single-card-" + _parent).addClass("oxi-accordions-expand");
            }
        });

        if (_trigger === 'auto') {
            _index_number = 0
            var total_accordions = $(".oxi-accordions-single-card-" + _parent).length;
            function autoplay() {
                $(".oxi-accordions-single-card-" + _parent + " .oxi-accordions-header").eq(_index_number).trigger('click');
                _index_number++;
                if (_index_number === total_accordions) {
                    _index_number = 0;
                }
            }
            var interval = setInterval(autoplay, _auto_play);
        }

        if (_trigger === 'hover') {
            $(".oxi-accordions-single-card-" + _parent).on("mouseenter", function () {
                $(this).children(".oxi-accordions-head-outside-body").children(".oxi-accordions-content-card").oxicollapse("show");
            }).on("mouseleave", function () {
                $(this).children(".oxi-accordions-head-outside-body").children(".oxi-accordions-content-card").oxicollapse("hide");
            });
        }


//        var maxHeight = Math.max.apply(null, $(".oxi-accordions-single-card-" + _parent + " .oxi-accordions-header-card .oxi-accordions-expand-collapse-icon .oxi-icons").map(function ()
//        {
//            return $(this).height();
//        }).get());
//        var maxWeight = Math.max.apply(null, $(".oxi-accordions-single-card-" + _parent + " .oxi-accordions-header-card .oxi-accordions-expand-collapse-icon .oxi-icons").map(function ()
//        {
//            return $(this).width();
//        }).get());
//        if (maxHeight > maxWeight) {
//            maxHeightWeight = maxHeight;
//        } else {
//            maxHeightWeight = maxWeight;
//        }
//
//
//
//
//        $(".oxi-accordions-single-card-" + _parent + " .oxi-accordions-expand-collapse-icon .oxi-icons").each(function () {
//            var This = $(this);
//            This.css('height', maxHeightWeight).css('width', maxHeightWeight);
//        });
//
//
//        var maxHeight = Math.max.apply(null, $(".oxi-accordions-additional-icon-" + _parent + " .oxi-icons").map(function ()
//        {
//            return $(this).height();
//        }).get());
//        var maxWeight = Math.max.apply(null, $(".oxi-accordions-additional-icon-" + _parent + " .oxi-icons").map(function ()
//        {
//            return $(this).width();
//        }).get());
//        if (maxHeight > maxWeight) {
//            maxHeightWeight = maxHeight;
//        } else {
//            maxHeightWeight = maxWeight;
//        }
//
//        $(".oxi-accordions-additional-icon-" + _parent + " .oxi-icons").each(function () {
//            var This = $(this);
//            This.css('height', maxHeightWeight).css('width', maxHeightWeight);
//        });


        if ($("#" + id + " .oxi-accordions-content-expand-button")[0]) {
            $.fn.getBg = function () {
                return $(this).parents().filter(function () {
                    // only checking for IE and Firefox/Chrome. add values as cross-browser compatibility is required
                    var color = $(this).css('background-color');
                    return color != 'transparent' && color != 'rgba(0, 0, 0, 0)';
                }).eq(0).css('background-color');
            };
            var value = $('.oxi-accordions-content-expand-button').getBg();
            var _Style = '#' + id + " .oxi-accordions-content-card-" + _parent + '> .oxi-accordions-content-body.oxi-accordions-content-height.oxi-accordions-content-mx-height-interface-button:not(.oxi-button-expand) > .oxi-accordions-content-expand-button{background: linear-gradient(to top, ' + value + ' 20%, rgba(255,255,255, 0) 100%);}';
            $('<style>' + _Style + '</style>').appendTo("#" + id);
        }


        $("#" + id + " .oxi-accordions-ultimate-type-search").on('change keyup paste click', function () {
            Text = $(this).val();
            if (Text.length >= 3) {
                $("#" + id + " .oxi-accordions-single-card").each(function () {
                    _This = $(this);
                    _ThisClass = _This.attr('id');
                    $("#" + _ThisClass + ':not(:CaseInsensitive(' + Text + '))').hide();
                    $("#" + _ThisClass + ':CaseInsensitive(' + Text + ')').show();
                });
            } else {
                $("#" + id + " .oxi-accordions-single-card").show();
            }
        });



    });
    $.expr[':'].CaseInsensitive = function (n, i, m) {
        return $(n).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
    };



    $(function () {
        (function () {
            $('.oxi-accordions-content-expand-body').bind('click', function () {
                var $story = $(this).parent('.oxi-accordions-content-expand-button').parent('.oxi-accordions-content-body');
                var collapsedHeight = $story.attr('collapsed-height');

                if (typeof collapsedHeight !== 'undefined' && collapsedHeight !== false) {
                    var collapsedHeight = $story.attr('collapsed-height');
                } else {
                    var collapsedHeight = $story.css('maxHeight');
                    $story.attr('collapsed-height', collapsedHeight);
                }

                var expandHeight = $story.attr('expandHeight');
                if (typeof expandHeight !== 'undefined' && expandHeight !== false) {
                    var expandHeight = $story.attr('expandHeight');
                } else {
                    var expandHeight = $story.prop('scrollHeight');
                    $story.attr('expandHeight', expandHeight);
                }

                transitionDuration = $story.parent().css('transition-duration');
                var floatTransitionDuration = parseFloat(transitionDuration);
                if (!floatTransitionDuration) {
                    transitionDuration = 500;
                } else {
                    transitionDuration = transitionDuration.split(',')[0];
                    transitionDuration = parseFloat(transitionDuration) * 1000;
                }
                if ($story.hasClass('oxi-button-expand')) {
                    $story.animate({'maxHeight': collapsedHeight}, transitionDuration);
                    $story.removeClass('oxi-button-expand');
                } else {
                    $story.animate({'maxHeight': expandHeight}, transitionDuration);
                    $story.addClass('oxi-button-expand');
                }
            });

        })();
    });

    $(".oxi-accordions-head-expand-collapse-position-outside").each(function () {
        var Icon = $(this).children('.oxi-accordions-expand-collapse'),
                Header = $(this).children('.oxi-accordions-head-outside-body').children('.oxi-accordions-header-card').children('.oxi-accordions-header-body');
        IconouterHeight = Icon.outerHeight();
        HeaderouterHeight = Header.outerHeight();
        if (HeaderouterHeight > IconouterHeight) {
            IconHeight = Icon.height();
            Iconwidth = Icon.width();
            height = HeaderouterHeight - (IconouterHeight - IconHeight);
            width = HeaderouterHeight - (IconouterHeight - Iconwidth);
            Icon.height(height);
            Icon.width(width);
        } else {
            Height = IconouterHeight - (HeaderouterHeight - Header.height());
            Header.height(Height);
        }
    });

    $("div[oxi-animation]").each(function () {
        var animation = $(this).attr('oxi-animation');
        Ths = $(this);
        if (typeof animation !== typeof undefined && animation !== false && animation.length > 0) {
            Ths.addClass(animation);
        }
    });

    $(document).ready(function () {
        $('.oxi-accordions-preloader').each(function () {
            $(this).css("opacity", "1");
        });
    });
    if ($("#oxi-addons-iframe-background-color").length) {
        var value = $('#oxi-addons-iframe-background-color').val();
        $('.shortcode-addons-template-body').css('background', value);
    }
})(jQuery);

