@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading"><h3>Product Files</h3>

                </div>

                <div class="panel-body">
                     <div class="row" style="padding-top:10px;">
                        <div class="col-xs-2">
                          
                        </div>
                        <div class="col-xs-10">
                        <form id="file-form" action="#" method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            Upload a File (PDF):
                            <input type="file" id="uploadfile" name="uploadfile"><br />
                            <div style="margin: 10px 0 20px 0;padding:5px">Title: This is name that is displayed in the ecat download link.<link rel="stylesheet" href="" class=""><br /><input type="text" name="title" id="title" /></div>
                            <div style="margin: 10px 0 20px 0;padding:5px">Description: This is not currently used or displayed anywhere <br />
                            <input type="text" name="description" id="description" /></div>
                            <input type="submit" id="submit" name="submit" value="Upload File Now" >
                        </form>

                        <p id="status"></p>
                      
                        </div>
                    </div>

                      <div class="row" style="padding-top:10px;">
                        <div class="col-xs-10">
                          <div id="msgBox">
                          </div>
                        </div>
                      </div>

                      @if ($files && $files->count())
                        <ul id="filesortable" class="sortable list-group">
                         @foreach ($files as $i)
                         <li id="item-{{ $i->id }}" class="list-group-item">
                            <span>{{ $i->filename }}</span>
                            <span>{{ $i->title }}</span>
                            <span>{{ $i->description }}</span>
                         <a title="delete file" class="pull-right" ><i style="margin-top:-5px" class="fa fa-trash fa-2x" aria-hidden="true"></i></a> </li>

                         @endforeach
                         </ul>

                     @endif





                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script> 
var typeid = {{ $typeid }};
var uploadUrl = '{{ route("producttype.file.upload",["typeid"=>$typeid ]) }}';

// Sortable for type images
$( "#filesortable" ).sortable({

axis: 'y',
update: function (event, ui) {
    // do something?

    
}

});

// setup delete click handler to delete images
$('#filesortable li a').on('click', function(e){
var fileId = $(e.target).closest('li').attr('id').substring(5,20) 


$.post('ajax/file/delete/' + fileId, function(data) {
    if (data > 0){
        //$('#item-'+ data).remove(); // remove the deleted item
        location.reload();
    }
    
});


})






$( "#sortable" ).disableSelection();

</script>

<script>
(function(){
    var form = document.getElementById('file-form');
    var fileSelect = document.getElementById('uploadfile');
    var fileTitle = document.getElementById('title');
    var fileDescription = document.getElementById('description');
    var uploadButton = document.getElementById('submit');
    var statusDiv = document.getElementById('status');

    form.onsubmit = function(event) {
        event.preventDefault();

        statusDiv.innerHTML = 'Uploading . . . ';

        // Get the files from the input
        var files = fileSelect.files;

        // Create a FormData object.
        var formData = new FormData();

        //Grab only one file since this script disallows multiple file uploads.
        var file = files[0]; 

        //Check the file type.
        // input.files[0].type==='application/pdf'
        if (!file.type ==='application/pdf') {
            statusDiv.innerHTML = 'You cannot upload this file because it’s not a PDF.';
            return;
        }

        if (file.size >= 6000000 ) {
            statusDiv.innerHTML = 'You cannot upload this file because its size exceeds the maximum limit of 6 MB.';
            return;
        }

         // Add the file to the AJAX request.
        formData.append('uploadfile', file, file.name);
        // Add the file title value
        formData.append('title', fileTitle.value);
        // Add the file title value
        formData.append('description', fileDescription.value);

        // Set up the request.
        var xhr = new XMLHttpRequest();

        // Open the connection.
        xhr.open('POST', uploadUrl + '?_token='+ K9homes.csrfToken, true);
    

        // Set up a handler for when the task for the request is complete.
        xhr.onload = function (data) {
            console.log(xhr.response);
            var resObj = JSON.parse(xhr.response);
            console.log(resObj);
           
          if (xhr.status === 200) {
            statusDiv.innerHTML = 'Your upload is successful..';
          } else {
            statusDiv.innerHTML = 'An error occurred during the upload. Try again.';
          }
          if(!resObj.error){
            location.reload(true);
          }
          statusDiv.innerHTML = 'An error occurred during the upload: ' + resObj.error ;
            
        
        };

        // Send the data.
        xhr.send(formData);
    }
})();

</script>
@endsection
