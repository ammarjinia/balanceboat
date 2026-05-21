$(document).ready(function () {
    $('#arrival_date').datepicker({
        format: 'mm/dd/yyyy',
        startDate: '+1d',
        clearBtn: true
    });
});