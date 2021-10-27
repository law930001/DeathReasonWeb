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
if($ye<100)
    $yearcode=1;
else
    $yearcode=4;
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
$coordinates = mysql_query(  "SELECT * FROM `coordinates` WHERE `yearcode`=".$yearcode, $database );
// query end
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
//cause code
$ca = $_POST["ca"];
while ( $row = mysql_fetch_row( $cause ) ){ // cause
    if($ca==$row[2]){
        $causecode=$row[1];
        break;
    }
}
// calculate total
$line=0;
while( $row = mysql_fetch_row( $coordinates)){
    $total[$row[1]]=0;
    $line++;
}
for ($i=1;$i<$num-1;$i++){  
    if($s[$i][2]==$causecode){
        $country = mysql_query(  "SELECT * FROM `countryTable` WHERE `yearcode`=".$yearcode1. " AND `country`=".$s[$i][1], $database );
        $row = mysql_fetch_row( $country ); 
        preg_match( "/([\x{4e00}-\x{9fff}]{3})/u" , $row[2], $match );
        $total[$match[1]]+=$s[$i][5];
    }
}
// output
$coordinates = mysql_query(  "SELECT * FROM `coordinates` WHERE `yearcode`=".$yearcode, $database );
print($line."\n");
foreach ($total as $key => $value) {
    $row = mysql_fetch_row( $coordinates);
    print($row[2]." ".$row[3]." ".$value."\n");
}
// end output
mysql_close( $database );
?>













