$("#api-explorer").on('click', function(){
    window.location.href = document.getElementById('api-explorer-path').value;
});
$("#show-token").click(function(){
    $(this).hide();
    $("#app-token").show();
})
function confirmDelete(name){
    return confirm('Are you sure you want to remove ' + name + '?');
}