<?php

namespace DAO;

use \Exception as Exception;
use DAO\QueryType as QueryType;
use DAO\Connection as Connection;
use Locale;
use Models\Message as Message;

class MessageDAO {

    private $tableName = "message";
    private $connection;

    //Para send y receive en vez de recibir los parametros por separado podria encapsular todo en un Model\Message (objeto) correspondiente
    public function sendMessage($senderCode,$receiverCode,$msgText,$chatCode,$seen)
    {
        try{
            $query = "INSERT INTO ".$this->tableName." (codeSender,codeReceiver,msgText,chatCode,seen)
            VALUES (:codeSender,:codeReceiver,:msgText,:chatCode,:seen);
            ";

            $querylast = "SELECT LAST_INSERT_ID();";

            $parameters["codeSender"] = $senderCode;
            $parameters["codeReceiver"] = $receiverCode;
            $parameters["msgText"] = $msgText;
            $parameters["chatCode"] = $chatCode;
            $parameters["seen"] = $seen;

            $this->connection = Connection::GetInstance();
            
            
            $resultSet = $this->connection->ExecuteNonQuery($query,$parameters);
        
            $resultLast = $this->connection->Execute($querylast);

            //Xq es un array(1) que contiene un arrayAsoc(2) {["lastinsertid"] => id ,[0] => id}
            $lastInsert = array_shift($resultLast[0]);

            //No se me ocurre en caso que haya error que devolveria 
            if(isset($lastInsert))
            {
                $query2 = "SELECT * FROM ".$this->tableName."
                WHERE idMsg = :id";

                $parameter["id"] = $lastInsert;

                $resultMsge = $this->connection->Execute($query2,$parameter);

                foreach($resultMsge as $msge)
                {
                    
                    $msg["idMsg"] = $msge["idMsg"];
                    $msg["codeSender"] = $msge["codeSender"];
                    $msg["codeReceiver"] = $msge["codeReceiver"]; 
                    $msg["msgText"] = $msge["msgText"]; 
                    $msg["chatCode"] = $msge["chatCode"];
                    $msg["timeStamp"] = $msge["timeStamp"];
                    $msg["seen"] = $msge["seen"]; 

                }

                return $msg;
            }else
            {
                throw new Exception("Error DAO message");
            }

        }catch(Exception $ex)
        {
            throw $ex;
        }
    }

    //public function checkCodCode()

    public function getChatCode($codeSender,$codeReceiver)
    {
        try{

            $query = "SELECT m.chatCode FROM ".$this->tableName." as m
            WHERE (m.codeSender = :codeSender AND m.codeReceiver = :codeReceiver) 
            OR (m.codeSender = :codeReceiver AND m.codeReceiver = :codeSender);";

            $this->connection = Connection::GetInstance();

            $parameters["codeSender"] = $codeSender;
            $parameters["codeReceiver"] = $codeReceiver;

            $resultSet = $this->connection->Execute($query,$parameters);

            //var_dump($resultSet);
            foreach($resultSet as $row)
            {
                $result["chatCode"] = $row["chatCode"];
            }

            return $result;
        }catch(Exception $ex)
        {
            throw $ex;
        }
    }


    //La idea seria (no importa si sos Keeper o Owner levantar aquellos usuarios que tenes booking.status = paidup y esten disponibles p/ conversacion)
    public function getBothBookingsUsers($codeLogged)
    {
        try
        {
            $query = "SELECT b.keeperCode, b.ownerCode, k.name AS kname, k.lastname AS klastname, 
                 o.name AS oname, o.lastname AS olastname, k.pfp AS kpfp, o.pfp AS opfp
                FROM booking AS b
                JOIN coupon AS c ON b.bookCode = c.bookCode
                JOIN owner AS o ON o.ownerCode = b.ownerCode";

            //Esta puesto c.status = 'paidup' en vez de b.status porq todavia no actualice que tambien el booking pase a paidup al pagarse
            if (strpos($codeLogged,"OWN") !== false) {
                $query .= " JOIN keeper AS k ON k.keeperCode = b.keeperCode
                WHERE b.ownerCode = :codeLogged AND c.status = 'paidup'";
            } else if (strpos($codeLogged,"KEP") !== false) {
                $query .= " JOIN keeper AS k ON k.keeperCode = b.keeperCode
                WHERE b.keeperCode = :codeLogged AND c.status = 'paidup'";
            }

            $this->connection = Connection::GetInstance();

            $parameter["codeLogged"] = $codeLogged;

            $resultSet = $this->connection->Execute($query,$parameter);

            $usersAvailTalk = array();
            foreach($resultSet as $row)
            {
                $infoBothUsers["keeperCode"] = $row["keeperCode"];
                $infoBothUsers["ownerCode"] = $row["ownerCode"];
                $infoBothUsers["kname"] = $row["kname"];
                $infoBothUsers["klastname"] = $row["klastname"];
                $infoBothUsers["oname"] = $row["oname"];
                $infoBothUsers["olastname"] = $row["olastname"];
                $infoBothUsers["kpfp"] = $row["kpfp"];
                $infoBothUsers["opfp"] = $row["opfp"];

                array_push($usersAvailTalk,$infoBothUsers);
            }

            return $usersAvailTalk;
        }catch(Exception $ex)
        {
            throw $ex;
        }
    }

