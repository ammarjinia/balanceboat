!(function (window, document, $) {
  "use strict";
  $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
})(window, document, jQuery);
$(function () {
  "use strict";
  $(document).on("blur", "#name", function () {
    var $slug = "";
    var trimmed = $.trim($(this).val());
    $slug = trimmed
      .replace(/[^a-z0-9-]/gi, "-")
      .replace(/-+/g, "-")
      .replace(/^-|-$/g, "");
    $("#slug").val($slug.toLowerCase());
  });

  if ($(".textarea_editor").length > 0) {
    tinymce.init({
      selector: "textarea.textarea_editor",
      theme: "modern",
      height: 300,
      menubar: 'file edit insert view format table',
      plugins: [
        "advlist autolink link image lists charmap print preview hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
        "save table contextmenu directionality emoticons template paste textcolor",
      ],
      toolbar:
        "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
    });
  }

  $(document).on("click", "#video_delete", function (e) {
    e.preventDefault();
    $(this).button("loading");
    $(this).attr("disabled", true);
    $.post(
      $(this).attr("href"),
      { id: $(this).attr("data-id") },
      function (response) {
        if (response) {
          $.toast({
            heading: "Success",
            text: "Video has been deleted.",
            position: "top-right",
            stack: false,
            icon: "success",
          });
          $("#exp_vid_container").remove();
        } else {
          $.toast({
            heading: "Failed",
            text: "Oops... Something went wrong",
            position: "top-right",
            stack: false,
            icon: "error",
          });
        }
        $(this).attr("disabled", false);
        $(this).button("reset");
      }
    );
    return false;
  });

  $(document).on("click", "#img_delete", function (e) {
    e.preventDefault();
    $(this).button("loading");
    $(this).attr("disabled", true);
    $.post(
      $(this).attr("href"),
      { id: $(this).attr("data-id") },
      function (response) {
        if (response) {
          $.toast({
            heading: "Success",
            text: "Image has been deleted.",
            position: "top-right",
            stack: false,
            icon: "success",
          });
          $("#exp_img_container").remove();
        } else {
          $.toast({
            heading: "Failed",
            text: "Oops... Something went wrong",
            position: "top-right",
            stack: false,
            icon: "error",
          });
        }
        $(this).attr("disabled", false);
        $(this).button("reset");
      }
    );
    return false;
  });

  $(document).on("click", "#img_thumbnail_delete", function (e) {
    e.preventDefault();
    $(this).button("loading");
    $(this).attr("disabled", true);
    $.post(
      $(this).attr("href"),
      { id: $(this).attr("data-id") },
      function (response) {
        if (response) {
          $.toast({
            heading: "Success",
            text: "Thumbnail has been deleted.",
            position: "top-right",
            stack: false,
            icon: "success",
          });
          $("#exp_thumb_img_container").remove();
        } else {
          $.toast({
            heading: "Failed",
            text: "Oops... Something went wrong",
            position: "top-right",
            stack: false,
            icon: "error",
          });
        }
        $(this).attr("disabled", false);
        $(this).button("reset");
      }
    );
    return false;
  });

  $(document).on("click", "#gallery_img_delete", function (e) {
    e.preventDefault();
    $(this).button("loading");
    $(this).attr("disabled", true);
    var imgId = $(this).attr("data-id");
    $.post(
      $(this).attr("href"),
      { id: $(this).attr("data-id") },
      function (response) {
        if (response) {
          $.toast({
            heading: "Success",
            text: "Image has been deleted.",
            position: "top-right",
            stack: false,
            icon: "success",
          });
          $("#img-" + imgId).remove();
        } else {
          $.toast({
            heading: "Failed",
            text: "Oops... Something went wrong",
            position: "top-right",
            stack: false,
            icon: "error",
          });
        }
        $(this).attr("disabled", false);
        $(this).button("reset");
      }
    );
    return false;
  });

  $(document).on("click", "#img_food_delete", function (e) {
    e.preventDefault();
    $(this).button("loading");
    $(this).attr("disabled", true);
    $.post(
      $(this).attr("href"),
      { id: $(this).attr("data-id") },
      function (response) {
        if (response) {
          $.toast({
            heading: "Success",
            text: "Image has been deleted.",
            position: "top-right",
            stack: false,
            icon: "success",
          });
          $("#exp_food_img_container").remove();
        } else {
          $.toast({
            heading: "Failed",
            text: "Oops... Something went wrong",
            position: "top-right",
            stack: false,
            icon: "error",
          });
        }
        $(this).attr("disabled", false);
        $(this).button("reset");
      }
    );
    return false;
  });

  $(document).on("click", ".food_gallery_img_delete", function (e) {
    e.preventDefault();
    $(this).button("loading");
    $(this).attr("disabled", true);
    var imgId = $(this).attr("data-id");
    $.post(
      $(this).attr("href"),
      { id: $(this).attr("data-id") },
      function (response) {
        if (response) {
          $.toast({
            heading: "Success",
            text: "Image has been deleted.",
            position: "top-right",
            stack: false,
            icon: "success",
          });
          $("#img-food-" + imgId).remove();
        } else {
          $.toast({
            heading: "Failed",
            text: "Oops... Something went wrong",
            position: "top-right",
            stack: false,
            icon: "error",
          });
        }
        $(this).attr("disabled", false);
        $(this).button("reset");
      }
    );
    return false;
  });

  // Datetime Picker
  $(".datetimepicker").datetimepicker();

  // Daterange Picker
  $(".clsdaterangepicker").daterangepicker({
    autoUpdateInput: false,
  });

  $(".clsdaterangepicker").on("apply.daterangepicker", function (ev, picker) {
    $(this).val(
      picker.startDate.format("MM/DD/YYYY") +
        " - " +
        picker.endDate.format("MM/DD/YYYY")
    );
  });

  $(".clsdaterangepicker").on("cancel.daterangepicker", function (ev, picker) {
    $(this).val("");
  });

  // Single Date Picker
  $("#offer_start_date").daterangepicker(
    {
      singleDatePicker: true,
      autoUpdateInput: false,
      locale: {
        format: "MM/DD/YYYY",
      },
    },
    function (start, end, label) {
      $("#offer_start_date").val(start.format("MM/DD/YYYY"));
    }
  );

  $("#offer_end_date").daterangepicker(
    {
      singleDatePicker: true,
      autoUpdateInput: false,
      locale: {
        format: "MM/DD/YYYY",
      },
    },
    function (start, end, label) {
      $("#offer_end_date").val(start.format("MM/DD/YYYY"));
    }
  );

  $("#center_id").on("change", function () {
    $(".acm-opt input[type=text]").val("");
    if ($("#center_id").find(":selected").data("have-accomodation") == "Yes") {
      $(".acm-opt").show();
      $.post(
        ADMIN_URL + "/centers/get_center_accomodation",
        { center_id: $(this).val() },
        function (response) {
          var data = JSON.parse(response);
          var opt = "";
          $.each(data.accomodations, function (k, v) {
            opt += "<option value='" + k + "'>" + v + "</option>";
          });
          $("select[id^=accomodation_title]").find("option:gt(0)").remove();
          $("select[id^=accomodation_title]").append(opt);

          tinymce.get("cancellation_policy").setContent("");
          $("input[name^=cancellation_policy_condition][value=1]")
            .prop("checked", true)
            .trigger("change");
          $("#cancellation_policy_days").val("");
          $("input[name^=deposit_policy][value=1]")
            .prop("checked", true)
            .trigger("change");
          $("#deposit_amount").val("");
          $("#commission").val("");
          $("#tax").val("");
          if (typeof data.commissions != "undefined") {
            tinymce
              .get("cancellation_policy")
              .setContent(data.commissions.cancellation_policy);
            $(
              "input[name^=cancellation_policy_condition][value=" +
                data.commissions.cancellation_policy_condition +
                "]"
            )
              .prop("checked", true)
              .trigger("change");
            $("#cancellation_policy_days").val(
              data.commissions.cancellation_policy_days
            );
            $(
              "input[name^=deposit_policy][value=" +
                data.commissions.deposit_policy +
                "]"
            )
              .prop("checked", true)
              .trigger("change");
            $("#deposit_amount").val(data.commissions.deposit_amount);
            $("#commission").val(data.commissions.commission);
            $("#tax").val(data.commissions.tax);
          }
        }
      );

      $.post(
        ADMIN_URL + "/centers/get_center_teachers",
        { center_id: $(this).val() },
        function (response) {
          var data = JSON.parse(response);
          $('select[name^="teacher_id"]').val(data);
          $('select[name^="teacher_id"]').trigger("change");
        }
      );
    } else {
      $(".acm-opt").hide();
    }
    return false;
  });

  //$("#center_id").trigger("change");

  $("#recurring_type").on("change", function () {
    $(".recurring").hide();
    if ($(this).val()) {
      $(
        "." + $("#recurring_type").val() + "-recurring, .recurring_end_date"
      ).show();
    }
  });

  $("#recurring_type").trigger("change");

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

  $("input[name^=rest_of_payment]").on("change", function () {
    $("#dv_rest_of_payment_days").hide();
    $("#rest_of_payment_days").val("");
    if ($(this).val() == 3) {
      $("#dv_rest_of_payment_days").show();
    }
  });

  $("input[name^='accomodation_promotional_price']").on(
    "change keyup",
    function () {
      var rw = $(this).parents(".row").attr("id");
      var promo_price = $(this).val();
      var accm_price = $(".row#" + rw)
        .find("input[name^='accomodation_price']")
        .val();
      if (accm_price && promo_price) {
        var promo_disc = (accm_price - promo_price).toFixed(2);
        $(".row#" + rw)
          .find("input[name^='accomodation_promotional_discount']")
          .val(promo_disc);
      } else {
        $(".row#" + rw)
          .find("input[name^='accomodation_promotional_discount']")
          .val("");
      }
    }
  );

  /*$("#frmCenter input, #frmCenter textarea, #frmCenter select").on(
    "change",
    function () {
      tinymce.triggerSave(true, true);
      $.ajax({
        url: ADMIN_URL + "/experiences/store",
        method: "POST",
        data: $("#frmCenter").serialize(),
        success: function (data) {
          data = JSON.parse(data);
          if (data.id) {
            $("#id").val(data.id);
            window.history.replaceState(
              "",
              "",
              ADMIN_URL + "/experiences/edit/" + data.id
            );
          }
        },
      });
    }
  );*/
});

