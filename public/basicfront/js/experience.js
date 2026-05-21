$(document).ready(function () {
    $(document).on("change", "#sort_by", function () {
        $("#frmExperience").submit();
    });

    $(document).on("ifChanged", "#frmExperience input[name^=popular]", function () {
        $("#frmExperience").submit();
    });

    $(document).on("blur", "#frmExperience input[name^=search]", function () {
        $("#frmExperience").submit();
    });

    $(document).on("keydown", "#frmExperience input[name^=search]", function (e) {
        if (e.type === "keydown" && e.keyCode === 13) {
            $("#frmExperience").submit();
        }
    });

    $(document).on("ifChecked", "#frmExperience input[name^=scategory], #frmExperience input[name^=sdestination]", function () {
        $("#frmExperience").submit();
    });
    
    $(document).on("ifChanged", "#frmExperience input[name^=stags]", function () {
        $("#frmExperience").submit();
    });

    $(".slim-scroll").slimScroll({
        height: '220px'
    });


    var price_range = $("#price_range");
    price_range.ionRangeSlider({
        onFinish:function() {
            $("#frmExperience").submit();
        }
    });

    

    var page = 0;
    var ajaxLoading = false;
    var stop = 0;
    $(window).scroll(function () {
        if ($(window).scrollTop() >= ($(document).height() - ($('footer').height()+500))) {
            if (!ajaxLoading && stop == 0) {
                ajaxLoading = true;
                page++;
                loadMoreData(page);
            }
        }
    });

    function loadMoreData(page) {
        $.ajax({
            url: APP_URL + '/experiences?page=' + page,
            type: "get",
            data: $("#frmExperience").serialize(),
            beforeSend: function () {
                $('.ajax-load').show();
            }
        }).done(function (data) {
            if (data.html == "") {
                //$('.ajax-load').html("No more records found");
                stop = 1;
                return;
            }
            $('.ajax-load').hide();
            $("#experience_data").append(data.html);
            $('.lazy').Lazy();
            ajaxLoading = false;
        }).fail(function (jqXHR, ajaxOptions, thrownError) {
            alert('server not responding...');
        });
    }
    
    $(document).on("click", "a[id^=btnListReserve]", function () {
        bkfrmAction = APP_URL + '/reservation';
        if($(this).attr("data-exp-name") == "AyurYoga Eco-Ashram Mysore India") {
            bkfrmAction = APP_URL + '/redirect-to-portal';
        }
        $("#frmBooking").attr("action", bkfrmAction);
        $("#frmBooking #hdn_experience_id").val($(this).attr("data-exp-id"));
        $("#frmBooking").submit();
        
    });
    
    $(document).on("click", "a.btn-send-inquiry", function () {
        $("#frmInquiry #experience_id").val($(this).attr("data-exp-id"));
    });
    $("#frmInquiry").validate()
    $("#frmInquiry").on("submit", function () {
        if ($("#frmInquiry").valid()) {
            experienceId = $("#frmInquiry #experience_id").val();
            $("#submit_enquiry").attr("disabled", true);
            $.ajax({
                url: APP_URL + "/store-inquiry",
                method: "POST",
                data: $("#frmInquiry").serialize() + "&experienceId=" + experienceId,
                success: function (result) {
                    if (result) {
                        resultdisp = "";
                        if (result.errors) {
                            jQuery.each(result.errors, function(key, value) {
                                resultdisp += value;
                            });
                        } else {
                            resultdisp = result;
                        }
                        $("#submit_enquiry").after(' <div class="alert alert-danger" align="left">' + resultdisp + '</div>');
                    } else {
                        $("#frmInquiry")[0].reset();
                        $("#submit_enquiry").after('<div class="alert alert-success" align="left">Your Inquiry has been submitted successfully!</div>');
                    }
                    $("#submit_enquiry").attr("disabled", false);
                }
            });
        }
        return false;
    });
});