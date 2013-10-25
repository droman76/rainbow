<html>
<head>


<script>

// ************************************************************
var handleUpload = function(){
     // event.preventDefault();
      //event.stopPropagation();
  //alert(gato);
  var fileInput = document.getElementById('files');
  var data = new FormData();

  /*for(var i = 0; i < fileInput.files.length; ++i){
     data.append('file[]',fileInput.files[i]);
  }
  */
  for (var i = 0, f; f = fileList[i]; i++) {
      //alert(f);
      data.append('file[]',f);
  }

  var request = new XMLHttpRequest();
      request.upload.addEventListener('progress',function(event){
   if(event.lengthComputable){

      var percent = event.loaded / event.total;
      var progress = document.getElementById('upload_progress');

    while (progress.hasChildNodes()){
           progress.removeChild(progress.firstChild);
         }
           progress.appendChild(document.createTextNode(Math.round(percent * 100) + '%'));
   }
});

           request.upload.addEventListener('load',function(event){
           document.getElementById('upload_progress').style.display = 'none';
});
           request.upload.addEventListener('error',function(event){
           alert("failed");

});
           request.open('POST','upload.php');
           request.setRequestHeader('Cache-Control','no-cache');
           request.send(data);
           document.getElementById('upload_progress').style.display = 'block';

};

           window.addEventListener('load',function(event){
           var submit = document.getElementById('submit');
           submit.addEventListener('click',handleUpload);
});


</script>

</head>





<style>
#drop_zone {
border: 2px dashed #bbb;
-moz-border-radius: 5px;
-webkit-border-radius: 5px;
border-radius: 5px;
padding: 0px;
text-align: center;
font: 20pt bold 'Vollkorn';
color: #bbb;
}
</style>
<style>
  .thumb {
    height: 75px;
    border: 1px solid #000;
    margin: 10px 5px 0 0;
  }
</style>

<output id="list"></output>   
<div id="drop_zone">Or Drag & Drop files here</div>
<output id="list"></output>

<div id = "upload_progress"></div>

<form id="form" action="" method="post" enctype="multipart/form-data">
    <input type="file" id="files" name="files[]" multiple />
     <br>
    <input type="submit" name="send" id ="submit" value="send">
</form>
<a href="javascript:handleUpload()">Sae to serverr</a>


<script>
var start=0;
var fileList;
    function handleFileSelect(evt) {
    var files = evt.target.files; // FileList object

    // Loop through the FileList and render image files as thumbnails.
    for (var i = 0, f; f = files[i]; i++) {

      // Only process image files.
      if (!f.type.match('image.*')) {
        continue;
      }

      var reader = new FileReader();

      // Closure to capture the file information.
      reader.onload = (function(theFile) {
        return function(e) {
          // Render thumbnail.
          var span = document.createElement('span');
          span.innerHTML = ['<img class="thumb" src="', e.target.result,
                            '" title="', escape(theFile.name), '"/>'].join('');
                            if (start ==0)
            document.getElementById('drop_zone').innerHTML = '';
          start=1;
          document.getElementById('drop_zone').insertBefore(span, null);
        };
      })(f);

      // Read in the image file as a data URL.
      reader.readAsDataURL(f);
    }
  }
  
  function handleDragFileSelect(evt) {

    evt.stopPropagation();
    evt.preventDefault();

    var files = evt.dataTransfer.files; // FileList object.
    fileList = files;
    for (var i = 0, f; f = files[i]; i++) {

      // Only process image files.
      if (!f.type.match('image.*')) {
        continue;
      }

      var reader = new FileReader();

      // Closure to capture the file information.
      reader.onload = (function(theFile) {
        return function(e) {
          // Render thumbnail.
          var span = document.createElement('span');
          span.innerHTML = ['<img class="thumb" src="', e.target.result,
                            '" title="', escape(theFile.name), '"/>'].join('');
          if (start ==0)
            document.getElementById('drop_zone').innerHTML = '';
          start=1;
          document.getElementById('drop_zone').insertBefore(span, null);
        };
      })(f);

      // Read in the image file as a data URL.
      reader.readAsDataURL(f);
    }
    
  }

  function handleDragOver(evt) {
    evt.stopPropagation();
    evt.preventDefault();
    evt.dataTransfer.dropEffect = 'copy'; // Explicitly show this is a copy.
  }

  // Setup the dnd listeners.
  //var dropZone = document.getElementById('drop_zone');
  var dropZone = document;
  
  dropZone.addEventListener('dragover', handleDragOver, false);
  dropZone.addEventListener('drop', handleDragFileSelect, false);
  document.getElementById('files').addEventListener('change', handleFileSelect, false);

function resize_textarea(t) {
  //ert('resizing');
a = t.value.explode('\n');
b=1;
for (x=0;x < a.length; x++) {
 if (a[x].length >= t.cols) b+= Math.floor(a[x].length/t.cols);
 }
b+= a.length;
if (b > t.rows) t.rows = b;
}
</script>

<style>
* { font-family: sans-serif; font-size: 10pt; font-weight: normal; }
.wrapper     { position: relative; width: 400px; height: 400px; outline: solid 1px #666; }
.wrapper > * { position: absolute; top: 0; left: 0; height: 100%; width: 100%; margin: 0; padding: 0; }
.highlighter { background-color: #fff; color: #fff; }
.highlight   { background-color: #9ff; color: #9ff; }
</style>

<div class="wrapper">
<div class="highlighter">
This <span class="highlight">is a</span> demonstration.
</div>
<textarea cols="40" rows="3" onclick="sz(this);" onkeyup="sz(this);">HOla</textarea>
This is a demonstration.
</textarea>
</body>

</html>