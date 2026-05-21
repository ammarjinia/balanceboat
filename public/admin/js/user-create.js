!function (window, document, $) {
    "use strict";
    $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
}(window, document, jQuery);
$(function () {
    "use strict";
    
    $('#center_id').select2({
        minimumInputLength: 2,
        ajax: {
            url: ADMIN_URL + "/centers/getcenters",
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
});
