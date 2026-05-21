$(document).ready(function () {
    "use strict";

    // ============================================================== 
    // List Experiences
    // ============================================================== 

    var tblExp = $('#tblExperiences').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url:"experiences",
            data:function(d) {
                d.sbookable = $("#sbookable").val();
                d.sdestination = $("#sdestination").val();
            }
        },
        "order": [
            [0, 'asc']
        ],
        
        "columns": [
            { "data": "name", "orderable": false },
            { "data": "slug", "orderable": false },
            { "data": "CenterName", "orderable": false },
            { "data": "LastUpdatedAt", "orderable": false },
            { "data": "action", "orderable": false, "searchable": false }
        ]
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
    
    $("#sbookable").on("change", function(){
        tblExp.ajax.reload();
    });

    $("#sdestination").on("change", function(){
        tblExp.ajax.reload();
    });
});
