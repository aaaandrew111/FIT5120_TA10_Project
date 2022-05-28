jQuery.noConflict();
(function ($) {


    var urlParams = new URLSearchParams(window.location.search);
    var styleid = urlParams.get("styleid");
    var childid = "";
    var WRAPPER = $('#oxi-addons-preview-data').attr('template-wrapper');

    var IFRAME = $("#oxi-addons-preview-iframe");
    var IFRAMEBODYCLASS = '.shortcode-addons-template-body';
    var IFRAMETABSWRAPPER = '#oxi-accordions-wrapper-' + styleid;
    var plugin_name = 'accordions';
    function NEWRegExp(par = '') {
        return new RegExp(par, "g");
    }

    function replaceStr(str, find, replace) {
        for (var i = 0; i < find.length; i++) {
            str = str.replace(new RegExp(find[i], 'gi'), replace[i]);
        }
        return str;
    }

    async function OxiAccordionsRestApi(functionname, rawdata, styleid, childid, callback) {
        if (functionname === "") {
            alert('Confirm Function Name');
            return false;
        }
        let result;
        try {
            result = await $.ajax({
                url: oxiaccordionsultimate.root + 'oxiaccordionsultimate/v1/' + functionname,
                method: 'POST',
                dataType: "json",
               
                data: {
                    _wpnonce: oxiaccordionsultimate.nonce,
                    styleid: styleid,
                    childid: childid,
                    rawdata: rawdata
                }
            });
            console.log(result);
            return callback(result);

        } catch (error) {
            console.error(error);
        }
    }
    var WRAPPER = $('#oxi-addons-preview-data').attr('template-wrapper');
    $(".oxi-addons-tabs-ul li:first").addClass("active");
    $(".oxi-addons-tabs-content-tabs:first").addClass("active");
    $(".oxi-addons-tabs-ul li").click(function () {
        if ($(this).hasClass('active')) {
            $(".oxi-addons-tabs-ul li").removeClass("active");
            var activeTab = $(this).attr("ref");
            $(activeTab).removeClass("active");
        } else {
            $(".oxi-addons-tabs-ul li").removeClass("active");
            $(this).toggleClass("active");
            $(".oxi-addons-tabs-content-tabs").removeClass("active");
            var activeTab = $(this).attr("ref");
            $(activeTab).addClass("active");
        }
    });
    $("#oxi-addons-setting-reload").click(function () {
        location.reload();
    });
    $(".oxi-head").click(function () {
        var self = $(this).parent();
        self.toggleClass("oxi-admin-head-d-none");
    });
    $(".shortcode-addons-templates-right-panel-heading").click(function () {
        var self = $(this).parent();
        self.toggleClass("oxi-admin-head-d-none");
    });
    $("#oxi-addons-form-submit").submit(function (e) {
        e.preventDefault();
        return false;
    });
    $("#shortcode-addons-style-change-submit").submit(function (e) {
        e.preventDefault();
        return false;
    });
    $("#shortcode-addons-name-change-submit").submit(function (e) {
        e.preventDefault();
        return false;
    });
    $("#shortcode-addons-name-change-submit").submit(function (e) {
        e.preventDefault();
        return false;
    });
    $("#shortcode-addons-template-modal-form").submit(function (e) {
        e.preventDefault();
        return false;
    });
    function formatState(state) {
        if (!state.id) {
            return state.text;
        }
        var baseUrl = oxiaccordionsultimate.plugin + "assets/backend/img/icons";
        var $state = $(
                '<span><img src="' + baseUrl + '/' + state.element.value.toLowerCase() + '.png" class="oxi-accordions-icon-admin-style" /> ' + state.text + '</span>'
                );
        return $state;


    }
    $("#oxi-accordions-icon-style").on("change", function (e) {
        _This = $(this);
        _Value = _This.val();


        if (_Value === 'angle') {
            _Open = 'fas fa-angle-down';
            _Close = 'fas fa-angle-right';
        } else if (_Value === 'agnle-2') {
            _Open = 'fas fa-angle-up';
            _Close = 'fas fa-angle-down';
        } else if (_Value === 'angle-3') {
            _Open = 'fas fa-angle-down';
            _Close = 'fas fa-angle-up';
        } else if (_Value === 'angle-double') {
            _Open = 'fas fa-angle-double-down';
            _Close = 'fas fa-angle-double-right';
        } else if (_Value === 'angle-double-down') {
            _Open = 'fas fa-angle-double-down';
            _Close = 'fas fa-angle-double-up';
        } else if (_Value === 'angle-double-up') {
            _Open = 'fas fa-angle-double-up';
            _Close = 'fas fa-angle-double-down';
        } else if (_Value === 'arrow') {
            _Open = 'fas fa-arrow-down';
            _Close = 'fas fa-arrow-right';
        } else if (_Value === 'arrow-up') {
            _Open = 'fas fa-arrow-up';
            _Close = 'fas fa-arrow-down';
        } else if (_Value === 'carret') {
            _Open = 'fas fa-caret-down';
            _Close = 'fas fa-caret-right';
        } else if (_Value === 'carret-up') {
            _Open = 'fas fa-caret-up';
            _Close = 'fas fa-caret-down';
        } else if (_Value === 'chevron') {
            _Open = 'fas fa-chevron-down';
            _Close = 'fas fa-chevron-right';
        } else if (_Value === 'hand') {
            _Open = 'far fa-hand-point-down';
            _Close = 'far fa-hand-point-right';
        } else if (_Value === 'plus') {
            _Open = 'fas fa-plus';
            _Close = 'fas fa-minus';
        } else if (_Value === 'tick') {
            _Open = 'fas fa-check';
            _Close = 'fas fa-times';
        }
        if (_Value !== 'custom') {
            _Open_id = "#oxi-accordions-head-expand-icon";
            _Close_id = "#oxi-accordions-head-collapse-icon";
            $(_Open_id).changeVal(_Open);
            $(_Close_id).changeVal(_Close);
        }
    });


    $.fn.changeVal = function (v) {
        return this.val(v).trigger("change");

    };


    $('.shortcode-control-type-select .shortcode-addons-select-input').each(function (e) {
        if (!$(this).parents('.shortcode-addons-form-repeater-store').length) {

            if ($(this).attr('id') === 'oxi-accordions-icon-style') {
                $(this).select2({
                    width: '100%',
                    templateResult: formatState
                });
            } else {
                $(this).select2({width: '100%'});
            }



        }
    });
    $('.shortcode-form-control').each(function (e) {
        if ($(this).hasClass('shortcode-addons-form-responsive-tablet')) {
            $(this).addClass('shortcode-addons-responsive-display-none');
        } else if ($(this).hasClass('shortcode-addons-form-responsive-mobile')) {
            $(this).addClass('shortcode-addons-responsive-display-none');
        }
    });
    $(document.body).on("click", ".shortcode-form-control-responsive-switchers a", function () {
        $curent = $(this).data('device');
        $arr = ['desktop', 'tablet', 'mobile'];
        if ($curent === 'desktop') {
            $("#wpbody").toggleClass('shortcode-responsive-switchers-open');
        } else {
            $("#wpbody").addClass('shortcode-responsive-switchers-open');
        }
        $.each($arr, function (i, value) {
            if (value === $curent) {
                $(".shortcode-form-responsive-switcher-" + value).addClass('active');
                $(".shortcode-addons-form-responsive-" + value).removeClass('shortcode-addons-responsive-display-none');
                $(".oxi-addons-preview-wrapper").addClass('oxi-addons-preview-wrapper-' + value);
            } else {
                $(".shortcode-form-responsive-switcher-" + value).removeClass('active');
                $(".shortcode-addons-form-responsive-" + value).addClass('shortcode-addons-responsive-display-none');
                $(".oxi-addons-preview-wrapper").removeClass('oxi-addons-preview-wrapper-' + value);
            }
        });
    });
    $.fn.uncheckableRadio = function () {
        var $root = this;
        $root.each(function () {
            var $radio = $(this);
            if ($radio.prop('checked')) {
                $radio.data('checked', true);
            } else {
                $radio.data('checked', false);
            }

            $radio.click(function () {
                var $this = $(this);
                if ($this.data('checked')) {
                    $this.prop('checked', false);
                    $this.data('checked', false);
                    $this.trigger('change');
                } else {
                    $this.data('checked', true);
                    $this.closest('form').find('[name="' + $this.prop('name') + '"]').not($this).data('checked', false);
                }
            });
        });
        return $root;
    };
    $('.shortcode-addons-form-toggle [type=radio]').uncheckableRadio();
    function PopoverActiveDeactive($_This) {
        $(".shortcode-form-control").not($_This.parents()).removeClass('popover-active');
        $_This.closest(".shortcode-form-control").toggleClass('popover-active');
        event.stopPropagation();
    }
    $(document.body).on("click", ".shortcode-form-control-content-popover .shortcode-form-control-input-wrapper", function (event) {
        PopoverActiveDeactive($(this));
    });
    $(document.body).on("click", ".shortcode-form-control-input-link", function (event) {
        PopoverActiveDeactive($(this));
        event.stopPropagation();
    });


    $('.shortcode-control-type-control-tabs .shortcode-control-type-control-tab-child:first-child').addClass('shortcode-control-tab-active');
    $('.shortcode-control-type-control-tabs .shortcode-form-control-tabs-content:first-child').removeClass('shortcode-control-tab-close');
    $(document.body).on("click", ".shortcode-control-type-control-tab-child", function () {
        $(this).siblings().removeClass("shortcode-control-tab-active");
        $(this).addClass('shortcode-control-tab-active');
        var index = $(this).index();
        $(this).parent().parent('.shortcode-form-control-content-tabs').next().children('.shortcode-form-control-tabs-content').addClass('shortcode-control-tab-close');
        $(this).parent().parent('.shortcode-form-control-content-tabs').next().children('.shortcode-form-control-tabs-content:eq(' + index + ')').removeClass('shortcode-control-tab-close');
        console.log(index);
    });

    (function ($) {
        setTimeout(function () {
            var data = 'body#tinymce.wp-editor{font-family:Arial,Helvetica,sans-serif!important}body#tinymce.wp-editor p{font-size:14px!important}body#tinymce.wp-editor h1{font-size:1.475em}body#tinymce.wp-editor h2{font-size:1.065em}body#tinymce.wp-editor h3{font-size:1.065em}body#tinymce.wp-editor h4{font-size:.9}body#tinymce.wp-editor h5{font-size:.75}body#tinymce.wp-editor h6{font-size:.65em}body#tinymce.wp-editor .gallery-caption,body#tinymce.wp-editor figcaption{font-size:.65em}body#tinymce.wp-editor .editor-post-title__block .editor-post-title__input{font-size:1.77em}body#tinymce.wp-editor .editor-default-block-appender .editor-default-block-appender__content{font-size:14px}body#tinymce.wp-editor .wp-block-paragraph.has-drop-cap:not(:focus)::first-letter{font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen,Ubuntu,Cantarell,"Fira Sans","Droid Sans","Helvetica Neue",sans-serif;font-size:2.12em;margin:0 .15em 0 0}.wp-block-cover .wp-block-cover-text,.wp-block-cover h2{font-size:1.07em;padding-left:.7rem;padding-right:.7rem}.wp-block-gallery .blocks-gallery-image figcaption,.wp-block-gallery .blocks-gallery-item figcaption,.wp-block-gallery .gallery-item .gallery-caption{font-size:.65em}.wp-block-button .wp-block-button__link{line-height:1.8;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen,Ubuntu,Cantarell,"Fira Sans","Droid Sans","Helvetica Neue",sans-serif;font-size:.7em;font-weight:700}.wp-block-quote.is-large p,.wp-block-quote.is-style-large p{font-size:1.07em;margin-bottom:.4em;margin-top:.4em}.wp-block-quote .wp-block-quote__citation,.wp-block-quote cite,.wp-block-quote footer{font-size:.65em}.wp-block[data-type="core/pullquote"] blockquote>.block-library-pullquote__content .editor-rich-text__tinymce[data-is-empty=true]::before,.wp-block[data-type="core/pullquote"] blockquote>.editor-rich-text p,.wp-block[data-type="core/pullquote"] p,.wp-block[data-type="core/pullquote"][data-align=left] blockquote>.block-library-pullquote__content .editor-rich-text__tinymce[data-is-empty=true]::before,.wp-block[data-type="core/pullquote"][data-align=left] blockquote>.editor-rich-text p,.wp-block[data-type="core/pullquote"][data-align=left] p,.wp-block[data-type="core/pullquote"][data-align=right] blockquote>.block-library-pullquote__content .editor-rich-text__tinymce[data-is-empty=true]::before,.wp-block[data-type="core/pullquote"][data-align=right] blockquote>.editor-rich-text p,.wp-block[data-type="core/pullquote"][data-align=right] p{font-size:1.067em;margin-bottom:.4em;margin-top:.4em}.wp-block[data-type="core/pullquote"] .wp-block-pullquote__citation,.wp-block[data-type="core/pullquote"][data-align=left] .wp-block-pullquote__citation,.wp-block[data-type="core/pullquote"][data-align=right] .wp-block-pullquote__citation{font-size:.65em}.wp-block-file .wp-block-file__button{font-size:.75em}.wp-block-separator.is-style-dots:before{font-size:1.067em;letter-spacing:calc(2 * .63rem);padding-left:calc(2 * .63rem)}.wp-block-categories li,.wp-block-latest-posts li,ul.wp-block-archives li{font-size:calc(14px * 1.125);padding-bottom:.6rem}';
            var head = $(".mce-container-body iframe").contents().find("head");
            var css = '<style type="text/css">' + data + '</style>';
            $(head).append(css);
        }, 2000);
    })($);

    $("#oxi-addons-list-data-modal-open").on("click", function () {
        $("#oxi-addons-list-data-modal").modal("show");
        $('#oxi-template-modal-form').trigger("reset");
        $('#oxi-template-modal-submit').html('Submit');
        $('#shortcode-addons-template-modal-form *:checkbox').each(function (i, e) {
            if ($(this).attr('ckdflt') === 'true') {
                $(this).attr('checked', true);
            } else {
                $(this).attr('checked', false);
            }
        });
        $('#shortcode-addons-template-modal-form *:radio').each(function (i, e) {
            if ($(this).attr('ckdflt') === 'true') {
                $(this).attr('checked', true);
            } else {
                $(this).attr('checked', false);
            }
        });
        $('#shortcode-addons-template-modal-form .shortcode-addons-media-control-image-load').each(function (i, e) {
            $(this).attr('style', $(this).attr('ckdflt'));
        });
        $('#shortcodeitemid').val("");
        $("[data-condition]").each(function (index, value) {
            $(this).addClass('shortcode-addons-form-conditionize');
        });

        $('.shortcode-addons-form-conditionize').conditionize();
    });
    $("[data-condition]").each(function (index, value) {
        $(this).addClass('shortcode-addons-form-conditionize');
    });





    function OxiAddonsPreviewDataLoader() {
        OxiAccordionsRestApi(
                'elements_template_render_data',
                JSON.stringify($("#oxi-addons-form-submit").serializeJSON({checkboxUncheckedValue: "0"})),
                styleid, childid,
                function (callback) {
                    document.getElementById('oxi-addons-preview-iframe').src += '';
                });
    }

    function OxiAddonsModalConfirm(id, data) {
        if (($("#OXIAADDONSCHANGEDPOPUP").data("bs.modal") || {})._isShown) {
            setTimeout(function () {
                $("#OXIAADDONSCHANGEDPOPUP").modal("hide");
            }, 3000);
            $(id).html(data);
        }
    }







    $("#addonsstylenamechange").on("click", function (e) {
        e.preventDefault();
        var rawdata = JSON.stringify($("#shortcode-addons-name-change-submit").serializeJSON({checkboxUncheckedValue: "0"}));
        var functionname = "template_name";
        $(this).html('<span class="dashicons dashicons-admin-generic"></span>');
        OxiAccordionsRestApi(functionname, rawdata, styleid, childid, function (callback) {
            if (callback === "success") {
                $("#OXIAADDONSCHANGEDPOPUP .icon-box").html('<span class="dashicons dashicons-yes"></span>');
                $("#OXIAADDONSCHANGEDPOPUP .modal-body.text-center h4").html("Superb!");
                $("#OXIAADDONSCHANGEDPOPUP .modal-body.text-center p").html("Shortcode name has been saved successfully.");
                $("#OXIAADDONSCHANGEDPOPUP").modal("show");
                OxiAddonsModalConfirm("#addonsstylenamechange", "Save");
            }
        });
    });
    $("#oxi-addons-modal-rearrange").sortable({
        axis: 'y',
        update: function (event, ui) {
            var list_sortable = $(this).sortable('toArray').toString();
            $('#oxi-addons-list-rearrange-data').val(list_sortable);
        }
    });
    $("#oxi-addons-rearrange-data-modal-open").on("click", function () {
        var rawdata = 'rearrange_modal_data';
        var functionname = "elements_rearrange_modal_data";
        $("#modal-rearrange-store-file").hide();
        $("#oxi-addons-list-rearrange-saving").show();
        $("#oxi-addons-modal-rearrange").hide();
        $("#oxi-addons-list-rearrange-modal").modal("show");
        $("#oxi-addons-modal-rearrange").html('');
        var d = $("#modal-rearrange-store-file").html();
        $("#oxi-addons-list-rearrange-data").val('');
        OxiAccordionsRestApi(functionname, rawdata, styleid, childid, function (callback) {
            var $number = 1;
            $.each($.parseJSON(callback), function (key, value) {
                data = d.replace(NEWRegExp("{{id}}"), key);
                $.each(value, function (k, v) {
                    if (v === '') {
                        v = 'Rearrange Title ' + $number;
                    }
                    data = data.replace(NEWRegExp('{{' + k + '}}'), v);
                });
                $("#oxi-addons-modal-rearrange").append(data);
                $("#oxi-addons-list-rearrange-saving").hide();
                $("#oxi-addons-modal-rearrange").show();
                $number++;
            });
        });
    });
    $("#oxi-addons-list-rearrange-submit").on("click", function (e) {
        e.preventDefault();
        $(this).val('Savings..');
        var rawdata = $('#oxi-addons-list-rearrange-data').val();
        if (rawdata === '') {
            alert('Kindly Rearrange, Then  Click to saved');
            return false;
        }
        
        var functionname = "elements_template_rearrange_save_data";
        OxiAccordionsRestApi(functionname, rawdata, styleid, childid, function (callback) {
            if (callback === "success") {
                $("#OXIAADDONSCHANGEDPOPUP .icon-box").html('<span class="dashicons dashicons-yes"></span>');
                $("#OXIAADDONSCHANGEDPOPUP .modal-body.text-center h4").html("Great!");
                $("#OXIAADDONSCHANGEDPOPUP .modal-body.text-center p").html("Rearrange has been saved successfully.");
                $("#OXIAADDONSCHANGEDPOPUP").modal("show");
                OxiAddonsModalConfirm("#oxi-addons-list-rearrange-submit", "Save");
                $("#oxi-addons-list-rearrange-submit").val('Save');
                $("#oxi-addons-list-rearrange-modal").modal("hide");
                OxiAddonsPreviewDataLoader();
            }
        });
    });
    IFRAME.load(function () {
        IFRAME.contents().on("click", ".shortcode-addons-template-item-edit", function (e) {
            e.preventDefault();
            $('#oxi-template-modal-form')[0].reset();
            var rawdata = "edit";
            var functionname = "elements_template_modal_data_edit";
            var childid = $(this).attr("value");
            OxiAccordionsRestApi(functionname, rawdata, styleid, childid, function (callback) {
                if (callback === "Go to hell") {
                    alert("Data Error");
                } else {
                    $("#shortcode-addons-template-modal-form input[type='checkbox']").attr('checked', false);
                    $.each($.parseJSON(callback), function (key, value) {
                        if ($("#" + key + "").hasClass('wp-editor-area')) {
                            if ($("#wp-" + key + "-wrap").hasClass('tmce-active')) {
                                tinymce.get(key).setContent(value);
                            } else {
                                $('#' + key + '').html(value);
                            }
                        } else {
                            var tp = $('input[name="' + key + '"]').attr("type");
                            if (typeof tp !== 'undefined') {
                                if (tp === 'radio') {
                                    $('input[name=' + key + ']').val([value]);
                                } else if (tp === 'checkbox') {
                                    if (value != '0') {
                                        $('input[name=' + key + ']').attr('checked', 'true');
                                    } else {
                                        $('input[name=' + key + ']').prop('checked', false).removeAttr('checked');
                                    }
                                } else if (tp === 'hidden') {

                                    $('input[name=' + key + ']').val(value);
                                    if ($('input[name=' + key + ']').hasClass('shortcode-addons-media-control-link')) {
                                        $('#' + key).siblings('.shortcode-addons-media-control').removeClass('shortcode-addons-media-control-hidden-button');
                                        $('input[name=' + key + ']').siblings('.shortcode-addons-media-control').children('.shortcode-addons-media-control-image-load').css({'background-image': 'url(' + value + ')'});
                                    }
                                } else {
                                    $("#" + key).val(value);
                                }
                            } else {
                                $("#" + key).val(value);
                            }
                        }

                    });
                    $("[data-condition]").each(function (index, value) {
                        $(this).addClass('shortcode-addons-form-conditionize');
                    });
                    $('.shortcode-addons-form-conditionize').conditionize();
                    $('.shortcode-control-type-select .shortcode-addons-select-input').each(function (e) {
                        $id = $(this).attr('id');
                        $('#' + $id).select2({width: '100%'});
                    });
                    $("#oxi-template-modal-submit").html("Submit");
                    $("#oxi-addons-list-data-modal").modal("show");
                }
            });
        });
        IFRAME.contents().on("click", ".shortcode-addons-template-item-delete", function (e) {
            e.preventDefault();
            var rawdata = "delete";
            var functionname = "elements_template_modal_data_delete";
            var childid = $(this).attr("value");
            var status = confirm("Do you Want to Delete this?");
            if (status === false) {
                return false;
            } else {
                OxiAccordionsRestApi(functionname, rawdata, styleid, childid, function (callback) {
                    if (callback === "done") {
                        $("#OXIAADDONSCHANGEDPOPUP .icon-box").html('<span class="dashicons dashicons-trash"></span>');
                        $("#OXIAADDONSCHANGEDPOPUP .modal-body.text-center h4").html("Deleted :(");
                        $("#OXIAADDONSCHANGEDPOPUP .modal-body.text-center p").html("Your data has been delete successfully.");
                        $("#OXIAADDONSCHANGEDPOPUP").modal("show");
                        OxiAddonsModalConfirm(".shortcode-addons-template-item-delete", "Delete")
                        OxiAddonsPreviewDataLoader();
                    } else {
                        alert("Data Error");
                    }
                });
            }
        });
        setInterval(function () {
            var frame = 'oxi-addons-preview-iframe';
            var actual = document.getElementById(frame).contentWindow.document.body.scrollHeight + 200,
                    current = $('#' + frame).outerHeight();
            if ((current - actual > 200 || actual - current > 200)) {
                $('#' + frame).css('height', actual + 'px');
            }
        }, 2000);
    });
    $("#oxi-addons-templates-submit").on("click", function (e) {
        e.preventDefault();
        $(".oxi-addons-minicolor").each(function (index, value) {
            var datavalue = $(this).attr("oxilabvalue");
            if (typeof datavalue !== typeof undefined && datavalue !== false) {
                $(this).val(datavalue);
            }
        });
        $(".oxi-addons-gradient-color").each(function (index, value) {
            var datavalue = $(this).attr("oxilabvalue");
            if (typeof datavalue !== typeof undefined && datavalue !== false) {
                $(this).val(datavalue);
            }
        });
        var rawdata = JSON.stringify($("#oxi-addons-form-submit").serializeJSON({checkboxUncheckedValue: "0"}));
        var functionname = "elements_template_style";
        $(this).html('<span class="dashicons dashicons-admin-generic"></span>');
        OxiAccordionsRestApi(functionname, rawdata, styleid, childid, function (callback) {
            if (callback === "success") {
                $("#OXIAADDONSCHANGEDPOPUP .icon-box").html('<span class="dashicons dashicons-yes"></span>');
                $("#OXIAADDONSCHANGEDPOPUP .modal-body.text-center h4").html("Great!");
                $("#OXIAADDONSCHANGEDPOPUP .modal-body.text-center p").html("Layouts data has been saved successfully.");
                $("#OXIAADDONSCHANGEDPOPUP").modal("show");
                OxiAddonsModalConfirm("#oxi-addons-templates-submit", "Save");
                OxiAddonsPreviewDataLoader();
            }
        });
    });
    $("#oxi-template-modal-submit").on("click", function (e) {
        e.preventDefault();
        var inst, contents = new Object();
        for (inst in tinyMCE.editors) {
            if (tinyMCE.editors[inst].getContent)
                if ($("#wp-" + inst + "-wrap").hasClass('tmce-active')) {
                    $('#' + inst).val(tinyMCE.editors[inst].getContent());
                }
        }

        var rawdata = JSON.stringify($("#oxi-template-modal-form").serializeJSON({checkboxUncheckedValue: "0"}));
        var functionname = "elements_template_modal_data";
        var childid = $("#shortcodeitemid").val();
        $(this).html('<span class="dashicons dashicons-admin-generic"></span>');
        OxiAccordionsRestApi(functionname, rawdata, styleid, childid, function (callback) {
            $("#oxi-addons-list-data-modal").modal("hide");
            $("#OXIAADDONSCHANGEDPOPUP .icon-box").html('<span class="dashicons dashicons-yes"></span>');
            $("#OXIAADDONSCHANGEDPOPUP .modal-body.text-center h4").html("Great!");
            $("#OXIAADDONSCHANGEDPOPUP .modal-body.text-center p").html("Data has been saved successfully.");
            $("#OXIAADDONSCHANGEDPOPUP").modal("show");
            OxiAddonsModalConfirm("#oxi-template-modal-submit", "Submit");
            OxiAddonsPreviewDataLoader();
        });
    });
    $('.oxi-addons-minicolor').each(function () {
        $(this).minicolors({
            control: $(this).attr('data-control') || 'hue',
            defaultValue: $(this).attr('data-defaultValue') || '',
            format: $(this).attr('data-format') || 'hex',
            keywords: $(this).attr('data-keywords') || 'transparent' || 'initial' || 'inherit',
            inline: $(this).attr('data-inline') === 'true',
            letterCase: $(this).attr('data-letterCase') || 'lowercase',
            opacity: $(this).attr('data-opacity'),
            position: $(this).attr('data-position') || 'bottom left',
            swatches: $(this).attr('data-swatches') ? $(this).attr('data-swatches').split('|') : [],
            change: function (value, opacity) {
                if (!value)
                    return;
                if (opacity)
                    value += ', ' + opacity;
                if (typeof console === 'object') {
                    // console.log(value);
                }
            },
            theme: 'bootstrap'
        });
    });
    $(".shortcode-form-link-dimensions").click(function () {
        $(this).toggleClass("link-dimensions-unlink");
    });
    $(".shortcode-control-type-dimensions .shortcode-form-units-choices-label").click(function () {
        var id = "#" + $(this).attr('for');
        var input = $(this).parent().siblings('.shortcode-form-control-input-wrapper').children('.shortcode-form-control-dimensions').children('li').children('input');
        input.attr('min', $(id).attr('min'));
        input.attr('max', $(id).attr('max'));
        input.attr('step', $(id).attr('step'));
        // console.log($(id).attr('step'));

    });
    function ShortCodeMultipleSelector_Handler($value) {
        return $value.replace(/{{[0-9a-zA-Z.?:_-]+}}/g, function (match, contents, offset, input_string) {
            var m = match.replace(/{{/g, "").replace(/}}/g, "");
            var arr = m.split('.');
            if (m.indexOf("SIZE") != -1) {
                m = m.replace(/.SIZE/g, $("#" + arr[0] + '-size').val());
            }
            if (m.indexOf("UNIT") != -1) {
                m = m.replace(/.UNIT/g, $("#" + arr[0] + '-choices').val());
            }
            if (m.indexOf("VALUE") != -1) {
                m = m.replace(/.VALUE/g, $("#" + arr[0]).val());
            }
            m = m.replace(NEWRegExp(arr[0]), '');
            return m;
        });
    }
    $(document.body).on("keyup", ".shortcode-control-type-text input", function (e) {
        $input = $(this);
        if ($input.attr("retundata") !== '') {
            e.preventDefault();
            var $data = JSON.parse($input.attr("retundata"));
            $.each($data, function (el, obj) {
                if (el.indexOf('{{KEY}}') != -1) {
                    el = el.replace(NEWRegExp("{{KEY}}"), $input.attr('name').split('saarsa')[1]);
                }
                var cls = el.replace(NEWRegExp("{{WRAPPER}}"), WRAPPER);
                IFRAME.contents().find(cls).html($input.val());
            });
        }
    });
    $(document.body).on("keyup", ".shortcode-control-type-textarea textarea", function (e) {
        $input = $(this);
        if ($input.attr("retundata") !== '') {
            e.preventDefault();
            var $data = JSON.parse($input.attr("retundata"));
            $.each($data, function (el, obj) {
                if (el.indexOf('{{KEY}}') != -1) {
                    el = el.replace(NEWRegExp("{{KEY}}"), $input.attr('name').split('saarsa')[1]);
                }
                var cls = el.replace(NEWRegExp("{{WRAPPER}}"), WRAPPER);
                IFRAME.contents().find(cls).html($input.val());
            });
        }
    });
    $(".shortcode-control-type-number input").on("keyup", function () {
        $input = $(this);
        if ($input.attr("retundata") !== '') {
            var $data = JSON.parse($input.attr("retundata"));

            $.each($data, function (el, obj) {
                if (el.indexOf('{{KEY}}') != -1) {
                    el = el.replace(NEWRegExp("{{KEY}}"), $input.attr('name').split('saarsa')[1]);
                }
                var cls = el.replace(NEWRegExp("{{WRAPPER}}"), WRAPPER);
                var Cval = obj.replace(NEWRegExp("{{VALUE}}"), $input.val());
                if (Cval.indexOf("{{") != -1) {
                    Cval = ShortCodeMultipleSelector_Handler(Cval);
                }
                if ($input.attr('responsive') === 'tab') {
                    IFRAME.contents().find(IFRAMETABSWRAPPER).append('<style>@media only screen and (min-width : 769px) and (max-width : 993px){' + IFRAMEBODYCLASS + ' ' + cls + '{' + Cval + '}} < /style>');
                } else if ($input.attr('responsive') === 'mobile') {
                    IFRAME.contents().find(IFRAMETABSWRAPPER).append('<style>@media only screen and (max-width : 768px){' + IFRAMEBODYCLASS + ' ' + cls + '{' + Cval + '}} < /style>');
                } else {
                    IFRAME.contents().find(IFRAMETABSWRAPPER).append('<style>' + IFRAMEBODYCLASS + ' ' + cls + '{' + Cval + '} < /style>');
                }
            });
            if ($input.val() === '' && $input.parents('.modal-body').length === 0) {
                OxiAddonsPreviewDataLoader();
            }
        }
    });
    $(".shortcode-control-type-select select").on("change", function (e) {
        $input = $(this);
        if ($input.attr("retundata") !== '') {
            id = $(this).attr('id');
            var arr = [];
            $("#" + id + " option").each(function () {
                arr.push($(this).val());
            });
            var $data = JSON.parse($input.attr("retundata"));
            $.each($data, function (key, obj) {
                if (key.indexOf('{{KEY}}') != -1) {
                    key = key.replace(NEWRegExp("{{KEY}}"), $input.attr('name').split('saarsa')[1]);
                }
                $.each(obj, function (k, o) {
                    var cls = key.replace(NEWRegExp("{{WRAPPER}}"), WRAPPER);
                    if (o.type === 'CSS') {
                        var Cval = o.value.replace(NEWRegExp("{{VALUE}}"), $input.val());
                        if (Cval.indexOf("{{") != -1) {
                            Cval = ShortCodeMultipleSelector_Handler(Cval);
                        }
                        if ($input.attr('responsive') === 'tab') {
                            IFRAME.contents().find(IFRAMETABSWRAPPER).append('<style>@media only screen and (min-width : 769px) and (max-width : 993px){' + IFRAMEBODYCLASS + ' ' + cls + '{' + Cval + '}} < /style>');
                        } else if ($input.attr('responsive') === 'mobile') {
                            IFRAME.contents().find(IFRAMETABSWRAPPER).append('<style>@media only screen and (max-width : 768px){' + IFRAMEBODYCLASS + ' ' + cls + '{' + Cval + '}} < /style>');
                        } else {
                            IFRAME.contents().find(IFRAMETABSWRAPPER).append('<style>' + IFRAMEBODYCLASS + ' ' + cls + '{' + Cval + '} < /style>');
                        }
                    } else {
                        $.each(arr, function (i, v) {
                            IFRAME.contents().find(cls).removeClass(v);
                        });
                        IFRAME.contents().find(cls).addClass($input.val());
                    }
                });
            });
            if ($input.val() === '' && $input.parents('.modal-body').length === 0) {
                OxiAddonsPreviewDataLoader();
            }
        }
    });
    $(document.body).on("click", ".shortcode-control-type-choose input", function (e) {

        name = $(this).attr('name');
        $value = $(this).val();
        if ($(this).parent('.shortcode-form-choices').attr("retundata") !== '') {
            var arr = [];
            $("input[name=" + name + "]").each(function () {
                arr.push($(this).val());
            });
            $input = $(this).parent('.shortcode-form-choices');
            var $data = JSON.parse($input.attr("retundata"));
            $.each($data, function (key, obj) {
                if (key.indexOf('{{KEY}}') != -1) {
                    key = key.replace(NEWRegExp("{{KEY}}"), name.split('saarsa')[1]);
                }
                $.each(obj, function (k, o) {
                    var cls = key.replace(NEWRegExp("{{WRAPPER}}"), WRAPPER);
                    if (o.type === 'CSS') {
                        var Cval = o.value.replace(NEWRegExp("{{VALUE}}"), $value);
                        if (Cval.indexOf("{{") != -1) {
                            Cval = ShortCodeMultipleSelector_Handler(Cval);
                        }
                        if ($input.attr('responsive') === 'tab') {
                            IFRAME.contents().find(IFRAMETABSWRAPPER).append('<style>@media only screen and (min-width : 769px) and (max-width : 993px){' + IFRAMEBODYCLASS + ' ' + cls + '{' + Cval + '}} < /style>');
                        } else if ($input.attr('responsive') === 'mobile') {
                            IFRAME.contents().find(IFRAMETABSWRAPPER).append('<style>@media only screen and (max-width : 768px){' + IFRAMEBODYCLASS + ' ' + cls + '{' + Cval + '}} < /style>');
                        } else {
                            IFRAME.contents().find(IFRAMETABSWRAPPER).append('<style>' + IFRAMEBODYCLASS + ' ' + cls + '{' + Cval + '} < /style>');
                        }
                    } else {
                        $.each(arr, function (i, v) {
                            IFRAME.contents().find(cls).removeClass(v);
                        });
                        IFRAME.contents().find(cls).addClass($value);
                    }
                });
            });
        }
    });
    $(document.body).on("click", ".shortcode-control-type-switcher input", function (e) {

        name = $(this).attr('name');
        $value = $(this).val();
        $checked = false;
        if ($(this).is(":checked"))
        {
            $checked = true;
        }

        if ($(this).attr("retundata") !== '') {
            var arr = [];
            $("input[name=" + name + "]").each(function () {
                arr.push($(this).val());
            });
            var $data = JSON.parse($(this).attr("retundata"));
            $.each($data, function (key, obj) {
                if (key.indexOf('{{KEY}}') != -1) {
                    key = key.replace(NEWRegExp("{{KEY}}"), name.split('saarsa')[1]);
                }
                $.each(obj, function (k, o) {
                    var cls = key.replace(NEWRegExp("{{WRAPPER}}"), WRAPPER);
                    IFRAME.contents().find(cls).removeClass($value);
                    if ($checked) {
                        IFRAME.contents().find(cls).addClass($value);
                    }

                });
            });
        }
    });



    $(".shortcode-control-type-color input").on("keyup, change", function () {
        $input = $(this);
        $custom = $input.attr("custom");
        if ($input.attr("retundata") !== '') {
            if ($custom === '') {
                var $data = JSON.parse($input.attr("retundata"));
                $.each($data, function (el, obj) {
                    if (el.indexOf('{{KEY}}') != -1) {
                        el = el.replace(NEWRegExp("{{KEY}}"), $input.attr('name').split('saarsa')[1]);
                    }
                    var cls = el.replace(NEWRegExp("{{WRAPPER}}"), WRAPPER);
                    var Cval = obj.replace(NEWRegExp("{{VALUE}}"), $input.val());
                    if (Cval.indexOf("{{") != -1) {
                        Cval = ShortCodeMultipleSelector_Handler(Cval);
                    }
                    if ($input.attr('responsive') === 'tab') {
                        IFRAME.contents().find(IFRAMETABSWRAPPER).append('<style>@media only screen and (min-width : 769px) and (max-width : 993px){' + IFRAMEBODYCLASS + ' ' + cls + '{' + Cval + '}} < /style>');
                    } else if ($input.attr('responsive') === 'mobile') {
                        IFRAME.contents().find(IFRAMETABSWRAPPER).append('<style>@media only screen and (max-width : 768px){' + IFRAMEBODYCLASS + ' ' + cls + '{' + Cval + '}} < /style>');
                    } else {
                        IFRAME.contents().find(IFRAMETABSWRAPPER).append('<style>' + IFRAMEBODYCLASS + ' ' + cls + '{' + Cval + '} < /style>');
                    }

                });
                if ($input.val() === '' && $input.parents('.modal-body').length === 0) {
                    $input.siblings('.minicolors-swatch').children('.minicolors-swatch-color').css('background-color', 'transparent');
                    OxiAddonsPreviewDataLoader();
                }
            } else {
                var $data = JSON.parse($input.attr("retundata"));
                $custom = $custom.split("|||||");
                $id = $custom[0];
                $VALUE = '';
                if ($custom[1] === 'text-shadow') {
                    $color = $('#' + $id + "-color").val();
                    $blur = $('#' + $id + "-blur-size").val();
                    $horizontal = $('#' + $id + "-horizontal-size").val();
                    $vertical = $('#' + $id + "-vertical-size").val();
                    $true = (!$blur || !$horizontal || !$vertical) ? true : false;
                    if ($true === false) {
                        $VALUE = 'text-shadow: ' + $horizontal + 'px ' + $vertical + 'px ' + $blur + 'px ' + $color + ';';
                    }
                } else if ($custom[1] === 'box-shadow') {
                    $type = $('input[name="' + $id + '-type"]:checked').val();
                    $color = $('#' + $id + "-color").val();
                    $blur = $('#' + $id + "-blur-size").val();
                    $spread = $('#' + $id + "-spread-size").val();
                    $horizontal = $('#' + $id + "-horizontal-size").val();
                    $vertical = $('#' + $id + "-vertical-size").val();
                    $true = (!$blur || !$spread || !$horizontal || !$vertical) ? true : false;
                    if ($true === false) {
                        $VALUE = 'box-shadow: ' + $type + ' ' + $horizontal + 'px ' + $vertical + 'px ' + $blur + 'px ' + $spread + 'px ' + $color + ';';
                    }
                }
                $.each($data, function (el, obj) {
                    if (el.indexOf('{{KEY}}') != -1) {
                        el = el.replace(NEWRegExp("{{KEY}}"), $input.attr('name').split('saarsa')[1]);
                    }
                    var cls = el.replace(NEWRegExp("{{WRAPPER}}"), WRAPPER);
                    var Cval = obj.replace(NEWRegExp("{{VALUE}}"), $VALUE);
                    if ($input.attr('responsive') === 'tab') {
                        IFRAME.contents().find(IFRAMETABSWRAPPER).append('<style>@media only screen and (min-width : 769px) and (max-width : 993px){' + IFRAMEBODYCLASS + ' ' + cls + '{' + Cval + '}} < /style>');
                    } else if ($input.attr('responsive') === 'mobile') {
                        IFRAME.contents().find(IFRAMETABSWRAPPER).append('<style>@media only screen and (max-width : 768px){' + IFRAMEBODYCLASS + ' ' + cls + '{' + Cval + '}} < /style>');
                    } else {
                        IFRAME.contents().find(IFRAMETABSWRAPPER).append('<style>' + IFRAMEBODYCLASS + ' ' + cls + '{' + Cval + '} < /style>');
                    }
                });
            }
        }
    });
    $(".shortcode-control-type-font input").on("change", function () {
        $input = $(this);
        if ($(this).attr("retundata") !== '') {
            var font = $input.val().replace(/\+/g, ' ');
            IFRAME.contents().find(IFRAMETABSWRAPPER).append('<link rel=\'stylesheet\' id=\'' + font + '-css\'  href=\'https://fonts.googleapis.com/css?family=' + font + '\' media=\'all\' />');
            font = font.split(':');
            var $data = JSON.parse($input.attr("retundata"));
            $.each($data, function (el, obj) {
                if (el.indexOf('{{KEY}}') != -1) {
                    el = el.replace(NEWRegExp("{{KEY}}"), $input.attr('name').split('saarsa')[1]);
                }
                var cls = el.replace(NEWRegExp("{{WRAPPER}}"), WRAPPER);
                var Cval = obj.replace(NEWRegExp("{{VALUE}}"), font[0]);
                if ($input.attr('responsive') === 'tab') {
                    IFRAME.contents().find(IFRAMETABSWRAPPER).append('<style>@media only screen and (min-width : 769px) and (max-width : 993px){' + IFRAMEBODYCLASS + ' ' + cls + '{' + Cval + '}} < /style>');
                } else if ($input.attr('responsive') === 'mobile') {
                    IFRAME.contents().find(IFRAMETABSWRAPPER).append('<style>@media only screen and (max-width : 768px){' + IFRAMEBODYCLASS + ' ' + cls + '{' + Cval + '}} < /style>');
                } else {
                    IFRAME.contents().find(IFRAMETABSWRAPPER).append('<style>' + IFRAMEBODYCLASS + ' ' + cls + '{' + Cval + '} < /style>');
                }
            });
        }
    });

    function ShortCodeFormSliderINT(ID = '') {
        $this = $('.shortcode-form-slider');
        if (ID !== '') {
            $this = ID.find('.shortcode-form-slider');
        }
        $this.each(function (key, value) {
            var $This = $(this);
            if (!$This.parents('.shortcode-addons-form-repeater-store').length) {

                var $input = $This.siblings('.shortcode-form-slider-input').children('input');
                var step = parseFloat($(this).siblings('.shortcode-form-slider-input').children('input').attr('step'));
                var min = parseFloat($(this).siblings('.shortcode-form-slider-input').children('input').attr('min'));
                var max = parseFloat($(this).siblings('.shortcode-form-slider-input').children('input').attr('max'));
                if (step % 1 == 0) {
                    decimals = 0;
                } else if (step % 0.1 == 0) {
                    decimals = 1;
                } else if (step % 0.01 == 0) {
                    decimals = 2;
                } else {
                    decimals = 3;
                }
                noUiSlider.create(value, {
                    animate: true,
                    start: $input.val(),
                    step: step,
                    connect: 'lower',
                    range: {
                        'min': min,
                        'max': max
                    },
                    format: wNumb({
                        decimals: decimals
                    })
                });
                value.noUiSlider.on('slide', function (v) {
                    $input.val(v);
                    $custom = $input.attr("custom");
                    if ($input.attr("retundata") !== '') {
                        if ($custom === '') {
                            var $data = JSON.parse($input.attr("retundata"));
                            $.each($data, function (el, obj) {
                                if (el.indexOf('{{KEY}}') != -1) {
                                    el = el.replace(NEWRegExp("{{KEY}}"), $input.attr('name').split('saarsa')[1]);
                                }
                                var cls = el.replace(NEWRegExp("{{WRAPPER}}"), WRAPPER);
                                var Cval = obj.replace(NEWRegExp("{{SIZE}}"), v);
                                Cval = Cval.replace(NEWRegExp("{{UNIT}}"), $input.attr('unit'));
                                if (Cval.indexOf("{{") != -1) {
                                    Cval = ShortCodeMultipleSelector_Handler(Cval);
                                }
                                if ($input.attr('responsive') === 'tab') {
                                    IFRAME.contents().find(IFRAMETABSWRAPPER).append('<style>@media only screen and (min-width : 769px) and (max-width : 993px){' + IFRAMEBODYCLASS + ' ' + cls + '{' + Cval + '}} < /style>');
                                } else if ($input.attr('responsive') === 'mobile') {
                                    IFRAME.contents().find(IFRAMETABSWRAPPER).append('<style>@media only screen and (max-width : 768px){' + IFRAMEBODYCLASS + ' ' + cls + '{' + Cval + '}} < /style>');
                                } else {
                                    IFRAME.contents().find(IFRAMETABSWRAPPER).append('<style>' + IFRAMEBODYCLASS + ' ' + cls + '{' + Cval + '} < /style>');
                                }
                            });
                        } else {
                            var $data = JSON.parse($input.attr("retundata"));
                            $custom = $custom.split("|||||");
                            $id = $custom[0];
                            $VALUE = '';
                            if ($custom[1] === 'text-shadow') {
                                $color = $('#' + $id + "-color").val();
                                $blur = $('#' + $id + "-blur-size").val();
                                $horizontal = $('#' + $id + "-horizontal-size").val();
                                $vertical = $('#' + $id + "-vertical-size").val();
                                $true = (($blur === '0' && $horizontal === '0' && $vertical === '0') || !$blur || !$horizontal || !$vertical) ? true : false;
                                if ($true === false) {
                                    $VALUE = 'text-shadow: ' + $horizontal + 'px ' + $vertical + 'px ' + $blur + 'px ' + $color + ';';
                                } else if ($input.parents('.modal-body').length === 0) {
                                    OxiAddonsPreviewDataLoader();
                                }
                            } else if ($custom[1] === 'box-shadow') {
                                $type = $('input[name="' + $id + '-type"]:checked').val();
                                $color = $('#' + $id + "-color").val();
                                $blur = $('#' + $id + "-blur-size").val();
                                $spread = $('#' + $id + "-spread-size").val();
                                $horizontal = $('#' + $id + "-horizontal-size").val();
                                $vertical = $('#' + $id + "-vertical-size").val();
                                $true = (($blur === '0' && $spread === '0' && $horizontal === '0' && $vertical === '0') || !$blur || !$spread || !$horizontal || !$vertical) ? true : false;
                                if ($true === false) {
                                    $VALUE = 'box-shadow: ' + $type + ' ' + $horizontal + 'px ' + $vertical + 'px ' + $blur + 'px ' + $spread + 'px ' + $color + ';';
                                } else if ($input.parents('.modal-body').length === 0) {
                                    OxiAddonsPreviewDataLoader();
                                }
                            }
                            $.each($data, function (el, obj) {
                                if (el.indexOf('{{KEY}}') != -1) {
                                    el = el.replace(NEWRegExp("{{KEY}}"), $input.attr('name').split('saarsa')[1]);
                                }
                                var cls = el.replace(NEWRegExp("{{WRAPPER}}"), WRAPPER);
                                var Cval = obj.replace(NEWRegExp("{{VALUE}}"), $VALUE);
                                if (Cval.indexOf("{{") != -1) {
                                    Cval = ShortCodeMultipleSelector_Handler(Cval);
                                }
                                if ($input.attr('responsive') === 'tab') {
                                    IFRAME.contents().find(IFRAMETABSWRAPPER).append('<style>@media only screen and (min-width : 769px) and (max-width : 993px){' + IFRAMEBODYCLASS + ' ' + cls + '{' + Cval + '}} < /style>');
                                } else if ($input.attr('responsive') === 'mobile') {
                                    IFRAME.contents().find(IFRAMETABSWRAPPER).append('<style>@media only screen and (max-width : 768px){' + IFRAMEBODYCLASS + ' ' + cls + '{' + Cval + '}} < /style>');
                                } else {
                                    IFRAME.contents().find(IFRAMETABSWRAPPER).append('<style>' + IFRAMEBODYCLASS + ' ' + cls + '{' + Cval + '} < /style>');
                                }
                            });
                        }
                    }
                });
            }
        });
    }
    ShortCodeFormSliderINT();
    $(".shortcode-form-slider-input input").on("keyup", function () {
        $input = $(this);
        $custom = $input.attr("custom");
        var html5Slider = $(this).parent().siblings('.shortcode-form-slider');
        html5Slider = html5Slider[0];
        var val = $(this).val();
        html5Slider.noUiSlider.set(val);
        if ($input.attr("retundata") !== '') {

            if ($custom === '') {
                var $data = JSON.parse($input.attr("retundata"));
                $.each($data, function (el, obj) {
                    if (el.indexOf('{{KEY}}') != -1) {
                        el = el.replace(NEWRegExp("{{KEY}}"), $input.attr('name').split('saarsa')[1]);
                    }
                    var cls = el.replace(NEWRegExp("{{WRAPPER}}"), WRAPPER);
                    var Cval = obj.replace(NEWRegExp("{{SIZE}}"), $input.val());
                    Cval = Cval.replace(NEWRegExp("{{UNIT}}"), $input.attr('unit'));
                    if (Cval.indexOf("{{") != -1) {
                        Cval = ShortCodeMultipleSelector_Handler(Cval);
                    }
                    if ($input.attr('responsive') === 'tab') {
                        IFRAME.contents().find(IFRAMETABSWRAPPER).append('<style>@media only screen and (min-width : 769px) and (max-width : 993px){' + IFRAMEBODYCLASS + ' ' + cls + '{' + Cval + '}} < /style>');
                    } else if ($input.attr('responsive') === 'mobile') {
                        IFRAME.contents().find(IFRAMETABSWRAPPER).append('<style>@media only screen and (max-width : 768px){' + IFRAMEBODYCLASS + ' ' + cls + '{' + Cval + '}} < /style>');
                    } else {
                        IFRAME.contents().find(IFRAMETABSWRAPPER).append('<style>' + IFRAMEBODYCLASS + ' ' + cls + '{' + Cval + '} < /style>');
                    }
                });
            } else {
                var $data = JSON.parse($input.attr("retundata"));
                $custom = $custom.split("|||||");
                $id = $custom[0];
                $VALUE = '';
                if ($custom[1] === 'text-shadow') {
                    $color = $('#' + $id + "-color").val();
                    $blur = $('#' + $id + "-blur-size").val();
                    $horizontal = $('#' + $id + "-horizontal-size").val();
                    $vertical = $('#' + $id + "-vertical-size").val();
                    $true = (($blur === '0' && $horizontal === '0' && $vertical === '0') || !$blur || !$horizontal || !$vertical) ? true : false;
                    if ($true === false) {
                        $VALUE = 'text-shadow: ' + $horizontal + 'px ' + $vertical + 'px ' + $blur + 'px ' + $color + ';';
                    } else if ($input.parents('.modal-body').length === 0) {
                        OxiAddonsPreviewDataLoader();
                    }
                } else if ($custom[1] === 'box-shadow') {
                    $type = $('input[name="' + $id + '-type"]:checked').val();
                    $color = $('#' + $id + "-color").val();
                    $blur = $('#' + $id + "-blur-size").val();
                    $spread = $('#' + $id + "-spread-size").val();
                    $horizontal = $('#' + $id + "-horizontal-size").val();
                    $vertical = $('#' + $id + "-vertical-size").val();
                    $true = (($blur === '0' && $spread === '0' && $horizontal === '0' && $vertical === '0') || !$blur || !$spread || !$horizontal || !$vertical) ? true : false;
                    if ($true === false) {
                        $VALUE = 'box-shadow: ' + $type + ' ' + $horizontal + 'px ' + $vertical + 'px ' + $blur + 'px ' + $spread + 'px ' + $color + ';';
                    } else if ($input.parents('.modal-body').length === 0) {
                        OxiAddonsPreviewDataLoader();
                    }
                }
                $.each($data, function (el, obj) {
                    if (el.indexOf('{{KEY}}') != -1) {
                        el = el.replace(NEWRegExp("{{KEY}}"), $input.attr('name').split('saarsa')[1]);
                    }
                    var cls = el.replace(NEWRegExp("{{WRAPPER}}"), WRAPPER);
                    var Cval = obj.replace(NEWRegExp("{{VALUE}}"), $VALUE);
                    if (Cval.indexOf("{{") != -1) {
                        Cval = ShortCodeMultipleSelector_Handler(Cval);
                    }
                    if ($input.attr('responsive') === 'tab') {
                        IFRAME.contents().find(IFRAMETABSWRAPPER).append('<style>@media only screen and (min-width : 769px) and (max-width : 993px){' + IFRAMEBODYCLASS + ' ' + cls + '{' + Cval + '}} < /style>');
                    } else if ($input.attr('responsive') === 'mobile') {
                        IFRAME.contents().find(IFRAMETABSWRAPPER).append('<style>@media only screen and (max-width : 768px){' + IFRAMEBODYCLASS + ' ' + cls + '{' + Cval + '}} < /style>');
                    } else {
                        IFRAME.contents().find(IFRAMETABSWRAPPER).append('<style>' + IFRAMEBODYCLASS + ' ' + cls + '{' + Cval + '} < /style>');
                    }
                });
            }
            if ($input.val() === '' && $input.parents('.modal-body').length === 0) {
                OxiAddonsPreviewDataLoader();
            }
        }
    });
    $(".oxi-addons-gradient-color").each(function (i, v) {
        $(this).coloringPick({
            "show_input": true,
            "theme": "dark",
            'destroy': false,
            change: function (val) {
                $data = [];
                var $This = $(this).children('input');
                // console.log($(this).children('input'));
                var _VALUE = $This.val();
                $id = $This.attr('background');
                $imagecheck = $("#" + $id + "-img").is(":checked");
                $imagesource = $('input[name="' + $id + '-select"]:checked').val();
                $Image = ($imagecheck === true ? ($imagesource === 'media-library' ? $("#" + $id + "-image").val() : $("#" + $id + "-url").val()) : '');
                var wordcount = val.split(/\b[\s,\.-:;]*/).length;
                var limitWord = 23;
                if ($Image === '') {
                    if (wordcount < limitWord) {
                        $BACKGROUND = 'background: ' + _VALUE + ';';
                    } else {
                        $BACKGROUND = ' background:-ms-' + _VALUE + '; background:-webkit-' + _VALUE + '; background:-moz-' + _VALUE + '; background:-o-' + _VALUE + '; background:' + _VALUE + ';';
                    }
                } else {
                    if (wordcount < limitWord) {
                        $BACKGROUND = 'background:linear-gradient(0deg, ' + _VALUE + ' 0%, ' + _VALUE + ' 100%), url(\'' + $Image + '\') ' + $("#" + $id + "-repeat").val() + ' ' + $("#" + $id + "-position").val() + ';';
                    } else {
                        $BACKGROUND = 'background:' + _VALUE + ', url(\'' + $Image + '\' ) ' + $("#" + $id + "-repeat").val() + ' ' + $("#" + $id + "-position-lap").val() + ';';
                    }
                }

                var $data = ($This.attr("retundata") !== '' ? JSON.parse($This.attr("retundata")) : []);
                $.each($data, function (el, obj) {
                    if (el.indexOf('{{KEY}}') != -1) {
                        el = el.replace(NEWRegExp("{{KEY}}"), $This.attr('name').split('saarsa')[1]);
                    }
                    var cls = el.replace(NEWRegExp("{{WRAPPER}}"), WRAPPER);
                    Cval = $BACKGROUND;
                    if ($This.attr('responsive') === 'tab') {
                        IFRAME.contents().find(IFRAMETABSWRAPPER).append('<style>@media only screen and (min-width : 769px) and (max-width : 993px){' + IFRAMEBODYCLASS + ' ' + cls + '{' + Cval + '}} < /style>');
                    } else if ($This.attr('responsive') === 'mobile') {
                        IFRAME.contents().find(IFRAMETABSWRAPPER).append('<style>@media only screen and (max-width : 768px){' + IFRAMEBODYCLASS + ' ' + cls + '{' + Cval + '}} < /style>');
                    } else {
                        IFRAME.contents().find(IFRAMETABSWRAPPER).append('<style>' + IFRAMEBODYCLASS + ' ' + cls + '{' + Cval + '} < /style>');
                    }
                });
                if (_VALUE === '' && $This.parents('.modal-body').length === 0) {
                    OxiAddonsPreviewDataLoader();
                }
            },
        });
    });
    $(".shortcode-control-type-slider .shortcode-form-units-choices-label").click(function () {
        var id = "#" + $(this).attr('for');
        var input = $(this).parent().siblings('.shortcode-form-control-input-wrapper').children('.shortcode-form-slider-input').children('input');
        input.attr('min', $(id).attr('min'));
        input.attr('max', $(id).attr('max'));
        input.attr('step', $(id).attr('step'));
        input.attr('unit', $(id).val());
        var step = parseFloat($(id).attr('step'));
        var min = parseFloat($(id).attr('min'));
        var max = parseFloat($(id).attr('max'));
        var start = input.attr('default-value');
        if (step % 1 == 0) {
            decimals = 0;
        } else if (step % 0.1 == 0) {
            decimals = 1;
        } else if (step % 0.01 == 0) {
            decimals = 2;
        } else {
            decimals = 3;
        }

        var html5Slider = $(this).parent().siblings('.shortcode-form-control-input-wrapper').children('.shortcode-form-slider');
        html5Slider = html5Slider[0];
        html5Slider.noUiSlider.updateOptions({
            start: start,
            step: step,
            range: {
                'min': min,
                'max': max
            },
            format: wNumb({
                decimals: decimals
            })
        });
    });
    $(".shortcode-control-type-dimensions input").on("input", function () {
        $this = $(this);
        if ($this.parent().siblings('.shortcode-form-control-dimension').children('.shortcode-form-link-dimensions').hasClass('link-dimensions-unlink')) {
            if ($this.val() === '') {
                $this.val(0);
            }
            $siblings = $this.parent().siblings('.shortcode-form-control-dimension').children('input');
            $.each($siblings, function (e, o) {
                if ($(this).val() === '') {
                    $(this).val(0);
                }
            });
        } else {
            var value = $this.val();
            $this.parent().siblings('.shortcode-form-control-dimension').children('input').val(value);
        }
        $input = $this;
        $InputID = $input.attr('input-id');
        UNIT = $InputID + '-choices';
        TOP = $InputID + '-top';
        RIGHT = $InputID + '-right';
        BOTTOM = $InputID + '-bottom';
        LEFT = $InputID + '-left';
        if ($input.attr("retundata") !== '' && $input.attr('type') !== 'radio') {
            //  console.log($this.val());
            var $data = JSON.parse($input.attr("retundata"));
            $.each($data, function (el, obj) {
                if (el.indexOf('{{KEY}}') != -1) {
                    el = el.replace(NEWRegExp("{{KEY}}"), $input.attr('name').split('saarsa')[1]);
                }
                var cls = el.replace(NEWRegExp("{{WRAPPER}}"), WRAPPER);
                var type = $("input[name=\"" + UNIT + "\"]").attr('type');
                if (type === 'hidden') {
                    Cval = obj.replace(NEWRegExp("{{UNIT}}"), $('#' + UNIT).val());
                } else {
                    var Cval = obj.replace(NEWRegExp("{{UNIT}}"), $("input[name=\"" + UNIT + "\"]:checked").val());
                }
                Cval = Cval.replace(NEWRegExp("{{TOP}}"), $('#' + TOP).val());
                Cval = Cval.replace(NEWRegExp("{{RIGHT}}"), $('#' + RIGHT).val());
                Cval = Cval.replace(NEWRegExp("{{BOTTOM}}"), $('#' + BOTTOM).val());
                Cval = Cval.replace(NEWRegExp("{{LEFT}}"), $('#' + LEFT).val());
                if ($input.attr('responsive') === 'tab') {
                    IFRAME.contents().find(IFRAMETABSWRAPPER).append('<style>@media only screen and (min-width : 769px) and (max-width : 993px){' + IFRAMEBODYCLASS + ' ' + cls + '{' + Cval + '}} < /style>');
                } else if ($input.attr('responsive') === 'mobile') {
                    IFRAME.contents().find(IFRAMETABSWRAPPER).append('<style>@media only screen and (max-width : 768px){' + IFRAMEBODYCLASS + ' ' + cls + '{' + Cval + '}} < /style>');
                } else {
                    IFRAME.contents().find(IFRAMETABSWRAPPER).append('<style>' + IFRAMEBODYCLASS + ' ' + cls + '{' + Cval + '} < /style>');
                }
            });
            if ($input.val() === '' && $input.parents('.modal-body').length === 0) {
                OxiAddonsPreviewDataLoader();
            }
        }

    });
    $('.oxi-admin-icon-selector').iconpicker();
    $(document.body).on("keyup, change", ".shortcode-control-type-icon input", function (e) {

        name = $(this).attr('name');
        $value = $(this).val();

        if ($(this).attr("retundata") !== '') {
            console.log($value);
            var $data = JSON.parse($(this).attr("retundata"));
            $.each($data, function (key, obj) {

                if (key.indexOf('{{KEY}}') != -1) {
                    key = key.replace(NEWRegExp("{{KEY}}"), name.split('saarsa')[1]);
                }


                var cls = key.replace(NEWRegExp("{{WRAPPER}}"), WRAPPER);
                IFRAME.contents().find(cls).attr('class', 'oxi-icons');
                IFRAME.contents().find(cls).addClass($value);


            });
        }
    });

    $('.shortcode-addons-form-conditionize').conditionize();
    $(document.body).on("change", ".shortcode-addons-control-loader  input", function () {
        if ($(this).parents('.modal-body').length === 0) {
            OxiAddonsPreviewDataLoader();
        }

    });
    $(document.body).on("change", ".shortcode-addons-control-loader  select", function () {
        if ($(this).parents('.modal-body').length === 0) {
            OxiAddonsPreviewDataLoader();
        }
    });
    $(document.body).on("keyup", ".shortcode-addons-control-loader input", function () {
        if ($(this).parents('.modal-body').length === 0) {
            OxiAddonsPreviewDataLoader();
        }
    });

    $("#oxi-addons-2-0-color").on("change", function (e) {
        $input = $(this).val();
        IFRAME.contents().find(IFRAMEBODYCLASS).css('background', $input);
        $("#oxilab-preview-color").val($input);
    });
    $(document.body).on("click", ".media-modal-close", function () {
        if (($("#oxi-addons-list-data-modal").data('bs.modal') || {})._isShown) {
            setTimeout(function () {
                $("#oxi-addons-list-data-modal").css({"overflow-x": "hidden", "overflow-y": "auto"});
                $("body").css({"overflow": "hidden"});
            }, 1000);
        }
    });
    $(document.body).on("click", "#insert-media-button", function () {
        if (($("#oxi-addons-list-data-modal").data('bs.modal') || {})._isShown) {
            setTimeout(function () {
                $("#oxi-addons-list-data-modal").css({"overflow-x": "hidden", "overflow-y": "auto"});
                $("body").css({"overflow": "hidden"});
            }, 1000);
        }
    });


})(jQuery);