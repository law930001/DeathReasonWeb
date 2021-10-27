//table
function progress() {
      var ye = $("#year").val();
      if(ye!='-'){
            $.ajax({
          url: "php/right.php",
          data: {
             ye: ye,
                },
          type: "POST",
          datatype: "html",
                success: function( output ) {
             $( "#right" ).html(output);
                },
          error : function(){
              alert( "Request failed." );
          }
            });
      }
      else{
        document.getElementById("right").innerHTML = "";
      }
  }
  //load
function load() {
  document.getElementById('refresh').innerHTML="<input type='button' value='refresh' onclick='javascript:progress()'>";
  var ye = $("#year").val();
  if(ye!='-'){
    $.ajax({
      url: "php/righttop.php",
      data: {
         ye: ye
      },
      type: "POST",
      datatype: "html",
      success: function( output ) {
         $( "#righttop" ).html(output);
      },
      error : function(){
          alert( "Request failed." );
      }
    });
  }
  else{
    document.getElementById("righttop").innerHTML = "";
    document.getElementById("refresh").innerHTML = "";    
  }
}
