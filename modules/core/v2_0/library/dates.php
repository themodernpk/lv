<?php

class Dates
{

    /* ****** Code Completed till 10th april */
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
    /* ******\ Code Completed till 10th april */
} //end of class