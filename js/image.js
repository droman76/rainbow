
window.addEventListener('load',function(event){
});

var start=0;
var hasimages = false;
var fileList;
    function handleFileSelect(evt) {
    var files = evt.target.files; // FileList object

    // Loop through the FileList and render image files as thumbnails.
    for (var i = 0, f; f = files[i]; i++) {

      // Only process image files.
      if (!f.type.match('image.*')) {
        continue;
      }
      else {
        hasimages = true;
        fileCounter++;
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
      if (fileCounter==1)
          _("feed-textarea").setAttribute('placeholder',"What would you like to share about this image?");
      else if (fileCounter > 1)    
          _("feed-textarea").setAttribute('placeholder',"What would you like to share about these images?");
       

      // Read in the image file as a data URL.
      reader.readAsDataURL(f);
    }
    if (hasimages) handleUpload();
  }
  var fileCounter = 0;
  function handleDragFileSelect(evt) {

    evt.stopPropagation();
    evt.preventDefault();

    var files = evt.dataTransfer.files; // FileList object.
    fileList = files;
    for (var i = 0, f; f = files[i]; i++) {

      // Only process image files.
      if (!f.type.match('image.*')) {
        alert('Only Images Please!');
        continue;
      }
      else {
        //makesure dropzone is visible
       hasimages = true;
       fileCounter++;

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
    if (hasimages){
      handleUpload();
     _("imagedropzone").style.display="block";
        scroll(0,0);

        _("feed-textarea").focus();
        if (fileCounter==1)
          _("feed-textarea").setAttribute('placeholder',"What would you like to share about this image?");
        else    
          _("feed-textarea").setAttribute('placeholder',"What would you like to share about these images?");
        _("fileinput").style.display='none';
        _("addimages_text").innerHTML = "Add more Images";
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

