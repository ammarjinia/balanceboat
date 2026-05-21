!function (window, document, $) {
    "use strict";
    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
}(window, document, jQuery);
$(function () {
    "use strict";
    $(document).on("blur", "#name", function () {
        var $slug = '';
        var trimmed = $.trim($(this).val());
        $slug = trimmed.replace(/[^a-z0-9-]/gi, '-').
                replace(/-+/g, '-').
                replace(/^-|-$/g, '');
        $("#slug").val($slug.toLowerCase());
    });

    $(document).on("click", "#img_delete", function (e) {
        e.preventDefault();
        $(this).button("loading");
        $(this).attr("disabled", true);
        $.post($(this).attr("href"), {'id': $(this).attr("data-id")}, function (response) {
            if (response) {
                $.toast({
                    heading: 'Success',
                    text: 'Image has been deleted.',
                    position: 'top-right',
                    stack: false,
                    icon: 'success'
                });
                $("#cert_img_container").remove();
            } else {
                $.toast({
                    heading: 'Failed',
                    text: 'Oops... Something went wrong',
                    position: 'top-right',
                    stack: false,
                    icon: 'error'
                });
            }
            $(this).attr("disabled", false);
            $(this).button("reset");
        });
        return false;
    });
});
