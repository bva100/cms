btnCallbackClass = 'btn-checkbox-callback';

$(document).ready(function() {
    $('.checkbox').click(function(){
        switchCheckbox($(this));
    })
});

function switchCheckbox($checkbox){
    if($checkbox.hasClass('master-checkbox')){
        switchAllCheckboxes($checkbox);
    }
}

function switchAllCheckboxes($master){
    if($master.attr('data-state') == 'on'){
        $('.checkbox:not(".master-checkbox")').removeClass('checked');
        $('[data-toggle="checkbox"]').prop('checked', false);
        $master.attr('data-state', 'off');
    }else{
        $('.checkbox:not(".master-checkbox")').addClass('checked');
        $('[data-toggle="checkbox"]').prop('checked', true);
        $master.attr('data-state', 'on');
    }
    return 1;
}