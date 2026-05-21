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
    paste_data_images: true,
    plugins: [
      "advlist autolink lists link image charmap print preview hr anchor pagebreak",
      "searchreplace wordcount visualblocks visualchars code fullscreen",
      "insertdatetime media nonbreaking save table contextmenu directionality",
      "emoticons template paste textcolor colorpicker textpattern"
    ],
    toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
    toolbar2: "print preview media | forecolor backcolor emoticons",
    image_advtab: true,
    file_picker_callback: function(callback, value, meta) {
      if (meta.filetype == 'image') {
        $('#upload').trigger('click');
        $('#upload').on('change', function() {
          var file = this.files[0];
          var reader = new FileReader();
          reader.onload = function(e) {
            callback(e.target.result, {
              alt: ''
            });
          };
          reader.readAsDataURL(file);
        });
      }
    },
    templates: [{
      title: 'Test template 1',
      content: 'Test 1'
    }, {
      title: 'Test template 2',
      content: 'Test 2'
    }]
  });
    }

    $(document).on("click", "#img_delete", function (e) {
        e.preventDefault();
        $(this).button("loading");
        $(this).attr("disabled", true);
        var field = $(this).attr("data-field");
        $.post($(this).attr("href"), {'id': $(this).attr("data-id"), 'field': $(this).attr("data-field")}, function (response) {
            if (response) {
                $.toast({
                    heading: 'Success',
                    text: 'Image has been deleted.',
                    position: 'top-right',
                    stack: false,
                    icon: 'success'
                });
                $("#"+field+"_container").remove();
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
                $("#img-" + imgId).remove();
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