Dropzone.autoDiscover = false;
$(document).ready(function () {
  var myDropzone = new Dropzone("div#image_gallery", {
    url: $("#dropzoneurl").val(),
    addRemoveLinks: true,
    acceptedFiles: "image/*",
    headers: {
      "X-CSRF-Token": $('meta[name="csrf-token"]').attr("content"),
    },
    init: function () {
      this.on("success", function (file) {
        var data = JSON.parse(file.xhr.responseText);
        if (data.message) {
          $.toast({
            heading: "Failed",
            text: data.message,
            position: "top-right",
            stack: false,
            icon: "error",
          });
          myDropzone.removeFile(file);
        } else {
          var prevVal = $("#image_gallery_ids").val();
          if (prevVal !== "") prevVal += "|@|@|";
          $("#image_gallery_ids").val(prevVal + data.filename);
        }
      });
      this.on("error", function (file) {
        var data = JSON.parse(file.xhr.responseText);
        $.toast({
          heading: "Failed",
          text: "Oops... Something went wrong",
          position: "top-right",
          stack: false,
          icon: "error",
        });
      });
      this.on("removedfile", function (file) {
        var data = JSON.parse(file.xhr.responseText);
        var hdnImgGal = $("#image_gallery_ids").val();
        $("#image_gallery_ids").val(
          hdnImgGal.replace("|@|@|" + data.filename, "")
        );
        $("#image_gallery_ids").val(
          $("#image_gallery_ids").val().replace(data.filename, "")
        );
      });
    },
  });

  var FoodDropzone = new Dropzone("div#food_image_gallery", {
    url: $("#fooddropzoneurl").val(),
    addRemoveLinks: true,
    acceptedFiles: "image/*",
    headers: {
      "X-CSRF-Token": $('meta[name="csrf-token"]').attr("content"),
    },
    init: function () {
      this.on("success", function (file) {
        var data = JSON.parse(file.xhr.responseText);
        if (data.message) {
          $.toast({
            heading: "Failed",
            text: data.message,
            position: "top-right",
            stack: false,
            icon: "error",
          });
          myDropzone.removeFile(file);
        } else {
          var prevVal = $("#food_image_gallery_ids").val();
          if (prevVal !== "") prevVal += "|@|@|";
          $("#food_image_gallery_ids").val(prevVal + data.filename);
        }
      });
      this.on("error", function (file) {
        var data = JSON.parse(file.xhr.responseText);
        $.toast({
          heading: "Failed",
          text: "Oops... Something went wrong",
          position: "top-right",
          stack: false,
          icon: "error",
        });
      });
      this.on("removedfile", function (file) {
        var data = JSON.parse(file.xhr.responseText);
        var hdnImgGal = $("#food_image_gallery_ids").val();
        $("#food_image_gallery_ids").val(
          hdnImgGal.replace("|@|@|" + data.filename, "")
        );
        $("#food_image_gallery_ids").val(
          $("#food_image_gallery_ids").val().replace(data.filename, "")
        );
      });
    },
  });

  function acm_dropzone(key) {
    var acmDropzone = new Dropzone("div#accomodation_image_gallery" + key, {
      url: $("#accomodationdropzoneurl" + key).val(),
      addRemoveLinks: true,
      acceptedFiles: "image/*",
      headers: {
        "X-CSRF-Token": $('meta[name="csrf-token"]').attr("content"),
      },
      init: function () {
        this.on("success", function (file) {
          var data = JSON.parse(file.xhr.responseText);
          if (data.message) {
            $.toast({
              heading: "Failed",
              text: data.message,
              position: "top-right",
              stack: false,
              icon: "error",
            });
            acmDropzone.removeFile(file);
          } else {
            var prevVal = $("#accomodation_gallery_ids" + key).val();
            if (prevVal !== "") prevVal += "|@|@|";
            $("#accomodation_gallery_ids" + key).val(prevVal + data.filename);
          }
        });
        this.on("error", function (file) {
          var data = JSON.parse(file.xhr.responseText);
          $.toast({
            heading: "Failed",
            text: "Oops... Something went wrong",
            position: "top-right",
            stack: false,
            icon: "error",
          });
        });
        this.on("removedfile", function (file) {
          var data = JSON.parse(file.xhr.responseText);
          var hdnImgGal = $("#accomodation_gallery_ids" + key).val();
          $("#accomodation_gallery_ids" + key).val(
            hdnImgGal.replace("|@|@|" + data.filename, "")
          );
          $("#accomodation_gallery_ids" + key).val(
            $("#accomodation_gallery_ids" + key)
              .val()
              .replace(data.filename, "")
          );
        });
      },
    });
  }
  for (i = 1; i <= 5; i++) {
    acm_dropzone(i);
  }
});
