<?php

namespace Utils;

use \DateTime as DateTime;
date_default_timezone_set('America/Argentina/Buenos_Aires');

class Dates{



    public static function validateDate($date)
    {
        $valid = null;
        $dateDT = DateTime::createFromFormat('Y-m-d',$date);

        return $dateDT;
    }


    public static function validateAndCompareDates($initDate, $endDate) {
        // from String to DateTime objs
        $initDateTime = DateTime::createFromFormat('Y-m-d', $initDate);
        $endDateTime = DateTime::createFromFormat('Y-m-d', $endDate);
        
    
        $result = null;
        // Validate dates
        if (!$initDateTime || !$endDateTime) {
            $result = null;
        }
        // Compare dates
        if ($initDateTime > $endDateTime) {
            $result = -1;
        } elseif ($initDateTime == $endDateTime) {
            $result = 0;
        } else {
           $result = 1;
        }

        
        return $result;
    }

    public static function currentCheck($date)
    {
        $currentDateStr = date('Y-m-d');
        $currentDate = new DateTime($currentDateStr);
        
        $dateTime = DateTime::createFromFormat('Y-m-d', $date);
        var_dump($date);
        var_dump($currentDate);
        if($dateTime < $currentDate)
        {
            $result = null;
        }else{
            $result = 1;
        }
        return $result;
    }

    public static function calculateDays($initDate,$endDate)
    {
        $initDateTime = DateTime::createFromFormat('Y-m-d', $initDate);
        $endDateTime = DateTime::createFromFormat('Y-m-d', $endDate);

        if (!$initDateTime || !$endDateTime) {
            $result = null;
        }else{
            $interval = $initDateTime->diff($endDateTime);
            $result = $interval->days + 1; // Agregar 1 para incluir el último día
        }

        return $result;
    }
}

?>