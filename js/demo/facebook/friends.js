jQuery.fn.swap = function(b) {
    b = jQuery(b)[0];
    var a = this[0];
    try {
    var a2 = a.cloneNode(true),
        b2 = b.cloneNode(true),
        stack = this;

    a.parentNode.replaceChild(b2, a);
    b.parentNode.replaceChild(a2, b);

    stack[0] = a2;
    return this.pushStack( stack );
    } catch(err) {
	//
    }
};

$(function() {

 FB_RequireFeatures(["Connect", "Common"], function() {
     FB.init("bdda66611c0e3f70be0f948a6445d64c");
     FB.Connect.requireSession(function() {
		$('#status').text('Loading List of Albums...');
		FB.Facebook.apiClient.fql_query('SELECT uid, name, pic_square FROM user WHERE uid IN ( SELECT uid2 FROM friend WHERE uid1 = ' + FB.Connect.get_loggedInUser() + ')',
		  function(response){
			for(var i in response) {
				try {
					friend = response[i];
					if(friend.pic_square != null) {
					$('<img/>').addClass('profile_pic').attr('src', friend.pic_square).attr('title', friend.name).appendTo($('#content'));
					}
				} catch(err) {
						// silently ignore ones we can't add.
				}
				
			}
			SwapTiles();
		  }
		);
     }, function() {
	$('#photo').hide();
	$('#caption').html('This demo requires a Facebook session');
     });
 });

});

function SwapTiles() {
	$all = $('.profile_pic');
	v1 = Math.floor(Math.random() * $all.length );
	v2 = Math.floor(Math.random() * $all.length );

	$all.eq(v1).swap($all.eq(v2));
	window.setTimeout(function() { SwapTiles(); }, 25);
}

	
