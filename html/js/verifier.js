$(function() {
    $('input[name="daterange"]').daterangepicker({
        opens: 'right',
        startDate: moment(),
        autoUpdateInput: false,
        locale: {
            format: 'DD/MM/YYYY',
            applyLabel: 'Confirmer', 
            cancelLabel: 'Annuler',
            daysOfWeek: [
                "Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam"
            ],
            monthNames: [
                "Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
                "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"
            ],
            firstDay: 1
        },
        applyButtonClasses: 'custom-apply-button',
        cancelButtonClasses: 'custom-cancel-button'
    }, function(start, end, label) {
        $('input[name="daterange"]').val(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
        console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
    });
});