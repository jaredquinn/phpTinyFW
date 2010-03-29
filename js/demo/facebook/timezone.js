
$(function() {

 FB_RequireFeatures(["Connect", "Common"], function() {
     FB.init("bdda66611c0e3f70be0f948a6445d64c");
     FB.Connect.requireSession(function() {
		FB.Facebook.apiClient.fql_query('SELECT uid,first_name,last_name,name,pic_small,pic_big,pic_square,pic,religion,birthday,sex,username,website,timezone FROM user WHERE uid = '+ FB.Connect.get_loggedInUser(),
		  function(response) {
			console.log(response);
		  }
		);
     }, function() {
	$('#content').html('This demo requires a Facebook session');
     });
  });

});

