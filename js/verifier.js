$(function() {
    $('input[name="daterange"]').daterangepicker({
      opens: 'right',
      "startDate": "05/13/2024",
    }, function(start, end, label) {
      console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
    });
  });