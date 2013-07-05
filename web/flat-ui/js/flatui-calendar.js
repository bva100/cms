$(document).ready(function() {
    $(".flatui-calendar-input-start").datepicker({
        showOtherMonths: true,
        selectOtherMonths: true,
        onClose: function( selectedDate ) {
            $( "#end-date" ).datepicker( "option", "minDate", selectedDate );
            $("#end-date").datepicker("show");
        }
    });

    $(".flatui-calendar-input-end").datepicker({
        showOtherMonths: true,
        selectOtherMonths: true,
        onClose: function( selectedDate ) {
            $( "#start-date" ).datepicker( "option", "maxDate", selectedDate );
        }
    });

    $(".flatui-calendar").on('click', function () {
        $("#flatui-calendar-input-container").toggle();
        $(this).blur();
    });

    $("#flatui-calendar-confirm-dates").on('click', function(){
        $(".flatui-calendar-text").text('Change Dates');
        $("#flatui-calendar-input-container").hide();
    });

    $("#flatui-calendar-cancel-dates").on('click', function(){
        $("#start-date").val('');
        $("#end-date").val('');
        $(".flatui-calendar-text").text('View All Dates');
        $("#flatui-calendar-input-container").hide();
    });
});