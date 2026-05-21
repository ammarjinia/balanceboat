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

    // Date Picker
    $('#arrival_date').daterangepicker({
        "singleDatePicker": true,
        "autoUpdateInput": false,
        locale: {
            format: 'MM/DD/YYYY'
        }
    }, function (start, end, label) {
        $('#arrival_date').val(start.format('MM/DD/YYYY'));
    });
    
    // Datetime Picker
    $('.datetimepicker').datetimepicker();

    // Daterange Picker
    $('.clsdaterangepicker').daterangepicker({
        "autoUpdateInput": false
    });

    $('.clsdaterangepicker').on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
    });

    $('.clsdaterangepicker').on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
    });

    $("#center_id").on("change", function () {
        $(".acm-opt input[type=text]").val("");
        if ($("#center_id").find(":selected").data("have-accomodation") == "Yes") {
            $(".acm-opt").show();
            $.post(ADMIN_URL + "/centers/get_center_accomodation", {'center_id': $(this).val()}, function (response) {
                var data = JSON.parse(response);
                var opt = "";
                $.each(data, function (k, v) {
                    opt += "<option value='" + k + "'>" + v + "</option>";
                });
                $("select[id^=accomodation_title]").find("option:gt(0)").remove();
                $("select[id^=accomodation_title]").append(opt);
            });

            $.post(ADMIN_URL + "/centers/get_center_teachers", {'center_id': $(this).val()}, function (response) {
                var data = JSON.parse(response);
                $('select[name^="teacher_id"]').val(data);
                $('select[name^="teacher_id"]').trigger('change');
            });
        } else {
            $(".acm-opt").hide();
        }
        return false;
    });

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

});