    //Importante el orden de los parametros
    public function receiveMessage($codeSender,$codeReceiver,$chatCode)
    {
        
        try{
            //Deberia ver si traigo tambien los k.pfp/o.pfp,tambien ver si de ultima lo hago en query aparte 
            $query = "SELECT m.idMsg,m.codeSender,m.codeReceiver,m.msgText,m.timestamp,chatCode 
            FROM ".$this->tableName." as m
            WHERE (codeSender = :codeSender AND codeReceiver = :codeReceiver)
            OR (codeSender = :codeReceiver AND codeReceiver = :codeSender)
            AND chatCode = :chatCode
            ORDER BY timestamp;";

            $this->connection = Connection::GetInstance();

            $parameters["codeSender"] = $codeSender;
            $parameters["codeReceiver"] = $codeReceiver;
            $parameters["chatCode"] = $chatCode;
            
            $queryTwo = "UPDATE ".$this->tableName." SET seen = 1
            WHERE chatCode = :chatCode AND seen = 0;";

            $resultSet = $this->connection->Execute($query,$parameters);

            //Al recibir los mensajes se dan por leidos...
            $parametersTwo["chatCode"] = $chatCode;
            $this->connection->ExecuteNonQuery($queryTwo,$parametersTwo);

            $arrayToChat = array();

            foreach($resultSet as $row){
                //$partialArray = array();
                //Un array,un objeto,QUE?
                $partialArray["idMsg"] = $row["idMsg"] ;
                $partialArray["codeSender"] = $row["codeSender"];
                $partialArray["codeReceiver"] = $row["codeReceiver"];
                $partialArray["msgText"] = $row["msgText"];
                $partialArray["timestamp"] = $row["timestamp"];
                $partialArray["chatCode"] = $row["chatCode"];
                

                array_push($arrayToChat,$partialArray);
            }
            return $arrayToChat;
        }catch(Exception $ex)
        {
            throw $ex;
        }
    }

    //Te llega el code revisas el formato y en base a eso haces la query join con booking
    
    public function getUsersFromBook($code)
    {
        
        try{
            
            $query ="SELECT email,username,name,";

            
            //En teoria si es own voy a buscar sus respectivos keepers con los que tenga coup = paidup y si es keepers al reves
            
            //verifica que la cadena comience con "OWN" o "KEP" y le sigan 3 digitos
            if(strpos($code,"OWN") !== false)
            {
                $query .= " k.keeperCode as codeUser FROM keeper as k 
                JOIN (SELECT c.couponCode,c.bookCode,c.price,c.status,b.keeperCode
	            FROM coupon as c
	            JOIN booking as b
                ON c.bookCode = b.bookCode
                WHERE c.status = 'paidup' AND b.ownerCode = :code) as tableTemp
                ON k.keeperCode = tableTemp.keeperCode;"; 
            }else if(strpos($code,"KEP") !== false)
            {
                $query .= " o.ownerCode as codeUser FROM owner as o 
                JOIN (SELECT c.couponCode,c.bookCode,c.price,c.status,b.ownerCode
	            FROM coupon as c
	            JOIN booking as b
                ON c.bookCode = b.bookCode
                WHERE c.status = 'paidup' AND b.keeperCode = :code) as tableTemp
                ON o.ownerCode = tableTemp.ownerCode;";
            }

           
            $this->connection = Connection::GetInstance();

            $parameters["code"] = $code;

            $resultSet = $this->connection->Execute($query,$parameters);

            $arrayUsers = array();
            foreach($resultSet as $row)
            {
                $infoUsers["email"] = $row["email"];
                $infoUsers["username"] = $row["username"];
                $infoUsers["name"] = $row["name"];
                //$infoUsers["keeperCode"] = $row["keeperCode"];
                $infoUsers["codeUser"] = $row["codeUser"];

                array_push($arrayUsers,$infoUsers);
            }

            
            return $arrayUsers;
        }catch(Exception $ex)
        {
            throw $ex;
        }
    }

