$(document).ready(function () {
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
    $("#frmBooking").validate();
    $("a[id^=btnReserve]").on("click", function () {
        $("#frmBooking").submit();
    });

    $("input[name^=exp_accomodation_id]").on("change", function () {
        selected_acm_id = $("input[name^=exp_accomodation_id]:checked").val();
        $(".price_list-min").html($("#accomodation_price_" + selected_acm_id).html());
    });

    if ($("input[name^=exp_accomodation_id]").length > 0) {
        $("input[name^=exp_accomodation_id]").trigger("change");
    }

    $("select#exp_accomodation_id").on("change", function () {
        selected_acm_id = $("select#exp_accomodation_id option:selected").val();
        $(".date_price_acm").hide();
        $(".date_price_acm_" + selected_acm_id).show();
        $(".price_list-min").html($("#accomodation_price_" + selected_acm_id).html());

    });

    $("select#exp_accomodation_id option:selected").trigger("change");

    $("input[name^=booking_date]").on("changeDate", function () {
        bkdate = $(this).val();
        exp_id = $("#hdn_experience_id").val();
        $.ajax({
            url: APP_URL + '/get_ajax_exp_accomodation',
            type: "post",
            data: {'exp_id': exp_id, 'booking_date': bkdate},
            beforeSend: function () {
            }
        }).done(function (data) {
            data = JSON.parse(data);
            $.each(data, function (key, exp) {
                $("#accomodation_price_" + (exp.id)).html(exp.htmlPrice);
                $(".date_price_acm_daily").html(exp.htmlPrice);
            });
            $("select#exp_accomodation_id option:selected").trigger("change");
        });
    });
    
    if ($("input[name^=booking_date].bkdate").length>0 && ($("input[name^=booking_date].bkdate").val() != "")) {
        $("input[name^=booking_date].bkdate").trigger("change");
    }

    $(".carousel").owlCarousel({
        items: 4,
        autoplay: true,
        loop:true,
        margin:10,
        responsiveClass:true
                //itemsDesktop: [1199, 3],
                //itemsDesktopSmall: [979, 3]
    });

    $('.slider-pro').sliderPro({
        width: 960,
        height: 500,
        fade: true,
        arrows: true,
        buttons: false,
        fullScreen: true,
        smallSize: 500,
        startSlide: 0,
        mediumSize: 1000,
        largeSize: 3000,
        thumbnailArrows: true,
        autoplay: false
    });

    /*$('.bkdate').datepicker({
        format: 'mm/dd/yyyy',
        startDate: '+1d',
        clearBtn: true
    });*/

    $(".quick-booking #btnReserve").on("click", function () {
        $("#frmBooking.quick-booking").submit();
    });
    
    var experienceId = $("#experience_id").val();
    $("#frmInquiry").validate()
    $("#frmInquiry").on("submit", function () {
        if ($("#frmInquiry").valid()) {
            $("#submit_enquiry").attr("disabled", true);
            $.ajax({
                url: APP_URL + "/store-inquiry",
                method: "POST",
                data: $("#frmInquiry").serialize() + "&experienceId=" + experienceId,
                success: function (result) {
                    if (result) {
                        $("#submit_enquiry").after(' <div class="alert alert-danger">' + result + '</div>');
                    } else {
                        $("#frmInquiry")[0].reset();
                        $("#submit_enquiry").after('<div class="alert alert-success">Your Inquiry has been saved successfully!</div>');
                    }
                    $("#submit_enquiry").attr("disabled", false);
                }
            });
        }
        return false;
    });
    
    $(document).on('change','.quick-booking #filter_dates_dd, .quick-booking #booking_date',function(e){
        $('.quick-booking #booking_date').val($(this).val());
        calculatePrice();
    });

    $(document).on('change','.quick-booking #exp_accomodation_id',function(e){
        calculatePrice();
    });
    
    $(document).on('change','.quick-booking #durations',function(e){
        calculatePrice();
    });
    changeFilters();
});

function calculatePrice(){
    if($('.quick-booking #exp_accomodation_id').val() =='' || $('.quick-booking #booking_date').val()==''|| $('#hdn_experience_id').val()==''){
        $('.quick-booking #quickReserverBtn').prop('disabled',true);
        //$('input[name="filter_price"]').val('');
    }else{
        $.ajax({
            url: APP_URL + '/get_booking_price',
            type: "post",
            data: {'exp_id': $('#hdn_experience_id').val(),'booking_date':$('.quick-booking #booking_date').val(), 'acc_id':$('.quick-booking #exp_accomodation_id').val(),'days': $(".quick-booking #durations option:selected").val()},
            beforeSend: function () {
            }
        }).done(function (data) {
            data = JSON.parse(data);
            if(data){
                if(data.accomodation_price > 0){
                    $('.quick-booking #quickReserverBtn').prop('disabled',false);
                    $('.quick-booking .booking_amount').html(data.booking_amount_html_price);
                    $('.quick-booking .date_price_acm').html(data.accomodation_html_price).show();
                } else {
                    $('.quick-booking .date_price_acm, .quick-booking .booking_amount').html('-');
                    $('.quick-booking #quickReserverBtn').prop('disabled',true);
                }
            }else{
                $('.quick-booking .date_price_acm, .quick-booking .booking_amount').html('-');
                $('.quick-booking #quickReserverBtn').prop('disabled',true);
            }
        });
    }
}

/**
 * Function to load filters for booking date and accomodation
 */ 
function changeFilters(){
    $.ajax({
        url: APP_URL + '/get_ajax_filter_values',
        type: "post",
        data: {'exp_id': $('#hdn_experience_id').val()},
        beforeSend: function () {
        }
    }).done(function (data) {
        data = JSON.parse(data);
        if(data){
            
            if(data.experience_durations !=''){
               $('.quick-booking #durations').append(data.experience_durations); 
            }
            
            if(data.experience_accomodations_options !=''){
               $('.quick-booking #exp_accomodation_id').append(data.experience_accomodations_options); 
            }
            /*if(data.experience_date_type=='daily'){
                $('.quick-booking div.filter_dates_radio_group').hide();
                $('.quick-booking div.no_booking_date').hide();
                $('.quick-booking div.filter_daily_exp').show();
                
            }else{
                $('.quick-booking div.filter_daily_exp').hide();                
                if(data.experience_dates!=''){
                    $('.quick-booking #filter_dates_dd').append(data.experience_dates);
                    $('.quick-booking div.no_booking_date').hide();
                    $('.quick-booking div.filter_dates_radio_group').show();
                }else{
                    $('.quick-booking div.filter_dates_radio_group').hide();
                    $('.quick-booking div.no_booking_date').show();
                }
            }*/
            
            
                $('.quick-booking div.no_booking_date').hide();
                $('.quick-booking div.filter_daily_exp').show();
                
                if ($('.quick-booking #durations option').length == 2) {
                    $('.quick-booking #durations').val($('.quick-booking #durations option:eq(1)').val());
                }
            
        }
    });
}