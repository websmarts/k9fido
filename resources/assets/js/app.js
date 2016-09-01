$( function() {

	$.ajaxSetup({
	    headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    }
	});

	// Sortable for type images
    $( "#sortable" ).sortable({

	    axis: 'y',
	    update: function (event, ui) {
	        var data = $(this).sortable('serialize');

	        console.log(data);

	        // POST to server using $.post or $.ajax
	        $.post('/ajax/image/sort', data);
	    }

    });

    // setup delete click handler to delete images
    $('#sortable li a').on('click', function(e){
    	var imageId = $(e.target).closest('li').attr('id').substring(5,20) 
    	

    	$.post('/ajax/image/delete/' + imageId, function(data) {
    		if (data > 0){
    			$('#item-'+ data).remove(); // remove the deleted item
    		}
    		
    	});
    	

    })





    $( "#sortable" ).disableSelection();


    // flash message fader
    $('.Alert').delay( 3000 ).fadeOut( 400 );
});