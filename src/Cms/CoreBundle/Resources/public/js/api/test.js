var baseUrl = document.getElementById('base-url').value;

$("#api-form").on('submit', function(event){
    event.preventDefault();
    $container = $('#results');
    $container.html('');
    loadApiData(getParams(), $container);
});

function getParams(){
    var params = {};
    params.output = document.getElementById('input-output').value;
    params.endpoint = document.getElementById('input-endpoint').value;
    params.path = document.getElementById('input-path').value;
    return params;
}

function loadApiData(params, $container){
    var url = baseUrl+'/api/v1/'+params.endpoint+'/'+params.path;
    switch(params.endpoint){
        case 'node':
            return loadNode(url, params, $container);
        case 'loop':
            return loadLoop(url, params, $container);
            break;
        default:
            break;
    }
}

function loadLoop(url, params, $container){
    $.get(url, {}, function(data, textStatus, xhr) {
        if(xhr.status === 200){
            var nodes = data.loop;
            switch(params.output){
                case 'raw':
                    $container.text(JSON.stringify(data));
                    $container.append('<hr><h2>Loop node data:</h2>'+JSON.stringify(data.node));
                    break;
                case 'text':
                    for (var i=0; i<nodes.length; i++)
                    {
                        $container.append('<h2>Node '+i+'</h2><div class="row" style="border-bottom: 1px solid dimgrey; margin: 10px 0px 20px 0px; padding: 30px; 0px;"><div class="span12">'+nodes[i].view.text+'</div></div>');
                    }
                    break;
                case 'html':
                default:
                    for (var i=0; i<nodes.length; i++)
                    {
                        $container.append('<h2>Node '+i+'</h2><div class="row" style="border-bottom: 1px solid dimgrey; margin: 10px 0px 20px 0px; padding: 30px; 0px;"><div class="span12">'+nodes[i].view.html+'</div></div>');
                    }
                    break;
            }
            return 1;
        }else{
            alert('failed');
            return 0;
        }
    }).fail(function(data, textStatus, xhr){
            $container.html(createResponseAlert(xhr));
            return 0;
        });
}

function loadNode(url, params, $container){
    $.get(url, {}, function(data, textStatus, xhr) {
        if(xhr.status === 200){
            var node = data.node;
            switch(params.output){
                case 'raw':
                    $container.text(JSON.stringify(data));
                    $container.append('<hr><h2>Loop node data:</h2>'+JSON.stringify(data.node));
                    break;
                case 'text':
                    $container.html(node.view.text);
                    break;
                case 'html':
                default:
                    $container.html(node.view.html);
                    break;
            }
        }else{
            alert('failed');
            return 0;
        }
    }).fail(function(data, textStatus, xhr){
            $container.html(createResponseAlert(xhr));
            return 0;
        });
}

function createResponseAlert(xhr){
    return '<div class="row"><div class="alert alert-danger span2 offset4"><i class="icon-warning">'+xhr+'</i></div></div>';
}