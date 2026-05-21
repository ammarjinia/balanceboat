!function (window, document, $) {
    "use strict";
    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
}(window, document, jQuery);
$(function () {
    "use strict";
    
    if ($(".textarea_editor").length > 0) {
        tinymce.init({
            selector: "textarea.textarea_editor",
            theme: "modern",
            height: 300,
            plugins: [
                "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                "save table contextmenu directionality emoticons template paste textcolor"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",
        });
    }

    $("input[name^=deposit_policy]").on("change", function () {
        $("#dv_deposit_amount").hide();
        $("#deposit_amount").val("");
        if ($(this).val() >= 2) {
            $("#dv_deposit_amount").show();
        }
    });

    $("input[name^=cancellation_policy_condition]").on("change", function () {
        $("#dv_cancellation_policy_days").hide();
        $("#cancellation_policy_days").val("");
        if ($(this).val() == 3) {
            $("#dv_cancellation_policy_days").show();
        }
    });
});