<?php 
error_reporting(1);

//print_r($_GET);
$current = date('F Y', strtotime("01-".$_GET['month']."-".$_GET['year']));
//echo $current;
$current_date = $_GET['year']."-".$_GET['month']."-01";
$current_month = $_GET['month'];
$current_year = $_GET['year'];
$search = $_GET['search'];
$current_minimal_year = date('y', strtotime($current_date));

if($search != '') {
    $search = explode(",",$search);
    //print_r($search);
} else {
    $search = NULL;
}

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
                //echo $url;
                $str = url_get_contents($url);
                //echo "dff".$str."g";

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
                    
                    if($search == NULL ? true : in_array($student->name, $search)) 
                    {
                        echo "<tr>";
                        echo "<td>".$student->id."</td>";
                        echo "<td>".$student->name."</td>";
                        $absent = $student->absent;
                        //print_r($absent);
                        for($i = 1; $i <=  date('t', strtotime($current_date)); $i++)
                        {
                            $date = date('Y', strtotime($current_date)) . "-" . date('m', strtotime($current_date)) . "-" . str_pad($i, 2, '0', STR_PAD_LEFT);
                            $day_num = date('d', strtotime($date));
                            $day_name = date('D', strtotime($date));
                                    
                            $date_check = str_pad($i, 2, '0', STR_PAD_LEFT) . "-" . date('m', strtotime($current_date)) . "-" . date('Y', strtotime($current_date));    
                            //echo $date_check;
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
                }
                ?>
            </tr>
        </tbody>
        </table>
    </div>
   