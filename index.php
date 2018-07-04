<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<title>SBD Task</title>
		

	  	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	  	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">

	  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	  	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	  	<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
	  	<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
	  	
	  	<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
	   	<link href="https://fonts.googleapis.com/css?family=Montserrat:600" rel="stylesheet">

	   	<script src="https://code.highcharts.com/highcharts.js"></script>
		<script src="https://code.highcharts.com/modules/exporting.js"></script>
		<script src="https://code.highcharts.com/modules/export-data.js"></script>

	  <style type="text/css">
	  	p {
		    text-align: center;
		    font-size: 32px;
		    color: #fff;
		    background-color: #0a85ce;
		    padding: 22px;
		    font-weight: 600;
		}
		body{
			font-family: 'Montserrat', sans-serif !important;
			/*font-family: 'Lato';*/
			font-weight: 100;
		}
		.upload_dataset_btn{
			margin-top: 10px;
			/*margin-bottom: 10px;*/
		}
		#oem_val{
			margin: 20px 0px 20px 0px;
		}


	  </style>

	
<?php
include_once "dbconfig.php";
require_once 'Excel/reader.php';

if(isset($_POST['submitc']))
{
	$file = $_FILES['excelf'];
	$filename = $file['name']; 
	$ext = substr($filename, strrpos($filename, '.') + 1);
	if(strcmp($ext,"xls")==0)
	{
		$data_cnt=0;
		$q=$_FILES["excelf"]["name"];
		//storing the file in the server
		move_uploaded_file($_FILES["excelf"]["tmp_name"],$_FILES["excelf"]["name"]);
		$data = new Spreadsheet_Excel_Reader();

		$data->setOutputEncoding('CP1251');

		$data->read("$q");

		error_reporting(E_ALL ^ E_NOTICE);

		mysql_query('SET AUTOCOMMIT=0');
		mysql_query('START TRANSACTION');

		for ($i = 3; $i <= $data->sheets[0]['numRows']; $i++)
		{
			$count=null;
			$oem=str_replace('\n', ' ',trim($data->sheets[0]['cells'][$i][1]),$count);
			$model=trim($data->sheets[0]['cells'][$i][2]);
			$segment=trim($data->sheets[0]['cells'][$i][3]);
			$variant=str_replace('\n', ' ',trim($data->sheets[0]['cells'][$i][4]));
			$headunits=str_replace('\n', ' ',trim($data->sheets[0]['cells'][$i][5]));

			$head_unit_type=str_replace('\n', ' ',trim($data->sheets[0]['cells'][$i][6]),$count);
			$standard=(trim($data->sheets[0]['cells'][$i][7])=='Y')?1:0;
			
			$stand_alone = (trim($data->sheets[0]['cells'][$i][8])=='Y')?1:0;

            $pack = str_replace(' ', '',trim($data->sheets[0]['cells'][$i][9]));
			$central_controller = (trim($data->sheets[0]['cells'][$i][10])=='Y')?1:0;
			$touch_screen = (trim($data->sheets[0]['cells'][$i][11])=='Y')?1:0;
			$hw_recogn = (trim($data->sheets[0]['cells'][$i][12])=='Y')?1:0;
			
			$proxy = (trim($data->sheets[0]['cells'][$i][13])=='Y')?1:0;

			$car_play = (trim($data->sheets[0]['cells'][$i][14])=='Y')?1:0;
			$andriod_auto = (trim($data->sheets[0]['cells'][$i][15])=='Y')?1:0;

			$insert_data = 'INSERT INTO sbd( oem, model, segment, variant, head_units, head_unit_type, standard, stand_alone, pack, central_controller, touch_screen, hw_recogn, proxy, carplay, andriod_auto) VALUES ("'.$oem.'","'.$model.'","'.$segment.'","'.$variant.'","'.$headunits.'","'.$head_unit_type.'","'.$standard.'","'.$stand_alone.'","'.$pack.'","'.$central_controller.'","'.$touch_screen.'","'.$hw_recogn.'","'.$proxy.'","'.$car_play.'","'.$andriod_auto.'")';
			
			$result = mysql_query($insert_data);
			$data_cnt++;
		}

		mysql_query('SET AUTOCOMMIT=1');

		echo "<center> <font color='green'>".$data_cnt."</font> Dataset added Successfully .</center><br>";
	}
	else
	 {
	 	echo "<script type='text/javascript'>alert('Please upload .xls file only');</script>";					
	 }
} 
?>
<script type="text/javascript" src="js/app.js"></script>

