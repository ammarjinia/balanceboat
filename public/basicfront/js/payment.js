$(document).ready(function () {
    $("#btnPayment").on("click", function () {
        $("#is_pay").val(0);
        $("#frmPayment").submit();
    });
});