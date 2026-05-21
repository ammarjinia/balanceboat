$(function () {
    "use strict";

    // ============================================================== 
    // List Certificate
    // ============================================================== 

    $('#tblCertificate').DataTable({
        "order": [
            [0, 'asc']
        ],
        columnDefs: [{orderable: false, targets: [3]}]
    });

    // Delete Certificate
    $(document).on("click", ".certificate-delete", function (e) {
        e.preventDefault();
        $("#id").val($(this).attr("data-rel"));
        //Warning Message
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this Certificate!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false
        }, function () {
            $("#frmCertificate").submit();
            return true;
        });
        return false;
    });
});