on = false;
$(document).ready(function() {
    $('select').selectpicker();
    $("#master-checkbox-container").click(function(event) {
        switchCheckBoxes();
    });
});

function switchCheckBoxes(){
    if(this.on){
        $('[data-toggle="checkbox"]').prop('checked', false);
        $('.checkbox').removeClass('checked');
        $('#master-checkbox-container').addClass('checked');
        $('#master-checkbox').prop('checked', false);
        this.on = false;
    }else{
        $('[data-toggle="checkbox"]').prop('checked', true);
        $('.checkbox').slice(1).addClass('checked');
        $('#master-checkbox-container').removeClass('checked');
        $('#master-checkbox').prop('checked', false);
        this.on = true;
    }
}