<?php

namespace DAO;

use \Exception as Exception;
use DAO\Connection as Connection;
use Models\Owner as Owner;


class OwnerDAO
{

    private $tableName = "owner";
    private $connection;

    public function Add(Owner $owner)
    {
        $ownerCode = null;
        try {

            $query = "INSERT INTO " . $this->tableName . " (ownerCode,email,username,password,status,name,lastname,dni,pfp)
            VALUES (:ownerCode,:email,:username,:password,:status,:name,:lastname,:dni,:pfp) ;";

            $this->connection = Connection::GetInstance();


            $parameters["ownerCode"] = $owner->getOwnerCode();
            $parameters["email"] = $owner->getEmail();
            $parameters["username"] = $owner->getUserName();
            $parameters["password"] = $owner->getPassword();
            $parameters["status"] = $owner->getStatus();
            $parameters["name"] = $owner->getName();
            $parameters["lastname"] = $owner->getLastname();
            $parameters["dni"] = $owner->getDni();
            $parameters["pfp"] = null;


            $resultInsert = $this->connection->ExecuteNonQuery($query, $parameters);
            if ($resultInsert == 1) {
                $ownerCode = $owner->getOwnerCode();
            }
        } catch (Exception $ex) {
            throw $ex;
        }

        //null or ownerCode generated
        return $ownerCode;
    }

    public function updateStatus($code, $status)
    {
        try {

            $query = "UPDATE " . $this->tableName . " 
            SET status = :status 
            WHERE ownerCode = :code ;";

            $this->connection = Connection::GetInstance();

            $parameters["status"] = $status;
            $parameters["code"] = $code;

            return $this->connection->ExecuteNonQuery($query, $parameters);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function GetAll()
    {
        try {

            $ownerList = array();

            $query = "SELECT *  FROM " . $this->tableName;

            $this->connection = Connection::GetInstance();

            $resultSet = $this->connection->Execute($query);

            foreach ($resultSet as $value) {
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

                array_push($ownerList, $owner);
            }

            return $ownerList;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function Remove($id)
    {
        try {

            $query = "DELETE FROM " . $this->tableName . "
                     WHERE id = :id ;";

            $parameters["id"] = $id;

            $this->connection = Connection::GetInstance();

            $this->connection->ExecuteNonQuery($query, $parameters);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function searchById($id)
    {
        try {

            $query = "SELECT * FROM " . $this->tableName . "
                    WHERE id = :id;";

            $this->connection = Connection::GetInstance();


            $resultSet = $this->connection->Execute($query);

            foreach ($resultSet as $value) {
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
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function checkUsername($username)
    {
        try {
            $query = "SELECT COUNT(*) as result FROM " . $this->tableName . " WHERE BINARY username = :username ;"; //Limit 1 to not oversearch

            $parameters["username"] = $username;

            $this->connection = Connection::GetInstance();

            $result = $this->connection->Execute($query, $parameters);


            foreach ($result as $row) {
                $finalRes = $row[0];
            }

            return $finalRes;
        } catch (Exception $ex) {

            throw $ex;
        }
    }

    public function searchByEmail($email)
    {
        try {
            $query = "SELECT * FROM " . $this->tableName . "
            WHERE email = :email ;";

            $parameters["email"] = $email;

            $this->connection = Connection::GetInstance();

            $resultSet = $this->connection->Execute($query, $parameters);

            if (empty($resultSet)) {
                $owner = null;
            } else {
                foreach ($resultSet as $value) {

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
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function searchByUsername($username)
    {
        try {
            //Compare the data as bytes and not as string
            $query = "SELECT * FROM " . $this->tableName . "
            WHERE BINARY username = :username;";

            $parameters["username"] = $username;

            $this->connection = Connection::GetInstance();

            $resultSet = $this->connection->Execute($query, $parameters);

            if ($resultSet == null) {
                $owner = null;
            } else {
                foreach ($resultSet as $value) {
                    
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
        } catch (Exception $ex) {
            throw $ex;
        }
    }


    public function searchByCode($code)
    {
        try {

            $query = "SELECT * FROM " . $this->tableName . " 
            WHERE ownerCode = :ownerCode ;";

            $this->connection = Connection::GetInstance();

            $parameters["ownerCode"] = $code;


            $resultSet = $this->connection->Execute($query, $parameters);

            $owner = new Owner();
            foreach ($resultSet as $value) {


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
                $owner->setBio($value["bio"]);
            }
        } catch (Exception $ex) {
            throw $ex;
        }
        return $owner;
    }

    public function getPassword($email)
    {
        try {
            $query = "SELECT password FROM " . $this->tableName . "
            where email = :email;";

            $this->connection = Connection::GetInstance();

            $parameters["email"] = $email;

            $resultSet = $this->connection->Execute($query, $parameters);

            $arrPwd = array_shift($resultSet);

            $pwd = $arrPwd["password"];

            return $pwd;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function updatePfp($ownerCode, $pfp)
    {
        try {
            $query = "UPDATE " . $this->tableName . " 
            SET pfp = :pfp
            WHERE ownerCode = :ownerCode;";

            $this->connection = Connection::GetInstance();

            $parameters["pfp"] = $pfp;
            $parameters["ownerCode"] = $ownerCode;


            $result = $this->connection->ExecuteNonQuery($query, $parameters);

            return $result;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function updateEmail($ownerCode, $email)
    {
        try {
            $query = "UPDATE " . $this->tableName . " 
            SET email = :email 
            WHERE ownerCode = :ownerCode;";

            $this->connection = Connection::GetInstance();

            $parameters["ownerCode"] = $ownerCode;
            $parameters["email"] = $email;

            $result = $this->connection->ExecuteNonQuery($query, $parameters);

            return $result;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function updateBio($ownerCode, $bio)
    {
        try {

            $query = "UPDATE " . $this->tableName . " 
            SET bio = :bio
            WHERE ownerCode = :ownerCode;";

            $this->connection = Connection::GetInstance();

            $parameters["ownerCode"] = $ownerCode;
            $parameters["bio"] = $bio;

            $result = $this->connection->ExecuteNonQuery($query, $parameters);

            return $result;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function updatePassword($email, $password)
    {
        try {
            $query = "UPDATE " . $this->tableName . " 
            SET password = :password 
            WHERE email = :email ;";

            $this->connection = Connection::GetInstance();

            $parameters["email"] = $email;
            $parameters["password"] = $password;

            return $this->connection->ExecuteNonQuery($query, $parameters);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function checkDni($dni)
    {
        try{
            $query = "SELECT COUNT(*) FROM ".$this->tableName." 
            WHERE dni = :dni;";

            $this->connection = Connection::GetInstance();

            $parameter["dni"] = $dni;

            $result = $this->connection->Execute($query,$parameter);

            return $result[0][0];
        }catch(Exception $ex)
        {
            throw $ex;
        }
    }
}
