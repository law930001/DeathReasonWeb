<?php
//get year and set yearcode
$ye = $_POST["ye"];
if($ye<=92)
    $yearcode1=1;
else if($ye<=96)
    $yearcode1=2;
else if($ye<=99)
    $yearcode1=3;
else if($ye>=100)
    $yearcode1=4;
if($ye<=86)
    $yearcode2=1;
else if($ye>86)
    $yearcode2=2;
// check mysql
if ( !( $database = mysql_connect( "localhost", "root", "l1a2w3" ) ) ) // connect database
    die( "Could not connect to database </body></html>" );
mysql_query( "SET NAMES ‘UTF8′");
mysql_query( "SET CHARACTER_SET_CLIENT='utf8'");
mysql_query( "SET CHARACTER_SET_RESULTS='utf8'");
if ( !mysql_select_db( "death", $database ) )                          // open database
    die( "Could not open products database </body></html>" );
if ( !mysql_query(  "SELECT * FROM ageTable", $database ) ||
     !mysql_query(  "SELECT * FROM causeTable", $database ) ||
     !mysql_query(  "SELECT * FROM countryTable", $database )  )              // query
{
    print( "<p>Could not execute query!</p>" );
    die( mysql_error() . "</body></html>" );
}
// check end
//query
$country = mysql_query(  "SELECT * FROM `countryTable` WHERE `yearcode`=".$yearcode1, $database );
$cause   = mysql_query(  "SELECT * FROM `causeTable` WHERE `yearcode`=".$yearcode2, $database );
$age     = mysql_query(  "SELECT * FROM `ageTable`", $database );
//get data from csv
$filename = "dead".$ye.".txt";
$data = file_get_contents('../death/'.$filename);
$rows = explode("\n",$data);
$s = array();
$num=0;
$count=1;
foreach($rows as $row) {
    $s[] = str_getcsv($row);
    $num++;
}
//end pre
//calculate male, female

for($a=0;$a<44;$a++){ // initialize array
  for($b=0;$b<27;$b++){ 
    $male[$a][$b]=0;   // cause, age
    $female[$a][$b]=0;
  }
}
    for ($i=1;$i<$num-1;$i++){  // cal
      if($s[$i][3]==1){ // male
        $male[intval($s[$i][2])][intval($s[$i][4])]+=$s[$i][5];
      }
      else{ // female
        $female[intval($s[$i][2])][intval($s[$i][4])]+=$s[$i][5];
      }
    }

//end calculate
//output
print("<input id='refresh' type='button' value='refresh'></input>");
print("
<script type='text/javascript'>
      google.charts.load('current', {'packages':['treemap']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'ID');
      data.addColumn('string', 'Parent');
      data.addColumn('number', 'Number Of Lines');
      data.addRows([
        ['Cause', null, 0],");  
//tree start
//cause, age, sex
$causeNum=0;
while ( $causerow = mysql_fetch_row( $cause ) ){ // cause
  $causetotal=0;
  for($a=0;$a<27;$a++)
    $causetotal+=$male[$causeNum][$a]+$female[$causeNum][$a];
  print("['".$causetotal."人".$causerow[2]."','Cause',null],");
  $ageNum=0;
  while( $agerow = mysql_fetch_row( $age )){ // age
    $agetotal=$male[$causeNum][$ageNum]+$female[$causeNum][$ageNum];
    print("['".$agetotal."人".$agerow[1]."(c".$causeNum.")','".$causetotal."人".$causerow[2]."',null],");
    // sex
    print("['".$male[$causeNum][$ageNum]."男(c".$causeNum.")(a".$ageNum.")','".$agetotal."人".$agerow[1]."(c".$causeNum.")',".$male[$causeNum][$ageNum]."],");
    print("['".$female[$causeNum][$ageNum]."女(c".$causeNum.")(a".$ageNum.")','".$agetotal."人".$agerow[1]."(c".$causeNum.")',".$female[$causeNum][$ageNum]."],");
    $ageNum++;
  }
  $age = mysql_query(  "SELECT * FROM `ageTable`", $database );
  $causeNum++;
}
print("['end', 'Cause', 0]");
//tree end

print("   ]);
      var tree = new google.visualization.TreeMap(document.getElementById('right'));

      var options = {
        highlightOnMouseOver: true,
        maxDepth: 1,
        maxPostDepth: 2,
        minHighlightColor: '#8c6bb1',
        midHighlightColor: '#9ebcda',
        maxHighlightColor: '#edf8fb',
        minColor: '#009688',
        midColor: '#f7f7f7',
        maxColor: '#ee8100',
        headerHeight: 15,
        showScale: true,
        height: 760,
        useWeightedAverageForAggregation: true
      };

        tree.draw(data, options);

      }    </script>"); 
mysql_close( $database ); 
?>











