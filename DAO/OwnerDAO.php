<?php

namespace DAO;

use \Exception as Exception;
use DAO\QueryType as QueryType;
use DAO\Connection as Connection;
use Models\Owner as Owner;
use Interfaces\IDAO as IDAO;

class OwnerDAO extends IDAO{

    private $tableName = "owner";
    private $connection;
    
    public function Add(Owner $owner)
    {
        $ownerCode = null;
        try{

            //Nicolas del futuro --> Trata de hacer una transaccion dentro de Connection con una function LastInsertId totalmente
            //hardcodeada por vos asi tenes acceso a PDO sin quebrar el resto del framework

            $query = "INSERT INTO ".$this->tableName." (ownerCode,email,username,password,status,name,lastname,dni,pfp)
            VALUES (:ownerCode,:email,:username,:password,:status,:name,:lastname,:dni,:pfp) ;";

            $this->connection = Connection::GetInstance();

            $nextId = $this->connection->LastInsertId($this->tableName);

            
            $parameters["ownerCode"] = $owner->getOwnerCode();
            $parameters["email"] = $owner->getEmail();
            $parameters["username"] = $owner->getUserName();
            //Averiguar como encriptar pass
            $parameters["password"] = $owner->getPassword();
            $parameters["status"] = $owner->getStatus();
            $parameters["name"] = $owner->getName();
            $parameters["lastname"] = $owner->getLastname();
            $parameters["dni"] = $owner->getDni();
            $parameters["pfp"] = null;

            

            $resultInsert = $this->connection->ExecuteNonQuery($query,$parameters);

            //Provisional hasta que arregle todo lo de UUID
            if ($resultInsert == 1) {

                $ownerCode = $owner->getOwnerCode();
            
                // Hacer algo con $ownerCode (por ejemplo, almacenarlo o utilizarlo en otra consulta)
            }


        }catch(Exception $ex)
        {
            throw $ex;   
        }

        //En caso que se haya ejecutado bien todo devuelve el codigo owner generado sino null
        return $ownerCode;
    }

    public function updateStatus($code)
    {
        try{

            $query = "UPDATE ".$this->tableName." 
            SET status = :status 
            WHERE keeperCode = :code ;";

            $this->connection = Connection::GetInstance();

            $parameters["code"] = $code;
            $parameters["status"] = "active";
            return $this->connection->ExecuteNonQuery($query,$parameters);

        }catch(Exception $ex)
        {
            throw $ex;
        }
    }

    public function GetAll()
    {
        try{

            $ownerList = array();

            $query = "SELECT *  FROM ".$this->tableName;

            $this->connection = Connection::GetInstance();
    
            $resultSet = $this->connection->Execute($query);

            foreach($resultSet as $value)
            {
                $owner = new Owner;

                $owner->setId($value["id"]);
                $owner->setOwnerCode($value["ownerCode"]);
                $owner->setEmail($value["email"]);
                $owner->setUserName($value["username"]);
                $owner->setPassword($value["password"]);
                $owner->setStatus($value["status"]);
                $owner->setName($value["name"]);
                $owner->setLastname($value["lastname"]);
                $owner->setDni($value["dni"]);
                $owner->setPfp($value["pfp"]);

                array_push($ownerList,$owner);
            
            }

            return $ownerList;

        }catch(Exception $ex)
        {
            throw $ex;
        }
    }

    public function Remove($id) //Remove tambien podria pasar al estado de "bajado" y que efectivamente se borrre luego de un tiempo
    {
        try{

            $query = "DELETE FROM ".$this->tableName."
                     WHERE id = :id ;";

            $parameters["id"] = $id;

            $this->connection = Connection::GetInstance();

            $this->connection->ExecuteNonQuery($query,$parameters);

        }catch(Exception $ex)
        {
            throw $ex;
        }
    }

    public function searchById($id)
    {
        try{

            $query = "SELECT * FROM ".$this->tableName."
                    WHERE id = :id;";

            $this->connection = Connection::GetInstance();

            //Creo que resultSet devuelve el puntero al arreglo que se forma a partir de las filas de la query
            $resultSet = $this->connection->Execute($query);

            //Lo mejor es pasarlo a un objeto y de ahi retornamos
            // --Probar unserialize serialize
            foreach($resultSet as $value)
            {
                $owner = new Owner;

                $owner->setId($value["id"]);
                $owner->setOwnerCode($value["ownerCode"]);
                $owner->setEmail($value["email"]);
                $owner->setUserName($value["username"]);
                $owner->setPassword($value["password"]);
                $owner->setStatus($value["status"]);
                $owner->setName($value["name"]);
                $owner->setLastname($value["lastname"]);
                $owner->setDni($value["dni"]);
                $owner->setPfp($value["pfp"]);
            }

            return $owner;

        }catch(Exception $ex)
        {
            throw $ex;
        }
    }

    public function checkUsername($username) 
    {
        try
        {
            $query = "SELECT COUNT(*) as result FROM ".$this->tableName." WHERE username = :username ;"; //Limit 1 corta la busqueda si ya encontro 1 coincidencia ta
            
            $parameters["username"] = $username;
           
            $this->connection = Connection::GetInstance();

            $result = $this->connection->Execute($query,$parameters);
            

            foreach($result as $row) //Itero sobre el resultado ya que el framework utiliza fetchAll (Entiendo que podria utilizar fetchColumn para traer el resultado de count(*) y listo)
            {
                $finalRes = $row[0];
            }
            
            return $finalRes;
        }
        catch(Exception $ex)
        {
           
            throw $ex;
        }
    }

