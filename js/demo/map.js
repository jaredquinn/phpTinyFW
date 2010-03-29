var MyMapper = {

  baseIcon: null,
  map: null,
  /* Set default Map settings for Icons and Map */ 
  setDefaults: function() {
	MyMapper.baseIcon.iconSize = new GSize(20,34);
	MyMapper.baseIcon.shadow = '/img/shadow.png';
	MyMapper.baseIcon.shadowSize = new GSize(37, 34);
	MyMapper.baseIcon.iconAnchor = new GPoint(9, 34);
	MyMapper.baseIcon.infoWindowAnchor = new GPoint(9, 2);
	
 	MyMapper.map.setCenter(new GLatLng(37.4419, -122.1419), 13);
        MyMapper.map.setUIToDefault();
	MyMapper.map.setZoom(2);
	MyMapper.map.setMapType(G_PHYSICAL_MAP);
  },
  /* Plot a Marker on the map */
  plotMarker: function(obj) {
	   var icon = new GIcon(MyMapper.baseIcon);
	   icon.image = '/img/marker' + Math.floor(obj.mag) + '.png';

	   var markerOptions = { icon: icon, title: obj.desc };
	   var marker = new GMarker(new GLatLng(obj.points[0], obj.points[1]), markerOptions);

	   marker.bindInfoWindowHtml('<div class="info">' + 
		'<h3>'+obj.title + '</h3>' + 
		obj.content + 
		'<strong>Magnitude: </strong>' + obj.mag +
		'<div style="clear: both;"></div></div>'
	   );

	   MyMapper.map.addOverlay(marker);
  },
  /* Process the XML object into something useful for us */
  entryToObject: function(entry) {
	   var info = $('title', entry).text().split(',');
	   var obj ={
		points: $('point', entry).text().split(' '),
		when: new Date(),
		title: info[1],
		mag: info[0].replace(/M (.+)/, "$1"),
		content: $('summary', entry).text(),
		desc: info[1]+' (Mag '+this.mag+')'
	   };
	   return obj;
  }
};

$(document).ready(function() {
	MyMapper.baseIcon = new GIcon(G_DEFAULT_ICON);
	MyMapper.map= new GMap2( document.getElementById('demomap') );

        MyMapper.setDefaults();

	$.ajax({ url: '/demo/map/feed',
		 data: { },
		 dataType: 'xml',
		 success: function(d,s,x) {
			$( d ).find('entry').each(function() {
			   var entry = $( this );	
			   var obj = MyMapper.entryToObject( entry );
			   MyMapper.plotMarker(obj);
			}); 
		 }
	});



});

$(document).unload(function() {
	GUnload();
});


