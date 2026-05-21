$(function () {
    "use strict";

    // ============================================================== 
    // List Category
    // ============================================================== 

    $('#tblCommission').DataTable({
        "order": [
            [0, 'asc']
        ],
        columnDefs: [{orderable: false, targets: [3]}]
    });

    // Delete Accomodation
    $(document).on("click", ".commission-delete", function (e) {
        e.preventDefault();
        $("#id").val($(this).attr("data-rel"));
        //Warning Message
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this accomodation!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false
        }, function () {
            $("#frmCommission").submit();
            return true;
        });
        return false;
    });
});