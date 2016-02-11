<?php

class Dates
{


    public static function showTimeAgo($date)
    {
        if (empty($date)) {
            return "No date provided";
        }
        $periods = array("sec", "min", "hr", "day", "week", "month", "year", "decade");
        $lengths = array("60", "60", "24", "7", "4.35", "12", "10");
        $now = time();
        $unix_date = strtotime($date);
        // check validity of date
        if (empty($unix_date)) {
            return "Bad date";
        }
        // is it future date or past date
        if ($now > $unix_date) {
            $difference = $now - $unix_date;
            $tense = "ago";
        } else {
            $difference = $unix_date - $now;
            $tense = "from now";
        }
        for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
            $difference /= $lengths[$j];
        }
        $difference = round($difference);
        if ($difference != 1) {
            $periods[$j] .= "s";
        }
        return "$difference $periods[$j] {$tense}";
    }

    //-----------------------------------------------------------------
    public static function dateformat($datetime, $day = false, $format = NULL)
    {
        if ($datetime == "0000-00-00 00:00:00") {
            return false;
        }
        $unix_date = strtotime($datetime);
        // check validity of date
        if (empty($unix_date)) {
            return "Bad date";
        }
        if ($format != NULL) {
            $d = date_create($datetime);
            $formitted = date_format($d, $format);
            return $formitted;
        }
        $count = strlen($datetime);
        if ($count == 10) {
            $date = $datetime;
            $time = "";
        } else if ($count > 10) {
            $datetime_d = explode(" ", $datetime);
            $date = $datetime_d[0];
            if (isset($datetime_d[1])) {
                $time = $datetime_d[1];
            }
        }
        if ($day == true) {
            $format = "l jS F Y";
        }
        if ($format == NULL) {
            $format = "M j, Y";
        }
        if (isset($time) && $time != "") {
            $format .= " g:i:s A";
        }
        $d = date_create($datetime);
        $formitted = date_format($d, $format);
        return $formitted;
    }

    //-----------------------------------------------------------------
    public static function now()
    {
        return date('Y-m-d H:i:s');
    }
    //-----------------------------------------------------------------
    public static function getWorkingDays($startDate,$endDate,$holidays)
    {
        $debug = false;
        $work = 0;
        $nowork = 0;
        $dayx = strtotime($startDate);
        $endx = strtotime($endDate);
        if($debug){
            echo '<h1>get_working_days</h1>';
            echo 'startDate: '.date('r',strtotime( $startDate)).'<br>';
            echo 'endDate: '.date('r',strtotime( $endDate)).'<br>';
            var_dump($holidays);
            echo '<p>Go to work...';
        }


        $result = array();
        $result['working_days'] = "";

        while($dayx <= $endx){
            $day = date('N',$dayx);
            $date = date('Y-m-d',$dayx);



            if($debug)echo '<br />'.date('r',$dayx).' ';
            if($day > 6 || in_array($date, $holidays))
            {
                $nowork++;
                if($debug){
                    if($day > 6){
                        echo 'weekend';
                    }else
                    {
                        echo 'holiday';
                    }
                }
            } else
            {
                $work++;
                $result['working_days'][] = $date;
            }
            $dayx = strtotime($date.' +1 day');
        }
        if($debug){
            echo '<p>No work: '.$nowork.'<br>';
            echo 'Work: '.$work.'<br>';
            echo 'Work + no work: '.($nowork+$work).'<br>';
            echo 'All seconds / seconds in a day: '.floatval(strtotime($endDate)-strtotime($startDate))/floatval(24*60*60);
        }

        //return $work;
        return $result;
    }
    //-----------------------------------------------------------------
    function getFirstAndLastDay($month, $year)
    {
        $first = date('Y-m-d', mktime(0, 0, 0, $month, 1, $year));
        $last = date('Y-m-t', mktime(0, 0, 0, $month, 1, $year));

        $result['first'] = $first;
        $result['last'] = $last;

        return $result;
    }
    //-----------------------------------------------------------------
    public static function countDaysBetweenDates($start, $end)
    {
        $start_ts = strtotime($start);
        $end_ts = strtotime($end);
        $diff = $end_ts - $start_ts;
        return round($diff / 86400);

    }
    //-----------------------------------------------------------------
    public static function getDaysBetweenDates($start, $end)
    {
        // Vars
        $day = 86400; // Day in seconds
        $format = 'Y-m-d'; // Output format (see PHP date funciton)
        $sTime = strtotime($start); // Start as time
        $eTime = strtotime($end); // End as time
        $numDays = round(($eTime - $sTime) / $day) + 1;
        $days = array();

        // Get days
        for ($d = 0; $d < $numDays; $d++) {
            $days[] = date($format, ($sTime + ($d * $day)));
        }

        // Return days
        return $days;
    }

    //-----------------------------------------------------------------
    public static function compareDates($date1,$date2)
    {
        $date1_array = explode("-",$date1);
        $date2_array = explode("-",$date2);
        $timestamp1 =
            mktime(0,0,0,$date1_array[1],$date1_array[2],$date1_array[0]);
        $timestamp2 =
            mktime(0,0,0,$date2_array[1],$date2_array[2],$date2_array[0]);
        if ($timestamp1>$timestamp2) {
            //print "The second date is earlier than the first.";
            return 1;
        } else if ($timestamp1<$timestamp2) {
            //print "The first date is earlier than the second.";
            return 2;
        } else {
            //print "The dates are equal.";
            return 0;
        }
    }
    //-----------------------------------------------------------------
    //-----------------------------------------------------------------
    //-----------------------------------------------------------------
    //-----------------------------------------------------------------
    //-----------------------------------------------------------------


} //end of class