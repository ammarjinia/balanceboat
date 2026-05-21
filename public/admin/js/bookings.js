$(function () {
    "use strict";

    // ============================================================== 
    // List Experiences
    // ============================================================== 

    $('#tblBookings').DataTable({
        "order": false,
        columnDefs: [ { orderable: false, targets: [4]}]
    });

    // Delete Experiences
    $(document).on("click", ".experience-delete", function (e) {
        e.preventDefault();
        $("#id").val($(this).attr("data-rel"));
        //Warning Message
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this Experience!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false
        }, function () {
            $("#frmExperiences").submit();
            return true;
        });
        return false;
    });
});