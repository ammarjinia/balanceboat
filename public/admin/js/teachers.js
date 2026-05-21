$(function () {
    "use strict";

    // ============================================================== 
    // List Teachers
    // ============================================================== 

    $('#tblTeachers').DataTable({
        "order": [
            [0, 'asc']
        ],
        columnDefs: [{orderable: false, targets: [4]}]
    });

    // Delete Teacher
    $(document).on("click", ".teacher-delete", function (e) {
        e.preventDefault();
        $("#id").val($(this).attr("data-rel"));
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
            $("#frmTeachers").submit();
            return true;
        });
        return false;
    });
});