<?php

namespace Utils;

use \DateTime as DateTime;


class Dates{

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
}

?>