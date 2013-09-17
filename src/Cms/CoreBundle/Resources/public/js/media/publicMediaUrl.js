function getPublicMediaUrl(){
    var abbrHostname = getAbbrHostname();
    switch(abbrHostname){
        case 'dev':
        case 'localhost':
            return 'http://d10nuh33ml191h.cloudfront.net/';
            break;
        default:
            return '';
            break;
    }
}

function getAbbrHostname(){
    switch(window.location.hostname){
        case 'pipestack.com':
            return 'prod';
        case 'dev.pipestack.com':
            return 'dev';
        case 'localhost':
        case '127.0.0.1':
            return 'localhost';
            break;
        default:
            return '';
            break;
    }
}