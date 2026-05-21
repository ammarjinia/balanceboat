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
    
    if ($(".textarea_editor").length > 0) {
        tinymce.init({
            selector: "textarea.textarea_editor"      
        });
    }
    
    $('#experience_id').select2({
        minimumInputLength: 2,
        ajax: {
            url: ADMIN_URL + "/experiences/getexperiences",
            dataType: 'json',
            data: function (params) {
                var query = {
                    search: params.term
                }

                // Query parameters will be ?search=[term]&type=public
                return query;
            },
            processResults: function (data) {
                // Tranforms the top-level key of the response object from 'items' to 'results'
                return {
                    results: data
                };
            }
        }
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
                $("#col_img_container").remove();
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