    public function searchByEmail($email)
    {
        try{
            $query = "SELECT * FROM ".$this->tableName."
            WHERE email = :email ;";

            $parameters["email"] = $email;

            $this->connection = Connection::GetInstance();

            $resultSet = $this->connection->Execute($query,$parameters);
             
            if(empty($resultSet))
            {
                $owner = null;
            }else
            {
                foreach($resultSet as $value)
            {
                $owner = new Owner();
                $owner->setId($value["id"]);
                $owner->setOwnerCode($value["ownerCode"]);
                $owner->setEmail($value["email"]);
                $owner->setUserName($value["username"]);
                $owner->setPassword($value["password"]);
                $owner->setStatus($value["status"]);
                $owner->setName($value["name"]);
                $owner->setLastname($value["lastname"]);
                $owner->setDni($value["dni"]);
                $owner->setPfp($value["pfp"]);
            }
            }
            
            return $owner;

        }catch(Exception $ex)
        {
            throw $ex;
        }
    }

    public function searchByUsername($username)
    {
        try{
            $query = "SELECT * FROM ".$this->tableName."
            WHERE username = :username;";

            $parameters["username"] = $username;

            $this->connection = Connection::GetInstance();

            //Asumo que con Laravel+ORM te podes ahorrar todo esto de setear TODAS las variables pero bueno...
            $resultSet = $this->connection->Execute($query,$parameters);

            if($resultSet == null)
            {
                $owner = null;
            }else
            {
                foreach($resultSet as $value)
            {
                $owner = new Owner();

                $owner->setId($value["id"]);
                $owner->setOwnerCode($value["ownerCode"]);
                $owner->setEmail($value["email"]);
                $owner->setUserName($value["username"]);
                $owner->setPassword($value["password"]);
                $owner->setStatus($value["status"]);
                $owner->setName($value["name"]);
                $owner->setLastname($value["lastname"]);
                $owner->setDni($value["dni"]);
                $owner->setPfp($value["pfp"]);
            }
            }
            return $owner;

        }catch(Exception $ex)
        {
            throw $ex;
        }
    }

    //Podria ser un count y listo pero bueno depende lo que necesite!
    public function searchByCode($code)
    {
        try{

            $query = "SELECT * FROM ".$this->tableName." 
            WHERE ownerCode = :ownerCode ;";

            $this->connection = Connection::GetInstance();

            $parameters["ownerCode"] = $code;

            //Creo que resultSet devuelve el puntero al arreglo que se forma a partir de las filas de la query
            $resultSet = $this->connection->Execute($query,$parameters);

            //Lo mejor es pasarlo a un objeto y de ahi retornamos
            // --Probar unserialize serialize
            $owner = new Owner();
            foreach($resultSet as $value)
            {
                

                $owner->setId($value["id"]);
                $owner->setOwnerCode($value["ownerCode"]);
                $owner->setEmail($value["email"]);
                $owner->setUserName($value["username"]);
                $owner->setPassword($value["password"]);
                $owner->setStatus($value["status"]);
                $owner->setName($value["name"]);
                $owner->setLastname($value["lastname"]);
                $owner->setDni($value["dni"]);
                $owner->setPfp($value["pfp"]);
            }

           

        }catch(Exception $ex)
        {
            throw $ex;
        }
        return $owner;
    }

    public function getPassword($email)
    {
        try{
            $query = "SELECT password FROM ".$this->tableName."
            where email = :email;";

            $this->connection = Connection::GetInstance();

            $parameters["email"] = $email;

            $resultSet = $this->connection->Execute($query,$parameters);

            $arrPwd = array_shift($resultSet);

            $pwd = $arrPwd["password"];

            return $pwd;
        }catch(Exception $ex)
        {
            throw $ex;
        }
    }

    public function updatePfp($ownerCode,$pfp)
    {
        try{
            $query = "UPDATE ".$this->tableName." 
            SET pfp = :pfp
            WHERE ownerCode = :ownerCode;";

            $this->connection = Connection::GetInstance();

            $parameters["pfp"] = $pfp;
            $parameters["ownerCode"] = $ownerCode;
            
            var_dump($query);
            $result = $this->connection->ExecuteNonQuery($query,$parameters);
            echo "RESULT :";
            var_dump($result);
            //1 success 0 failed
            return $result;
        }catch(Exception $ex)
        {   
            throw $ex;
        }
    }

    public function updateEmail($ownerCode,$email){
        try{
            $query = "UPDATE ".$this->tableName." 
            SET email = :email 
            WHERE ownerCode = :ownerCode;";

            $this->connection = Connection::GetInstance();

            $parameters["ownerCode"] = $ownerCode;
            $parameters["email"] = $email;

            $result = $this->connection->ExecuteNonQuery($query,$parameters);

            return $result;

        }catch(Exception $ex)
        {
            throw $ex;
        }
    }

    public function updateBio($ownerCode,$bio){
        try{
            $query = "UPDATE ".$this->tableName." 
            SET bio = :bio
            WHERE ownerCode = :ownerCode;";

            $this->connection = Connection::GetInstance();

            $parameters["ownerCode"] = $ownerCode;
            $parameters["bio"] = $bio;

            $result = $this->connection->ExecuteNonQuery($query,$parameters);

            return $result;
        }catch(Exception $ex){
            throw $ex;
        }

    }

}
