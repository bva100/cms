$("#api-explorer").on('click', function(){
    window.location.href = document.getElementById('api-explorer-path').value;
});

function confirmDelete(name){
    return confirm('Are you sure you want to remove ' + name + '?');
}