</head>
<body>
	<div class="head">
			<p>SBD Task</p>
	</div>
	<div class="container">

	<!-- tabs -->
	<ul class="nav nav-tabs">
		<li id="dataset_tab" class="active"><a href="#upload" data-toggle="tab">Upload DataSet</a>
		</li>
		<li id="oem_tab"><a href="#oem" data-toggle="tab">OEM</a>
		</li>
		<li id="oem_models_tab"><a href="#oem_models" data-toggle="tab">OEM Models</a>
		</li>
	</ul>
	<div class="tab-content ">
			<div class="tab-pane active" id="upload">
				
				<button type="button" class="btn btn-primary  pull-right upload_dataset_btn" data-toggle="modal" data-target=".upload_dataset">Upload Dataset</button>
				<div id="data_set">
					
				</div>
				<div class="modal fade upload_dataset" id="triptableManager" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
					<div class="modal-dialog modal-lg" role="document">
						<div class="modal-content">
							
							<div class="modal-header">
								 <button type="button" class="close col-md-1 col-md-offset-11" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h3 class="modal-title">Upload DataSet:</h3>
							</div>
							<div class="modal-body">
								<div id="editContent">
									<div class="form-group">
									
										<form id="formID" enctype="multipart/form-data" class="form" name="form1" method="post" action="#" >
											<label for='upload_dataset'>Select Excel File:</label>

											<input type="file"  class="form-control" maxlength="3" id="excelfile" name="excelf" size='10' required="required" />

											<br>
											<font color='green' size='4'>  .XLS (Excel 2003 File) only ! </font>
											<br><br>
											<input class="submitc form-control btn btn-primary" name="submitc" type="submit" value="Upload Data" />
										</form>
									</div>
									
								</div>

								<div id="showContent" style="display: none;">
								</div>
							</div>
							

						</div>
					</div>
				</div>
				
			</div>
			<div class="tab-pane" id="oem">
				<div id="data_oem">
					<select  name='oem_val' id='oem_val' class='col-md-3 form-control' >
					</select>
				</div>
				<div id="data_oem_model"></div>
				<?
					$get_oem = get_OEM();
					$oem_arr = $get_oem['oem_arr'];
					$unit_type_string = "'".implode("','",$get_oem['unit_type_arr'])."'";

					$oem_string = implode(',',$oem_arr);

					$series_data = array();
					foreach ($oem_arr as $key) {
						array_push($series_data, implode(',', $get_oem['oem'][$key]));
					}

				?>
				<div id="container" class="container" style="width:750px;height:350px;"></div>
				<script type="text/javascript">
		        $(function () {
		            var chart;
		            $(document).ready(function() {
		                chart = new Highcharts.Chart({
		                    chart: {
		                        renderTo: 'container',
		                        type: 'column'
		                    },
		                    exporting:{
		                        enabled: false
		                    },
		                    credits:{
		                        enabled: false
		                    },
		                    title: {
		                        text: 'OverAll Head Unit Type Distribution '
		                    },
		                    
		                    xAxis: {
		                        categories: [<?php echo $unit_type_string; ?>]
		                    },
		                    yAxis: {
		                        min: 0,
		                        title: {
		                            text: 'Count'
		                        }
		                    },
		                    legend: {
		                        layout: 'vertical',
		                        backgroundColor: '#FFFFFF',
		                        align: 'right',
		                        verticalAlign: 'top',
		                        x: 0,
		                        y: 0,
		                        floating: true,
		                        shadow: true
		                    },
		                    tooltip: {
		                        formatter: function() {
		                            return ''+
		                                this.x +': '+ this.y ;
		                        }
		                    },
		                    plotOptions: {
		                        column: {
		                            pointPadding: 0.2,
		                            borderWidth: 0
		                        }
		                    },
		                    series: [
		                    <?
		                    foreach ($oem_arr as $key => $value) 
		                    { 
		                    ?>
		                    	{
			                        name: '<? echo $value;?>',
			                        data: [<? echo $series_data[$key];?>]
			                    },

		                    <?
		                    }
		                    ?>
		                    
		                    ],
		                    colors: ['#ffa812','#3D96AE','#DB843D','#92A8CD','#A47D7C','#B5CA92'],
		                   
		                });
		            });

		        });
		    </script>
    
			</div>
			<div class="tab-pane" id="oem_models">
				<div id="oem_model_data"></div>
			</div>
	</div>

	</div>

	
	</body>
</html>
