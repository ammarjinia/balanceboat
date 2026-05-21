$(function () {
    "use strict";

    // ============================================================== 
    // List Category
    // ============================================================== 

    $('#tblBlog').DataTable({
        "order": [
            [0, 'desc']
        ],
        columnDefs: [{orderable: false, targets: [2]}]
    });

    // Delete Blog
    $(document).on("click", ".blog-delete", function (e) {
        e.preventDefault();
        $("#id").val($(this).attr("data-rel"));
        //Warning Message
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this blog!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false
        }, function () {
            $("#frmBlog").submit();
            return true;
        });
        return false;
    });
});