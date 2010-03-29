
$(function() {

window.Photos = new Array;

 FB_RequireFeatures(["Connect", "Common"], function() {
     FB.init("bdda66611c0e3f70be0f948a6445d64c");
     FB.Connect.requireSession(function() {
		$('#status').text('Loading List of Albums...');
		FB.Facebook.apiClient.fql_query('SELECT aid, cover_pid, owner, name, created, modified, description, location, link, size, visible FROM album WHERE owner=' + FB.Connect.get_loggedInUser(),
		  function(response){
			for(var i in response) {
				try {
				album = response[i];
				ob = $('<option></option').attr('value', album.aid).text(album.name + ' (' + album.size + ' pics)');
				$('#albums').append(ob);
				} catch(err) {
						// silently ignore ones we can't add.
				}
				
			}
			$('#albums').show();
		  }
		);

		PopulatePictures('me');	
		changePhoto();
     }, function() {
	$('#image').hide();
	$('#caption').html('This demo requires a Facebook session');
     });
 });

$('#albums').change(function() {
	PopulatePictures($(this).val());
});

function PopulatePictures(val) {
     FB.Connect.requireSession(function() {
	$('#status').text('Loading Data from Album...');
	if(val == 'me') {
	   FB.Facebook.apiClient.fql_query('SELECT pid, aid, owner, src, src_big, src_small, link, caption, created FROM photo WHERE pid IN (SELECT pid FROM photo_tag WHERE subject=' + FB.Connect.get_loggedInUser() + ')',
	   function(response) { window.NewPhotos = response; });
	} else {
	  FB.Facebook.apiClient.fql_query('SELECT pid, aid, owner, src, src_big, src_small, link, caption, created FROM photo WHERE aid = ' + val, function(response) { window.NewPhotos = response; });
     	}
    });
}


function changePhoto() {

	if(window.NewPhotos != null) { 
		window.Photos = window.NewPhotos; 
		window.NewPhotos = null; 
		$('#next-photo').attr('src', '');
		$('#next-caption').html('');
		$('#status').text(''); 
	}

	var photo = window.Photos[Math.floor(Math.random()*window.Photos.length)]

	if(photo) {

		if($('#next-photo').attr('src') == '') {
			$('#next-photo').attr('src', photo.src_big);
			$('#next-caption').html(photo.caption);
			photo = window.Photos[Math.floor(Math.random()*window.Photos.length)]
		}

		$('#photo').attr('src', $('#next-photo').attr('src'));
		$('#caption').html( $('#next-caption').html() );

		$('#next-photo').attr('src', photo.src_big);
		$('#next-caption').html(photo.caption);
	}


	window.setTimeout(function() {
		changePhoto();
	}, 5000);

}


});
