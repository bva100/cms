var filepickerKey="AMPdbi1aZQuuzMBLLznNWz";function convertInkToMediaParams(a,b,c){mediaParams={};mediaParams.filename=a.key;mediaParams.storage="S3";mediaParams.url=a.url;mediaParams.mime=a.mimetype;mediaParams.size=a.size;mediaParams.siteId=b;if(c){mediaParams.nodeId=c}return mediaParams};
var siteId=document.getElementById("site-id").value;var mediaAddPath=document.getElementById("media-add-path").value;$(document).ready(function(){filepicker.setKey(filepickerKey);uploader()});function uploader(){filepicker.pickAndStore({container:"media-uploader"},{location:"S3",access:"public"},function(b){var a=convertInkToMediaParams(b[0],siteId);$.post(mediaAddPath,a,function(f,h,g){if(h=="success"){var e=document.getElementById("media-upload-complete");e.className="row-fluid";var d=document.getElementById("media-list");d.className="";var c=document.createElement("li");c.setAttribute("class","media-item row-fluid");c.innerHTML="<img src='"+a.url+"' class='span3' /><div class='span7'><h4>"+a.url+"</h4><p>"+a.mime+"</p></div><div class='span2'><a href='#edit' class='btn btn-info btn-block'>edit</a></div>";d.appendChild(c);uploader()}else{alert("Upload failed. Please try again")}})})};