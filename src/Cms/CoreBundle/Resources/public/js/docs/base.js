$(".doc-json").each(function(){
    var json = JSON.parse($(this).text());
    var formatted = JSON.stringify(json, null, 6);
    $(this).text(formatted);
});