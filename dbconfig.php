<?php
error_reporting(0); // Turn off all error reporting

$host = "localhost";
$dbUsername = "DB_USR_NAME";
$dbPassword = "DB_PSWD";
$dbName = "DB_NAME";


$con = mysql_pconnect($host, $dbUsername, $dbPassword);
if (!$con) {
    die('Could not Connect' . mysql_error());
}
mysql_select_db($dbName, $con);

function get_OEM(){
	$get_OEM = "SELECT oem,head_unit_type, count(*) as cnt  FROM sbd  group by head_unit_type,oem ORDER BY oem,head_unit_type ASC";
	$res_OEM = mysql_query($get_OEM);
	$oem_array = $unit_type_arr = array();
	$oem = array();
	$result = array();
	while($row_OEM = mysql_fetch_array($res_OEM))
	{
		if(!in_array($row_OEM['oem'],$oem_array))
			array_push($oem_array,$row_OEM['oem']);

		if(!in_array($row_OEM['head_unit_type'],$unit_type_arr))
			array_push($unit_type_arr,$row_OEM['head_unit_type']);

		$oem[$row_OEM['oem']][$row_OEM['head_unit_type']] = $row_OEM['cnt'];
	}
	$result['oem'] = $oem;
	$result['oem_arr'] = $oem_array;
	$result['unit_type_arr'] = $unit_type_arr;
	return $result;
}

?>
