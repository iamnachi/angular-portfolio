<?php 
error_reporting(1);

$current = date('F Y');


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

    <script src="js/jquery.min.js"></script>
</head>

<body>
    <div class="container">
        <div class="time-navigation">
            <span class="navi-icon navi-prev" onclick="prevmonth();">❮</span>
            <span class="navi-time"><?php echo $current; ?></span>
            <span class="navi-icon navi-next" onclick="nextmonth();">❯</span>
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
                        $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                        $url = $actual_link."data/attendance.json";
                        
                        $str = url_get_contents($url);
                        $array = json_decode($str);
                        print_r($array);
                        ?>
                        <td></td>
                        <td></td>
                        <?php
                        for($i = 1; $i <=  date('t'); $i++)
                        {
                            $date = date('Y') . "-" . date('m') . "-" . str_pad($i, 2, '0', STR_PAD_LEFT);
                            $day_num = date('d', strtotime($date));
                            $day_name = date('D', strtotime($date));
                    
                            // add the date to the dates array
                            if($day_name == 'Sat' || $day_name=='Sun')
                            echo "<td class='color-1'>W</td>";
                            else
                            echo "<td ></td>";
                            
                        }
                        ?>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="timetable-example">
            <div class="tiva-timetable" data-view="week" data-mode="day" id="timetable-1">
                <div class="timetable-week show-time">
                    <div class="timetable-axis">
                        <div class="axis-item">Arulvoli</div>
                        <div class="axis-item">Arun</div>
                        <div class="axis-item">11:00</div>
                        <div class="axis-item">12:00</div>
                        <div class="axis-item">13:00</div>
                        <div class="axis-item">14:00</div>
                        <div class="axis-item">15:00</div>
                        <div class="axis-item">16:00</div>
                        <div class="axis-item">17:00</div>
                        <div class="axis-item">18:00</div>
                        <div class="axis-item">19:00</div>
                    </div>
                    <div class="timetable-columns">
                        <div class="timetable-column">
                            <div class="timetable-column-header ">Sunday
                                <br>
                                <span>Feb 25, 2018</span>
                            </div>
                            <div class="timetable-column-content">
                                <div class="timetable-item">
                                    <a class="timetable-title color-4" style="top:27px; height:82px; " href="#timetable-1-popup-0">
                                        <div class="timetable-title-wrap">
                                            <div class="timetable-name">Roger Hodgson</div>
                                            <div class="timetable-time">09:30 - 11:00</div>
                                        </div>
                                    </a>
                                </div>
                                <div class="timetable-item">
                                    <a class="timetable-title color-1" style="top:330px; height:137px; " href="#timetable-1-popup-10">
                                        <div class="timetable-title-wrap">
                                            <div class="timetable-name">Musiq SoulChild - Grown &amp; Sexy 16</div>
                                            <div class="timetable-time">15:00 - 17:30</div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="timetable-column-grid">
                                <div class="grid-item first-column"></div>
                                <div class="grid-item first-column"></div>
                                <div class="grid-item first-column"></div>
                                <div class="grid-item first-column"></div>
                                <div class="grid-item first-column"></div>
                                <div class="grid-item first-column"></div>
                                <div class="grid-item first-column"></div>
                                <div class="grid-item first-column"></div>
                                <div class="grid-item first-column"></div>
                                <div class="grid-item first-column"></div>
                            </div>
                        </div>
                        <div class="timetable-column">
                            <div class="timetable-column-header ">Monday
                                <br>
                                <span>Feb 26, 2018</span>
                            </div>
                            <div class="timetable-column-content">
                                <div class="timetable-item">
                                    <a class="timetable-title color-2" style="top:247px; height:137px; " href="#timetable-1-popup-7">
                                        <div class="timetable-title-wrap">
                                            <div class="timetable-name">Festival of Praise Tour</div>
                                            <div class="timetable-time">13:30 - 16:00</div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="timetable-column-grid">
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                            </div>
                        </div>
                        <div class="timetable-column">
                            <div class="timetable-column-header ">Tuesday
                                <br>
                                <span>Feb 27, 2018</span>
                            </div>
                            <div class="timetable-column-content">
                                <div class="timetable-item">
                                    <a class="timetable-title color-1" style="top:55px; height:82px; " href="#timetable-1-popup-1">
                                        <div class="timetable-title-wrap">
                                            <div class="timetable-name">Musiq SoulChild - Grown &amp; Sexy 16</div>
                                            <div class="timetable-time">10:00 - 11:30</div>
                                        </div>
                                    </a>
                                </div>
                                <div class="timetable-item">
                                    <a class="timetable-title color-3" style="top:330px; height:165px; width:50%;" href="#timetable-1-popup-8">
                                        <div class="timetable-title-wrap">
                                            <div class="timetable-name">Lucha Libre</div>
                                            <div class="timetable-time">15:00 - 18:00</div>
                                        </div>
                                    </a>
                                </div>
                                <div class="timetable-item">
                                    <a class="timetable-title color-4" style="top:330px; height:165px; width:50%;left:50%" href="#timetable-1-popup-9">
                                        <div class="timetable-title-wrap">
                                            <div class="timetable-name">Roger Hodgson</div>
                                            <div class="timetable-time">15:00 - 18:00</div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="timetable-column-grid">
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                            </div>
                        </div>
                        <div class="timetable-column">
                            <div class="timetable-column-header ">Wednesday
                                <br>
                                <span>Feb 28, 2018</span>
                            </div>
                            <div class="timetable-column-content">
                                <div class="timetable-item">
                                    <a class="timetable-title color-3" style="top:110px; height:55px; " href="#timetable-1-popup-3">
                                        <div class="timetable-title-wrap">
                                            <div class="timetable-name">Roger Hodgson</div>
                                            <div class="timetable-time">11:00 - 12:00</div>
                                        </div>
                                    </a>
                                </div>
                                <div class="timetable-item">
                                    <a class="timetable-title color-1" style="top:220px; height:137px; " href="#timetable-1-popup-6">
                                        <div class="timetable-title-wrap">
                                            <div class="timetable-name">Musiq SoulChild - Grown &amp; Sexy 16</div>
                                            <div class="timetable-time">13:00 - 15:30</div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="timetable-column-grid">
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                            </div>
                        </div>
                        <div class="timetable-column">
                            <div class="timetable-column-header ">Thursday
                                <br>
                                <span>Mar 1, 2018</span>
                            </div>
                            <div class="timetable-column-content">
                                <div class="timetable-item">
                                    <a class="timetable-title color-2" style="top:165px; height:110px; " href="#timetable-1-popup-4">
                                        <div class="timetable-title-wrap">
                                            <div class="timetable-name">Lucha Libre</div>
                                            <div class="timetable-time">12:00 - 14:00</div>
                                        </div>
                                    </a>
                                </div>
                                <div class="timetable-item">
                                    <a class="timetable-title color-3" style="top:385px; height:165px; " href="#timetable-1-popup-12">
                                        <div class="timetable-title-wrap">
                                            <div class="timetable-name">Festival of Praise Tour</div>
                                            <div class="timetable-time">16:00 - 19:00</div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="timetable-column-grid">
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                            </div>
                        </div>
                        <div class="timetable-column">
                            <div class="timetable-column-header ">Friday
                                <br>
                                <span>Mar 2, 2018</span>
                            </div>
                            <div class="timetable-column-content">
                                <div class="timetable-item">
                                    <a class="timetable-title color-2" style="top:73px; height:64px; " href="#timetable-1-popup-2">
                                        <div class="timetable-title-wrap">
                                            <div class="timetable-name">Roger Hodgson</div>
                                            <div class="timetable-time">10:20 - 11:30</div>
                                        </div>
                                    </a>
                                </div>
                                <div class="timetable-item">
                                    <a class="timetable-title color-4" style="top:330px; height:137px; " href="#timetable-1-popup-11">
                                        <div class="timetable-title-wrap">
                                            <div class="timetable-name">Roger Hodgson</div>
                                            <div class="timetable-time">15:00 - 17:30</div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="timetable-column-grid">
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                            </div>
                        </div>
                        <div class="timetable-column">
                            <div class="timetable-column-header last-column">Saturday
                                <br>
                                <span>Mar 3, 2018</span>
                            </div>
                            <div class="timetable-column-content">
                                <div class="timetable-item">
                                    <a class="timetable-title color-3" style="top:165px; height:220px; " href="#timetable-1-popup-5">
                                        <div class="timetable-title-wrap">
                                            <div class="timetable-name">Lucha Libre</div>
                                            <div class="timetable-time">12:00 - 16:00</div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="timetable-column-grid">
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                                <div class="grid-item "></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</body>

</html>