<?php 
error_reporting(1);

$current = date('F Y');
$current_date = date('Y')."-".date('m')."-01";
$current_month = date('m');
$current_year = date('Y');
$current_minimal_year = date('y');

function url_get_contents ($Url) {
    if (!function_exists('curl_init')){ 
        die('CURL is not installed!');
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $Url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}
?>
<!doctype html>
<html>
<head>
    <title>Calendar Demo</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/tagmanager.min.css">

    <script src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script src="js/attendance.js"></script>
    <script type="text/javascript" src="js/tagmanager.min.js"></script>
	<script src="js/bootstrap3-typeahead.min.js"></script>
</head>

<body>
    <div class="container">
        <div class="form-group" style="margin-top:20px;">
            <label>Search :</label><br>
            <input type="text" name="tags" placeholder="Search" class="typeahead tm-input form-control tm-input-info"/>
            <input type="hidden" name="hidden-tags"/>
            
        </div>    
        <div id ="attendance">
            <input type="hidden" name="current_month" id="current_month" value="<?php echo $current_month; ?>"/>
            <input type="hidden" name="current_year" id="current_year" value="<?php echo $current_year; ?>"/>
            
            <div class="time-navigation">
                <span class="navi-icon navi-prev" onclick="prevmonth('<?php echo $current_date; ?>');">❮</span>
                <span class="navi-time"><?php echo $current; ?></span>
                <span class="navi-icon navi-next" onclick="nextmonth('<?php echo $current_date; ?>');">❯</span>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Id </th>
                            <th>Name</th>
                            <?php
                            for($i = 1; $i <=  date('t'); $i++)
                            {
                                $date = date('Y') . "-" . date('m') . "-" . str_pad($i, 2, '0', STR_PAD_LEFT);
                                $day_num = date('d', strtotime($date));
                                $day_name = date('D', strtotime($date));
                        
                                // add the date to the dates array
                                echo "<th>". $day_name ." ". $day_num ."</th>";
                            }
                            ?>
                        </tr>    
                    </thead>
                    <tbody>
                        <tr>
                            <?php
                            $actual_link = "http://$_SERVER[HTTP_HOST]/calendar/";
                            $url = $actual_link."data/attendance_".$current_month."_".$current_minimal_year.".json";
                            $str = url_get_contents($url);
                            //echo $str;
                            $manage = (array) json_decode($str);
                            //print_r($manage);
                            $holidays = $manage['attendance']->holidays;
                            $students = $manage['attendance']->details;
                            //print_r($holidays);
                            //print_r($students);
                            if(count($students) == 0) {
                                echo 'The attendance details for the month '.$current.' is not available.';
                            }

                            foreach($students as $student) {
                                echo "<tr>";
                                echo "<td>".$student->id."</td>";
                                echo "<td>".$student->name."</td>";
                                $absent = $student->absent;
                                for($i = 1; $i <=  date('t'); $i++)
                                {
                                    $date = date('Y') . "-" . date('m') . "-" . str_pad($i, 2, '0', STR_PAD_LEFT);
                                    $day_num = date('d', strtotime($date));
                                    $day_name = date('D', strtotime($date));
                                    
                                    $date_check = str_pad($i, 2, '0', STR_PAD_LEFT) . "-" . date('m') . "-" . date('Y');    
                                
                                    // add the date to the dates array
                                    if($day_name == 'Sat' || $day_name=='Sun')
                                    echo "<td class='weekend'>W</td>";
                                    else if(in_array($date_check, $absent)) 
                                    echo "<td class='absent'>A</td>";
                                    else if(in_array($date_check, $holidays)) 
                                    echo "<td class='holidays'>H</td>";
                                    else
                                    echo "<td ></td>";
                                }
                                echo "</tr>";
                            }
                            ?>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- ajax loader -->
    </div>
</body>
<script type="text/javascript">
	$(document).ready(function() {
		var tagApi = $(".tm-input").tagsManager();

		jQuery(".typeahead").typeahead({
	      name: 'tags',
	      displayKey: 'name',
	      source: function (query, process) {
        		return $.get('data/students.json', { query: query }, function (data) {
        			// data = $.parseJSON(data);
	            	return process(data);
	        	});
    		},
    	  afterSelect :function (item){
    	  	tagApi.tagsManager("pushTag", item.name);
            //console.log( $('input[name="hidden-tags"]').val() );
            //search( $('input[name="hidden-tags"]').val(), $("#current_month").val(), $("#current_year").val() );
    	  }
	    });

        jQuery('input[name="hidden-tags"]').on('change', function() {
            //console.log( this.value );
            search( this.value, $("#current_month").val(), $("#current_year").val() );
        });
	});

    function search(value, currentMonth, currentYear) {
        $.get("search.php", { month: currentMonth, year: currentYear, search: value },
        function(data, status){
            if(status == "success") {
                //alert("Data: " + data + "\nStatus: " + status);
                $("#attendance").html(data);
            }
        });
    }
 </script>
</html>