var filepickerKey = 'AMPdbi1aZQuuzMBLLznNWz';

function convertInkToMediaParams(ink, siteId, nodeId ){
    mediaParams = {};
    mediaParams.filename = ink.key;
    mediaParams.storage = 'S3';
    mediaParams.url = ink.url;
    mediaParams.mime = ink.mimetype;
    mediaParams.size = ink.size;
    mediaParams.siteId = siteId;
    if(nodeId){
        mediaParams.nodeId = nodeId;
    }
    return mediaParams;
}