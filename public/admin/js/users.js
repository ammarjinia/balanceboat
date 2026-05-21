$(function () {
    "use strict";

    // ============================================================== 
    // List Users
    // ============================================================== 

    $('#tblUsers').DataTable({
        "order": [
            [0, 'asc']
        ],
        columnDefs: [{orderable: false, targets: [4]}]
    });

    // Delete User
    $(document).on("click", ".user-delete", function (e) {
        e.preventDefault();
        var frm = $(this).parents("form");
        //Warning Message
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this teacher!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false
        }, function () {
            frm.submit();
            return true;
        });
        return false;
    });
});