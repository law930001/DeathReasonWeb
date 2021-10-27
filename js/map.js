
var markers=[];
var infowindows = [];

$(function(){
  var map = new google.maps.Map(document.getElementById('taiwanmap'), {
      center: {lat: 23.5657812, lng:121.0240305 },
      zoom: 8}
      );
});

function markdata() {
  var ca = $("#cause").val();
   var ye = $("#year").val();
  if(ca!='-'){
    $.ajax({
      url: "php/mapmark.php",
      data: {
         ca: ca,
         ye: ye
      },
      type: "POST",
      datatype: "html",
      success: function( output ) {
      	makemark( output);
      },
      error : function(){
          alert( "Request failed." );
      }
    });
  }
  else{
  	for (var i = 0; i < markers.length; i++) 
    	markers[i].setMap(null);
  	markers= [];
  }
}

function makemark(output) {
  var map = new google.maps.Map(document.getElementById('taiwanmap'), {
	      center: {lat: 23.5657812, lng:121.0240305 },
	      zoom: 8}
	);

  var line = output.split("\n");
  var line_num = line[0];

  for (var i = 1; i<=line_num; i++)
  {
    var seq = line[i].split(" "); //seq[0]: x, seq[1] : y, seq[2] : total
  	//addmarker
    if(seq[2]>0){
  	var myCenter=new google.maps.LatLng(seq[0],seq[1]);
  	var marker=new google.maps.Marker({
  	    position:myCenter,
  	    map:map
  	});
  	//addinfo
  	var infowindow = new google.maps.InfoWindow({
  	    content: seq[2]+"人"
  	});
  	markers.push(marker);
  	infowindows.push(infowindow);
    infowindow.open(map,marker);
  //  google.maps.event.addListener(marker,'click', function() {infowindow.open(map,marker);});
  //  google.maps.event.addListener(map,'click', function() {infowindow.close();});
    }
  }
}




