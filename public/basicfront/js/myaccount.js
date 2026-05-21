$(document).ready(function () {
    $("#frmUpdateProfile").validate();

    $(document).on("click", "#img_delete", function (e) {
        e.preventDefault();
        $(this).button("loading");
        $(this).attr("disabled", true);
        $.post($(this).attr("href"), {'id': $(this).attr("data-id")}, function (response) {
            if (response) {
                alert('Image has been deleted.');
                $("#user_img_container").remove();
            } else {
                alert('Oops... Something went wrong');
            }
            $(this).attr("disabled", false);
            $(this).button("reset");
        });
        return false;
    });
});