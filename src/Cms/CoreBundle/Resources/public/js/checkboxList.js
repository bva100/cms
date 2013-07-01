btnCallbackClass = '.btn-checkbox-action';
checkboxContainer = 'btn-checkbox-callback';

$(document).ready(function() {
    $('.checkbox').click(function(){
        switchCheckbox($(this));
    })
});

function switchCheckbox($checkbox){
    if($checkbox.hasClass('master-checkbox')){
        switchAllCheckboxes($checkbox);
    }else{
        switchCheckbox($checkbox);
    }
    updateBtns();
    return 1;
}

function switchCheckbox($checkbox){
    $realCheckbox = $checkbox.find('.slave-checkbox');
    if($realCheckbox.is(':checked')){
        $realCheckbox.prop('checked', false);
    }else{
        $realCheckbox.prop('checked', true);
    }
    return 1;
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

function updateBtns(){
    console.log($('input[name=content-checkbox]:checked').length);
    if($('input[name=content-checkbox]:checked').length > 0){
        $('.btn-checkbox-callback').prop('disabled', false);
    }else{
        $('.btn-checkbox-callback').prop('disabled', 'disabled');
    }
    return 1;
}