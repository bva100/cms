$(document).ready(function() {
    $('.checkbox').on('click', function(){
        switchCheckbox($(this));
    });
});

function switchCheckbox($checkbox){
    if($checkbox.hasClass('master-checkbox')){
        switchAllCheckboxes($checkbox);
    }else{
        switchOneCheckbox($checkbox);
    }
    return 1;
}

function switchOneCheckbox($checkbox){
    if($checkbox.hasClass('checked')){
        $("#master-checkbox-container").removeClass('checked');
        $("#master-checkbox-container").attr('data-state', 'off');
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

function checkIfNone() {
    return $('.checked:not(".master-checkbox")').length;
}

function getCheckedIds(){
    var ids = [];
    $(".checked").each(function(){
        var $this = $(this);
        if($this.hasClass("checked")){
            ids.push($this.attr('data-id'));
        }
    });
    return ids;
}