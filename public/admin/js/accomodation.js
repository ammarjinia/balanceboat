$(function () {
    "use strict";

    // ============================================================== 
    // List Category
    // ============================================================== 

    $('#tblAccomodation').DataTable({
        "order": [
            [0, 'asc']
        ],
        columnDefs: [{orderable: false, targets: [4]}]
    });

    // Delete Accomodation
    $(document).on("click", ".accomodation-delete", function (e) {
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
            $("#frmAccomodation").submit();
            return true;
        });
        return false;
    });
});