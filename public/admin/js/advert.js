$(function () {
    "use strict";

    // ============================================================== 
    // List Adverts
    // ============================================================== 

    $('#tblAdvert').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url" : ADMIN_URL + "/adverts",
            "data":{"ajax":true}
        },
        "order": [
            [0, 'desc']
        ],
        columnDefs: [{orderable: false, targets: [1]}]
    });

    // Delete Advert
    $(document).on("click", ".advert-delete", function (e) {
        e.preventDefault();
        $("#id").val($(this).attr("data-rel"));
        //Warning Message
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this advert!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false
        }, function () {
            $("#frmAdvert").submit();
            return true;
        });
        return false;
    });
});