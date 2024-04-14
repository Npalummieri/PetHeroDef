<?php 

namespace DAO;


use DAO\Connection as Connection;
use \Exception as Exception;

class NotificationDAO {

    private $tableName = "notification";
    private $connection;

    public function generateNoti($message,$receiver)
    {
        try{

            $query = "INSERT INTO ".$this->tableName." (message,receiver) VALUES (:message,:receiver);";

            $this->connection = Connection::GetInstance();

            $parameters["message"] = $message;
            $parameters["receiver"] = $receiver;

            $result = $this->connection->ExecuteNonQuery($query,$parameters);

            return $result;
        }catch(Exception $ex)
        {
            throw $ex;
        }
    }

    public function getAllByCode($code)
    {
        try{

            $query = "SELECT * FROM ".$this->tableName." 
            WHERE receiver = :code
            ORDER BY timestamp DESC;";

            $this->connection = Connection::GetInstance();

            $parameter["code"] = $code;

            $resultSet = $this->connection->Execute($query,$parameter);

            $notis = array();
            foreach($resultSet as $row)
            {
                
            
                $noti["id"] = $row["id"];
                $noti["message"] = $row["message"];
                $noti["receiver"] = $row["receiver"];
                $noti["timestamp"] = $row["timestamp"];
                $noti["seen"] = $row["seen"];

                array_push($notis,$noti);
            }

            return $notis;
        }catch(Exception $ex)
        {
            throw $ex;
        }
    }

    public function viewNotis($codeUser)
    {
        try{

            $query = "UPDATE ".$this->tableName."  
            SET seen = 1 
            WHERE receiver = :codeUser ;";            
            

            $this->connection = Connection::GetInstance();

            $parameter["codeUser"] = $codeUser;

            $result = $this->connection->ExecuteNonQuery($query,$parameter);

            return $result;
        }catch(Exception $ex)
        {
            throw $ex;
        }
    }
}
?>