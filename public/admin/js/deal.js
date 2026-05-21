$(function () {
    "use strict";

    // ============================================================== 
    // List deals
    // ============================================================== 

    $('#tblDeal').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url" : ADMIN_URL + "/deals",
            "data":{"ajax":true}
        },
        "order": [
            [0, 'desc']
        ],
        columnDefs: [{orderable: false, targets: [1]}]
    });

    // Delete deals
    $(document).on("click", ".deal-delete", function (e) {
        e.preventDefault();
        $("#id").val($(this).attr("data-rel"));
        //Warning Message
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this Deal!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false
        }, function () {
            $("#frmDeal").submit();
            return true;
        });
        return false;
    });
});