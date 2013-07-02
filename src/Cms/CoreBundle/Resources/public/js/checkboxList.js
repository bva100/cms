btnCallbackClass = '.btn-checkbox-action';
checkboxContainer = 'btn-checkbox-callback';

$(document).ready(function() {
    $('.checkbox').on('click', function(){
        switchCheckbox($(this));
    });
    $('.btn-checkbox-action').on('click', function(){
        token = $("#token").val();
        baseUrl = $("#baseRoot").val();
        var action = $(this).attr('data-action');
        var isChecked = checkIfNone();
        if(isChecked == 0){
            alert('please select an item');
            return 0;
        }
        var ids = getCheckedIds();
        switch (action){
            case 'delete':
                ids.forEach(function(id){
                    deleteNode(id, token);
                });
                break;
            case 'edit':
                var id = ids[0];
                window.location.href = baseUrl + '/node/'+id;
                break;
            default:
                alert('action not found');
                break;
        }
    })
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

function deleteNode(id, token){
    $.post('/node/delete', {id: id, token: token}, function(data, textStatus, xhr) {
        if(textStatus == 'success'){
            $('#tr-' + id).remove();
        }else{
            alert('Unsuccessful delete. Please try again.');
        }
    });
}