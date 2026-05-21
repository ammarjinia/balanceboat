$(document).ready(function () {
    $("#btnReserve").on("click", function () {
        $("#frmBooking").submit();
    });
    $('.bkdate').datepicker({
        format: 'mm/dd/yyyy',
        startDate: '+1d',
        clearBtn: true
    });
    
    $(document).on('change','#hdn_experience_id',function(e){ 
        $('#quickReserverBtn').prop('disabled',true);          
        $('#exp_accomodation_id').val('');          
        $('#booking_date').val('');  
        $('.date_price_acm').html('');
        $('.bkdate').val('');

        $('#exp_accomodation_id').find("option").remove();      
        $('#filter_dates_dd').find("option:gt(0)").remove();
        $('#durations').find("option:gt(0)").remove();
        changeFilters();
    });

    $(document).on('change','#filter_dates_dd, #booking_date',function(e){
        $('#booking_date').val($(this).val());
        calculatePrice();
    });

    $(document).on('change','#exp_accomodation_id',function(e){
        calculatePrice();
    });
    
    $(document).on('change','#durations',function(e){
        calculatePrice();
    });
    
    if ($(".carousel").length > 0) {
     $(".carousel").owlCarousel({
        items: 4,
        autoplay: true,
        nav:true,
        lazyLoad:true,
                //itemsDesktop: [1199, 3],
                //itemsDesktopSmall: [979, 3]
    });
    }
    
});

function calculatePrice(){
    if($('#exp_accomodation_id').val() =='' || $('#booking_date').val()==''|| $('#hdn_experience_id').val()==''){
        $('#quickReserverBtn').prop('disabled',true);
        $('input[name="filter_price"]').val('');
    }else{
        $.ajax({
            url: APP_URL + '/get_booking_price',
            type: "post",
            data: {'exp_id': $('#hdn_experience_id').val(),'booking_date':$('#booking_date').val(), 'acc_id':$('#exp_accomodation_id').val(),'days': $("#durations").val()},
            beforeSend: function () {
            }
        }).done(function (data) {
            data = JSON.parse(data);
            if(data){
                if(data.accomodation_html_price!=''){
                    $('.date_price_acm').html(data.accomodation_html_price);
                    $('#quickReserverBtn').prop('disabled',false);
                }
            }else{
                $('.date_price_acm').html('');
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
               $('#durations').append(data.experience_durations); 
            }
            
            if(data.experience_accomodations_options !=''){
               $('#exp_accomodation_id').append(data.experience_accomodations_options); 
            }
            if(data.experience_date_type=='daily'){
                $('div.filter_dates_radio_group').hide();
                $('div.no_booking_date').hide();
                $('div.filter_daily_exp').show();
                
            }else{
                $('div.filter_daily_exp').hide();                
                if(data.experience_dates!=''){
                    $('#filter_dates_dd').append(data.experience_dates);
                    $('div.no_booking_date').hide();
                    $('div.filter_dates_radio_group').show();
                }else{
                    $('div.filter_dates_radio_group').hide();
                    $('div.no_booking_date').show();
                }
            }
        }
    });
   
}