    public function getConverInfo($codeLogged)
    {
        try{
            
            ///Faltaria pfp
            $query ="WITH ranked_messages AS (
            SELECT
            m.msgText,
            m.timeStamp,
            m.chatCode,
            email,
            username,
            name,
            ";

            //hice where b.status = 'confirmed' pero en realidad deberia hacer el join con los coupons = 'paidup'
            //En teoria si es own voy a buscar sus respectivos keepers con los que tenga reserva confirmed y si es keepers al reves
            
            //verifica que la cadena comience con "OWN" o "KEP" y le sigan 3 digitos
            if(strpos($codeLogged,"OWN") !== false)
            {
                $query .= "k.lastname,k.keeperCode as codeUser,k.pfp,
                m.seen,
                ROW_NUMBER() OVER (PARTITION BY m.chatCode ORDER BY m.timeStamp DESC) as row_num
                FROM message as m 
                JOIN keeper as k 
                ON k.keeperCode = m.codeReceiver OR k.keeperCode = m.codeSender
                JOIN booking as b 
                ON k.keeperCode = b.keeperCode
                JOIN coupon as c
                ON c.bookCode = b.bookCode
                WHERE c.status = 'paidup' AND b.ownerCode = :codeLogged
                 )";
            }else if(strpos($codeLogged,"KEP") !== false)
            {
                $query .= "o.lastname,o.ownerCode as codeUser,o.pfp,
                m.seen,
                ROW_NUMBER() OVER (PARTITION BY m.chatCode ORDER BY m.timeStamp DESC) as row_num
                FROM message as m
                JOIN owner as o 
                ON o.ownerCode = m.codeReceiver OR o.ownerCode = m.codeSender
                JOIN booking as b 
                ON o.ownerCode = b.ownerCode
                JOIN coupon as c
                ON c.bookCode = b.bookCode
                WHERE c.status = 'paidup' AND b.keeperCode = :codeLogged
                 )";
            }

            $query .= "SELECT
            chatCode,
            COUNT(*) as totalMsgs,
            SUM(CASE WHEN seen = 0 THEN 1 ELSE 0 END) as unseen,
            FIRST_VALUE(ranked_messages.msgText) OVER (PARTITION BY chatCode ORDER BY timeStamp DESC) as msgText,
            MAX(ranked_messages.timeStamp) as timeStamp,
            MAX(ranked_messages.email) as email,
            MAX(ranked_messages.username) as username,
            MAX(ranked_messages.name) as name,
            MAX(ranked_messages.lastname) as lastname,
            MAX(ranked_messages.codeUser) as codeUser,
            MAX(ranked_messages.pfp) as pfp
            FROM ranked_messages
            GROUP BY chatCode;";

            $this->connection = Connection::GetInstance();

            $parameters["codeLogged"] = $codeLogged;
            
            $resultSet = $this->connection->Execute($query,$parameters);
            //var_dump($query);
            $arrayInfoConver = array();

            //Falta PFP //Codeuser es el codigo con el que el sender entabla la conver
            foreach($resultSet as $row)
            {
                $infoConver["msgText"] = $row["msgText"]; 
                $infoConver["timeStamp"] = $row["timeStamp"]; 
                $infoConver["chatCode"] = $row["chatCode"]; 
                $infoConver["email"] = $row["email"]; 
                $infoConver["username"] = $row["username"]; 
                $infoConver["name"] = $row["name"]; 
                $infoConver["lastname"] = $row["lastname"]; 
                $infoConver["codeUser"] = $row["codeUser"]; 
                //Ver si hace falta ponerlo en los max
                $infoConver["pfp"] = $row["pfp"]; 
                $infoConver["unseen"] = $row["unseen"];

                array_push($arrayInfoConver,$infoConver);
            }
            
            // echo "<pre>";
            // var_dump($arrayInfoConver);
            // echo "</pre>";

            return $arrayInfoConver;

        }catch(Exception $ex)
        {
            throw $ex;
        }
    }

    public function getUsersFromChat($chatCode)
    {
        try
        {
            $query = "SELECT codeReceiver,codeSender FROM ".$this->tableName."
            WHERE chatCode = :chatCode;";

            $this->connection = Connection::GetInstance();

            $parameters["chatCode"] = $chatCode;

            $resultSet = $this->connection->Execute($query,$parameters);
            
            $codes = array();
            foreach($resultSet as $row)
            {
                $codes["codeReceiver"] = $row["codeReceiver"];
                $codes["codeSender"] = $row["codeSender"];
            }

            echo "CODES :";
            var_dump($codes);
            return $codes;
        }catch(Exception $ex)
        {
            throw $ex;
        }
    }
    
}
?>