$("#api-explorer").on('click', function(){
    window.location.href = document.getElementById('api-explorer-path').value;
});
$("#show-token").click(function(){
    $(this).hide();
    $("#app-token").show();
});
$(".holder-container").click(function(){
    var href = $(this).attr('data-href');
    window.location.href = href;
})
function confirmDelete(name){
    return confirm('Are you sure you want to remove ' + name + '?');
}