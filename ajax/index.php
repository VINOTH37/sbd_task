<?php
include_once "../dbconfig.php";
$action  = $_REQUEST['action'];
if($action=='getall')
{
$sql="SELECT oem, model, segment, variant, head_units, head_unit_type, 
CASE WHEN standard =1 THEN 'YES' ELSE 'NO' END as standard,
CASE WHEN stand_alone =1 THEN 'YES' ELSE 'NO' END as stand_alone, 
pack, 
CASE WHEN central_controller =1 THEN 'YES' ELSE 'NO' END as central_controller,
CASE WHEN touch_screen =1 THEN 'YES' ELSE 'NO' END as touch_screen,
CASE WHEN hw_recogn =1 THEN 'YES' ELSE 'NO' END as hw_recogn,
CASE WHEN proxy =1 THEN 'YES' ELSE 'NO' END as proxy,
CASE WHEN carplay =1 THEN 'YES' ELSE 'NO' END as carplay,
CASE WHEN andriod_auto =1 THEN 'YES' ELSE 'NO' END as andriod_auto FROM sbd";
$res= mysql_query($sql);

	if($res)
	{
  		$result_array=array();
  		while($row = mysql_fetch_assoc($res)) 
  		{

       		array_push($result_array,$row);
    	}
  		$result['error'] = 0;
  		$result['value']  = $result_array;
	}
	else 
	{
			$result['error'] = 1;
    }
		echo json_encode($result);
}
else if($action=='getOEM')
{
	$sql="select distinct oem as oem from sbd";
	$res= mysql_query($sql);
	if($res)
	{
		$result_array=array();
		while($row = mysql_fetch_assoc($res)) 
		{
			array_push($result_array,$row);
		}
		$result['error'] = 0;
		$result['value']  = $result_array;
	}
	else 
	{
		$result['error'] = 1;
	}
	echo json_encode($result);
}

else if($action=='getOEM_Model')
{
	$oem = $_REQUEST['oem'];
	$sql="SELECT oem,head_unit_type, count(*) as cnt  FROM sbd where oem like '".$oem."'  group by head_unit_type,oem ORDER BY oem ASC";
	$res= mysql_query($sql);
	if($res)
	{
		$result_array=array();
		while($row = mysql_fetch_assoc($res)) 
		{
			array_push($result_array,$row);
		}
		$result['error'] = 0;
		$result['value']  = $result_array;
	}
	else 
	{
		$result['error'] = 1;
	}
	echo json_encode($result);
}

?>