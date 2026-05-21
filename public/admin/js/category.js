$(function () {
    "use strict";

    // ============================================================== 
    // List Category
    // ============================================================== 

    $('#tblCategory').DataTable({
        "order": false,
        columnDefs: [{orderable: false, targets: [4]}]
    });

    // Delete Category
    $(document).on("click", ".cat-delete", function (e) {
        e.preventDefault();
        $("#id").val($(this).attr("data-rel"));
        //Warning Message
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this category!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false
        }, function () {
            $("#frmCategory").submit();
            return true;
        });
        return false;
    });
});