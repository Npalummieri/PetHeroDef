<?php

namespace Utils;

use \DateTime as DateTime;


class Dates{


    public static function validateDate($date)
    {
        $valid = null;
        $dateDT = DateTime::createFromFormat('Y-m-d',$date);

        return $dateDT;
    }

    //Valido 0 o 1 en el mejor de los casos
    public static function validateAndCompareDates($initDate, $endDate) {
        // Convertir las fechas a objetos DateTime
        $initDateTime = DateTime::createFromFormat('Y-m-d', $initDate);
        $endDateTime = DateTime::createFromFormat('Y-m-d', $endDate);
        
    
        $result = null;
        // Verificar si las fechas son válidas
        if (!$initDateTime || !$endDateTime) {
            $result = null;
        }
        // Comparar las fechas
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
        $currentDate = new DateTime();
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
}

?>