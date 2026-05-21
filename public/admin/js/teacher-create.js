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
                $("#teach_img_container").remove();
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
    
    $(document).on("click", "#gallery_img_delete", function (e) {
        e.preventDefault();
        $(this).button("loading");
        $(this).attr("disabled", true);
        var imgId = $(this).attr("data-id");
        $.post($(this).attr("href"), {'id': $(this).attr("data-id")}, function (response) {
            if (response) {
                $.toast({
                    heading: 'Success',
                    text: 'Image has been deleted.',
                    position: 'top-right',
                    stack: false,
                    icon: 'success'
                });
                $("#img-"+imgId).remove();
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

Dropzone.autoDiscover = false;
$(document).ready(function () {
    var myDropzone = new Dropzone("div#image_gallery", {
        url: $("#dropzoneurl").val(),
        addRemoveLinks: true,
        acceptedFiles:'image/*',
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        },
        init: function () {
            this.on("success", function (file) {
                var data = JSON.parse(file.xhr.responseText);
                if (data.message) {
                    $.toast({
                        heading: 'Failed',
                        text: data.message,
                        position: 'top-right',
                        stack: false,
                        icon: 'error'
                    });
                    myDropzone.removeFile(file);
                } else {
                    var prevVal = $('#image_gallery_ids').val();
                    if (prevVal !== '')
                        prevVal += '|@|@|';
                    $('#image_gallery_ids').val(prevVal + data.filename);
                }
            });
            this.on("error", function (file) {
                var data = JSON.parse(file.xhr.responseText);
                $.toast({
                    heading: 'Failed',
                    text: 'Oops... Something went wrong',
                    position: 'top-right',
                    stack: false,
                    icon: 'error'
                });
            });
            this.on("removedfile", function (file) {
                var data = JSON.parse(file.xhr.responseText);
                var hdnImgGal = $('#image_gallery_ids').val();
                $('#image_gallery_ids').val(hdnImgGal.replace('|@|@|' + data.filename, ''));
                $('#image_gallery_ids').val($('#image_gallery_ids').val().replace(data.filename, ''));
            });
        }
    });
});
