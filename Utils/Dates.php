<?php

namespace Utils;

use DAO\OwnerDAO as OwnerDAO;
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

    public static function remainingSuspense($codeOwner)
    {
        $ownerDAO = new OwnerDAO();
        $time = time();
        $owner = $ownerDAO->searchByCode($codeOwner);
        $suspensionDate = strtotime($owner->getSuspensionDate());
        $punishTime = $suspensionDate + 172800; //+48hs
        // $convertedDate =  DateTime::createFromFormat('U',$remaining);
        $remaining = $punishTime - $time;
        $seconds = $remaining % 60;
        $minutes = floor(($remaining / 60) % 60);
        $hours = floor(($remaining / 3600) % 24);
        $days = floor($remaining / 86400);

        if($days > 0 || $hours > 0 || $minutes > 0 || $seconds > 0)
        {
            return "TIEMPO RESTANTE DE SUSPENSION : Dias :$days, Horas: $hours, Minutos: $minutes, Segundos: $seconds";
        }else{
            return null;
        }
        

    }
}

?>