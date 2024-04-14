<?php

namespace DAO;

use \Exception as Exception;
use Models\Conversation as Conversation;
use DAO\Connection as Connection;
use Models\Status as Status;

class  conversationDAO
{

    private $tableName = "conversation";
    private $connection;

    public function generateConver($keeperCode, $ownerCode)
    {
        try {

            $checkConverPrev = $this->checkPrevConver($keeperCode, $ownerCode);

            if ($checkConverPrev == ' ' || $checkConverPrev == null) {

                $query = "INSERT INTO " . $this->tableName . " (idCon,codeConv,keeperCode,ownerCode,status) 
            VALUES (:idCon,:codeConv,:keeperCode,:ownerCode,:status);";

                $this->connection = Connection::GetInstance();

                $lastInsertId = $this->connection->LastInsertId($this->tableName);

                $microseconds = microtime();
                $uniqueId = md5($microseconds); // Obtener el hash MD5 de la marca de tiempo con microsegundos
                $uniqueId = substr($uniqueId, 0, 12);

                $parameters["idCon"] = $lastInsertId;
                $parameters["codeConv"] = $uniqueId;
                $parameters["keeperCode"] = $keeperCode;
                $parameters["ownerCode"] = $ownerCode;
                $parameters["status"] = Status::ACTIVE;

                $result = $this->connection->ExecuteNonQuery($query, $parameters);

                if ($result == 1) {
                    $result = $uniqueId;
                }
            } else {
                $result = $checkConverPrev;
            }



            //Success insert $result = uniqueId else $result = 0 (error)
            return $result;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function getConverCode($keeperCode, $ownerCode)
    {
        try {
            $query = "SELECT * FROM " . $this->tableName . "
            WHERE keeperCode = :keeperCode AND ownerCode = :ownerCode";

            $this->connection = Connection::GetInstance();

            $parameters["keeperCode"] = $keeperCode;
            $parameters["ownerCode"] = $ownerCode;

            $resultSet = $this->connection->Execute($query, $parameters);

            $conver = new Conversation();
            foreach ($resultSet as $row) {
                $conver->setidCon($row["idCon"]);
                $conver->setCodeConv($row["codeConv"]);
                $conver->setKeeperCode($row["keeperCode"]);
                $conver->setOwnerCode($row["ownerCode"]);
                $conver->setTimestamp($row["timestamp"]);
                $conver->setStatus($row["status"]);
            }


            //if empty :( else object filled :)
            return $conver;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function checkPrevConver($codeKeeper, $codeOwner)
    {
        try {
            $query = "SELECT codeConv FROM " . $this->tableName . " 
            WHERE keeperCode = :codeKeeper AND ownerCode = :codeOwner;";

            $this->connection = Connection::GetInstance();

            $parameters["codeKeeper"] = $codeKeeper;
            $parameters["codeOwner"] = $codeOwner;

            $resultSet = $this->connection->Execute($query, $parameters);

            $resp = $resultSet[0][0];

            return $resp;
        } catch (Exception $ex) {
            throw $ex;
        }
    }


    public function getConverByUserCode($userCode)
    {
        try {
            $query = "SELECT c.codeConv,c.keeperCode,c.idCon,c.timestamp,c.status,c.ownerCode,k.name AS kname,k.lastname AS klastname, o.name AS oname,o.lastname AS olastname,k.pfp AS kpfp,o.pfp AS opfp,m.msgText AS lastMsgText,m.timestamp AS msgTimeStamp,SUM(CASE WHEN m.seen = 0 THEN 1 ELSE 0 END) AS unread_messages
                    FROM " . $this->tableName . " as c
                    JOIN keeper AS k 
                    ON k.keeperCode = c.keeperCode
                    JOIN owner AS o 
                    ON o.ownerCode = c.ownerCode
                    LEFT JOIN 
                        (
                            SELECT chatCode,msgText,timestamp,seen
                            FROM message as m
                            WHERE (chatCode, timestamp) 
                            IN 
                                (
                                    SELECT chatCode,MAX(timestamp) 
                                    FROM message 
                                    GROUP BY chatCode
                                )
                         ) AS m ON m.chatCode = c.codeConv ";
            if (strpos($userCode, "OWN") !== false) {
                $query .= " WHERE c.ownerCode = :userCode";
            } else if (strpos($userCode, "KEP") !== false) {
                $query .= " WHERE c.keeperCode = :userCode";
            } else {
                throw new Exception("Something is bad with the userCode");
            }

            $query .= " GROUP BY
            c.codeConv,
            c.keeperCode,
            c.idCon,
            c.timestamp,
            c.status,
            c.ownerCode,
            k.name,
            k.lastname, 
            o.name,
            o.lastname,
            k.pfp,
            o.pfp,
            m.msgText,
            m.timestamp;";

            $this->connection = Connection::GetInstance();

            $parameters["userCode"] = $userCode;

            $resultSet = $this->connection->Execute($query, $parameters);

            $conversationsByCode = array();
            foreach ($resultSet as $row) {
                //paso directamente un array asociativo para no deserializar y serializar los objs y pasarlos a json
                //$conver = new Conversation();

                $conver["idCon"] = $row["idCon"];
                $conver["codeConv"] = $row["codeConv"];
                $conver["keeperCode"] = $row["keeperCode"];
                $conver["ownerCode"] = $row["ownerCode"];
                $conver["timestamp"] = $row["timestamp"];
                $conver["status"] = $row["status"];
                $conver["kname"] = $row["kname"];
                $conver["klastname"] = $row["klastname"];
                $conver["oname"] = $row["oname"];
                $conver["olastname"] = $row["olastname"];
                $conver["kpfp"] = FRONT_ROOT . "Images/";
                $conver["kpfp"] .= $row["kpfp"];
                $conver["opfp"] = FRONT_ROOT . "Images/";
                $conver["opfp"] .= $row["opfp"];
                $conver["lastMsgText"] = $row["lastMsgText"];
                $conver["msgTimeStamp"] = $row["msgTimeStamp"];
                $conver["unread_messages"] = $row["unread_messages"];

                array_push($conversationsByCode, $conver);
            }
            return $conversationsByCode;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function getUsersFromConver($converCode)
    {
        try {
            $query = "SELECT keeperCode,ownerCode FROM " . $this->tableName . "
            WHERE codeConv = :codeConv;";

            $this->connection = Connection::GetInstance();

            $parameters["codeConv"] = $converCode;

            $resultSet = $this->connection->Execute($query, $parameters);

            $codes = array();
            foreach ($resultSet as $row) {
                $codes["keeperCode"] = $row["keeperCode"];
                $codes["ownerCode"] = $row["ownerCode"];
            }

            return $codes;
        } catch (Exception $ex) {
            throw $ex;
        }
    }


    public function getFullNamesFromConver($chatCode)
    {
        try {
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
