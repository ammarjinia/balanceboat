$(document).ready(function () {
    $(document).on("change", "#sort_by", function () {
        $("#frmExperience").submit();
    });

    var page = 1;
    $(window).scroll(function () {
        if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
            page++;
            if ($(".btn-load-more").length > 0) {
                $(".btn-load-more").trigger("click");
            }
        }
    });

    $(document).on("click", ".btn-load-more", function () {
        page++;
        loadMoreData(page);
    });

    function loadMoreData(page) {
        $.ajax({
            url: APP_URL + '/experiences/loadDataAjax?page=' + page+'&sdestination='+$("#sdestination").val()+'&scategory='+$("#scategory").val()+'&sexp_date='+$("#sexp_date").val(),
            type: "get",
            data: $("#frmExperience").serialize(),
            beforeSend: function () {
                $('.ajax-load').show();
            }
        }).done(function (data) {
            if (data.html == "") {
                $('.ajax-load').html("No more records found");
                return;
            }
            $('.ajax-load').hide();
            $("#experience_data").append(data.html);
        }).fail(function (jqXHR, ajaxOptions, thrownError) {
            alert('server not responding...');
        });
    }
});