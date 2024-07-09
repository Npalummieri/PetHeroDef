<?php 

namespace DAO;

use \Exception as Exception;
use Models\Admin as Admin;
use Interfaces\IRepositoriesBasic as IRepositoriesBasic;

class AdminDAO implements IRepositoriesBasic{

    private $connection;
    private $tableName = "admin";

    /**
     * @param Admin $admin
     */
    public function Add($admin)
    {
        
        $admCode = null;
        try {

            if (!$admin instanceof Admin) {
                throw new Exception('Se espera que el parametro sea una instancia de Admin');
            }
            
            $query = "INSERT INTO " . $this->tableName . " (email,password,status,dni,adminCode)
            VALUES (:email,:password,:status,:dni,:adminCode) ;";

            $this->connection = Connection::GetInstance();


            $parameters["email"] = $admin->getEmail();
            $parameters["password"] = $admin->getPassword();
            $parameters["status"] = $admin->getStatus();
            $parameters["dni"] = $admin->getDni();
            $parameters["adminCode"] = $admin->getAdminCode();



            $resultInsert = $this->connection->ExecuteNonQuery($query, $parameters);
            if ($resultInsert == 1) {
                $admCode = $admin->getAdminCode();
            }
        } catch (Exception $ex) {
            throw $ex;
        }
        return $admCode;
    }

    public function GetAll()
    {
        try {

            $adminList = array();

            $query = "SELECT *  FROM " . $this->tableName;

            $this->connection = Connection::GetInstance();

            $resultSet = $this->connection->Execute($query);

            foreach ($resultSet as $value) {
                $admin = new Admin();

                $admin->setId($value["id"]);
                $admin->setEmail($value["email"]);
                $admin->setPassword($value["password"]);
                $admin->setDni($value["dni"]);
                $admin->setStatus($value["status"]);
                $admin->setAdminCode($value["adminCode"]);


                array_push($adminList, $admin);
            }

            return $adminList;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    //Habria que poner un restrict muy especifico para eliminar 
    public function delete($code)
    {
        try {

            $query = "DELETE FROM " . $this->tableName . " 
                WHERE adminCode = :adminCode ;";

            $this->connection = Connection::GetInstance();

            $parameter["adminCode"] = $code;

            return $this->connection->ExecuteNonQuery($query, $parameter);
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
                $admin = null;
            } else {
                foreach ($resultSet as $value) {
                    $admin = new Admin();

                    $admin->setId($value["id"]);
                    $admin->setAdminCode($value["adminCode"]);
                    $admin->setEmail($value["email"]);
                    $admin->setPassword($value["password"]);
                    $admin->setStatus($value["status"]);
                    $admin->setDni($value["dni"]);

                }
            }
        } catch (Exception $ex) {
            throw $ex;
        }
        return $admin;
    }


    public function updateStatus($code, $status)
    {
        try {

            $query = "UPDATE " . $this->tableName . " 
            SET status = :status  
            WHERE adminCode = :code ;";

            $this->connection = Connection::GetInstance();

            $parameters["code"] = $code;
            $parameters["status"] = $status;

            return $this->connection->ExecuteNonQuery($query, $parameters);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function searchByCode($adminCode)
    {
        try {
            $query = "SELECT * FROM " . $this->tableName . "
            WHERE adminCode = :adminCode;";

            $parameters["adminCode"] = $adminCode;

            $this->connection = Connection::GetInstance();

            $resultSet = $this->connection->Execute($query, $parameters);

            if ($resultSet == null) {
                $admin = null;
            } else {
                foreach ($resultSet as $value) {
                    $admin = new Admin();

                    $admin->setId($value["id"]);
                    $admin->setAdminCode($value["adminCode"]);
                    $admin->setEmail($value["email"]);
                    $admin->setPassword($value["password"]);
                    $admin->setStatus($value["status"]);
                    $admin->setDni($value["dni"]);
                }
            }
        } catch (Exception $ex) {
            throw $ex;
        }
        return $admin;
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



}

?>