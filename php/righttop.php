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
$cause   = mysql_query(  "SELECT * FROM `causeTable` WHERE `yearcode`=".$yearcode2, $database );
//cause
print("<p>Cause: <select id = 'cause' onchange='javascript:markdata()'>"); 
print("<option>-</option>");
while ( $row = mysql_fetch_row( $cause ) ){
    print("<option>".$row[2]."</option>");
}
print("</select></p>");
mysql_close( $database );
?>











