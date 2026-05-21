$(function () {
    "use strict";

    // ============================================================== 
    // List deals
    // ============================================================== 

    var tblLeads =  $('#tblLead').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url" : ADMIN_URL + "/leads",
            "data": function(data){
                data.ajax = true;
                //data.listing_id = $("#listing_id").val();
                data.start_date = $('input[name="start_date"]').val();
                data.end_date = $('input[name="end_date"]').val();
            }
        },
        "order": [
            [0, 'desc']
        ],
        columnDefs: [{orderable: false, targets: [4,5]}]
    });

    // Delete deals
    $(document).on("click", ".lead-delete", function (e) {
        e.preventDefault();
        $("#id").val($(this).attr("data-rel"));
        //Warning Message
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this Lead!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false
        }, function () {
            $("#frmLead").submit();
            return true;
        });
        return false;
    });
    
    /*$('#listing_id').select2({
        minimumInputLength: 2,
        ajax: {
            url: ADMIN_URL + "/comment/getlistings",
            dataType: 'json',
            data: function (params) {
                var query = {
                    search: params.term
                }

                // Query parameters will be ?search=[term]&type=public
                return query;
            },
            processResults: function (data) {
                // Tranforms the top-level key of the response object from 'items' to 'results'
                return {
                    results: data
                };
            }
        }
    });
    
    $('#listing_id').on("change", function(){
        tblLeads.draw();
    });*/
    
    $("#btnExport").on("click", function(){
       $("#search").val($("input[type='search']").val()); 
    });

    $('#date-range').datepicker({
        toggleActive: true,
        format: 'dd-mm-yyyy',
        orientation: "bottom",
        autoclose: true
    });

    $('#date-range input').on('change', function() {
        tblLeads.draw();
    });
});