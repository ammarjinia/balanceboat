$(function () {
    "use strict";

    // ============================================================== 
    // List Centers
    // ============================================================== 

    $('#tblCenters').DataTable({
        "order": [
            [0, 'asc']
        ],
        columnDefs: [{orderable: false, targets: [3]}]
    });

    // Delete Centers
    $(document).on("click", ".center-delete", function (e) {
        e.preventDefault();
        $("#id").val($(this).attr("data-rel"));
        //Warning Message
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this center!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false
        }, function () {
            $("#frmCenters").submit();
            return true;
        });
        return false;
    });
});