$(".delete-contentType").on('click', function(event){
    event.preventDefault();
    if(!confirm('Are you sure you want to delete this contentType?')){
        return 0;
    }
    $(this).text('deleting...').attr('disabled', true);
    $(this).parent('form').submit();
});