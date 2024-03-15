<?php

namespace DAO;

use \Exception as Exception;
use DAO\QueryType as QueryType;
use DAO\Connection as Connection;
use Models\Owner as Owner;
use Interfaces\IDAO as IDAO;
use Models\Keeper as Keeper;


class KeeperDAO extends IDAO
{

    private $tableName = "keeper";
    private $connection;

    public function Add(Keeper $keeper)
    {
        try {

            $query = "INSERT INTO " . $this->tableName . " (keeperCode,email,username,password,status,name,lastname,dni,pfp,typeCare,price,typePet,score,initDate,endDate,visitPerDay)
            VALUES (:keeperCode,:email,:username,:password,:status,:name,:lastname,:dni,:pfp,:typeCare,:price,:typePet,:score,:initDate,:endDate,:visitPerDay); ";


            $this->connection = Connection::GetInstance();


            $parameters["keeperCode"] = $keeper->getKeeperCode();
            $parameters["email"] = $keeper->getEmail();
            $parameters["username"] = $keeper->getUserName();
            //Averiguar como encriptar pass
            $parameters["password"] = $keeper->getPassword();
            $parameters["status"] = $keeper->getStatus();
            $parameters["name"] = $keeper->getName();
            $parameters["lastname"] = $keeper->getLastname();
            $parameters["dni"] = $keeper->getDni();
            $parameters["pfp"] = $keeper->getPfp();
            $parameters["typeCare"] = $keeper->getTypeCare();
            $parameters["price"] = $keeper->getPrice();
            $parameters["typePet"] = $keeper->getTypePet();
            $parameters["score"] = null;
            $parameters["initDate"] = $keeper->getInitDate();
            $parameters["endDate"] = $keeper->getEndDate();
            $parameters["visitPerDay"] = $keeper->getVisitPerDay();


            $result = $this->connection->ExecuteNonQuery($query, $parameters);

            if ($result == 1) {
                $keeperCode = $keeper->getKeeperCode();
            } else {
                $keeperCode = null;
            }


            return $keeperCode;
        } catch (Exception $ex) {
            echo $ex->getMessage();
            throw $ex;
        }
    }


    //El getAll deberia discriminar el status del keeper (O sea si esta activo o no)
    public function GetAll()
    {

        try {

            $keeperList = array();

            $query = "SELECT * FROM " . $this->tableName;

            $this->connection = Connection::GetInstance();

            $resultSet = $this->connection->Execute($query);

            foreach ($resultSet as $value) {
                $keeper = new Keeper();

                $keeper->setId($value["id"]);
                $keeper->setKeeperCode($value["keeperCode"]);
                $keeper->setEmail($value["email"]);
                $keeper->setUserName($value["username"]);
                $keeper->setPassword($value["password"]);
                $keeper->setStatus($value["status"]);
                $keeper->setName($value["name"]);
                $keeper->setLastname($value["lastname"]);
                $keeper->setDni($value["dni"]);
                $keeper->setPfp($value["pfp"]);
                $keeper->setTypeCare($value["typeCare"]);
                $keeper->setPrice($value["price"]);
                $keeper->setTypePet($value["typePet"]);
                $keeper->setScore($value["score"]);
                $keeper->setInitDate($value["initDate"]);
                $keeper->setEndDate($value["endDate"]);
                $keeper->setVisitPerDay($value["visitPerDay"]);


                array_push($keeperList, $keeper);
            }


            return $keeperList;
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
                $keeper = null;
            } else {
                foreach ($resultSet as $value) {
                    $keeper = new Keeper();

                    $keeper->setId($value["id"]);
                    $keeper->setKeeperCode($value["keeperCode"]);
                    $keeper->setEmail($value["email"]);
                    $keeper->setUserName($value["username"]);
                    $keeper->setPassword($value["password"]);
                    $keeper->setStatus($value["status"]);
                    $keeper->setName($value["name"]);
                    $keeper->setLastname($value["lastname"]);
                    $keeper->setDni($value["dni"]);
                    $keeper->setPfp($value["pfp"]);
                    $keeper->setTypeCare($value["typeCare"]);
                    $keeper->setPrice($value["price"]);
                    $keeper->setTypePet($value["typePet"]);
                    $keeper->setScore($value["score"]);
                    $keeper->setInitDate($value["initDate"]);
                    $keeper->setEndDate($value["endDate"]);
                    $keeper->setVisitPerDay($value["visitPerDay"]);
                }
            }

            return $keeper;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function searchByUsername($username)
    {
        try {
            $query = "SELECT * FROM " . $this->tableName . "
            WHERE username = :username;";

            $parameters["username"] = $username;

            $this->connection = Connection::GetInstance();

            //Asumo que con Laravel+ORM te podes ahorrar todo esto de setear TODAS las variables pero bueno...
            $resultSet = $this->connection->Execute($query, $parameters);

            if ($resultSet == null) {
                $keeper = null;
            } else {
                foreach ($resultSet as $value) {
                    $keeper = new Keeper();

                    $keeper->setId($value["id"]);
                    $keeper->setkeeperCode($value["keeperCode"]);
                    $keeper->setEmail($value["email"]);
                    $keeper->setUserName($value["username"]);
                    $keeper->setPassword($value["password"]);
                    $keeper->setStatus($value["status"]);
                    $keeper->setName($value["name"]);
                    $keeper->setLastname($value["lastname"]);
                    $keeper->setDni($value["dni"]);
                    $keeper->setPfp($value["pfp"]);
                    $keeper->setTypeCare($value["typeCare"]);
                    $keeper->setPrice($value["price"]);
                    $keeper->setTypePet($value["typePet"]);
                    $keeper->setScore($value["score"]);
                    $keeper->setInitDate($value["initDate"]);
                    $keeper->setEndDate($value["endDate"]);
                    $keeper->setVisitPerDay($value["visitPerDay"]);
                }
            }
            return $keeper;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function searchByKeeperCode($keeperCode)
    {
        try {
            $query = "SELECT * FROM " . $this->tableName . "
            WHERE keeperCode = :keeperCode;";

            $parameters["keeperCode"] = $keeperCode;

            $this->connection = Connection::GetInstance();

            //Asumo que con Laravel+ORM te podes ahorrar todo esto de setear TODAS las variables pero bueno...
            $resultSet = $this->connection->Execute($query, $parameters);

            if ($resultSet == null) {
                $keeper = null;
            } else {
                foreach ($resultSet as $value) {
                    $keeper = new Keeper();

                    $keeper->setId($value["id"]);
                    $keeper->setkeeperCode($value["keeperCode"]);
                    $keeper->setEmail($value["email"]);
                    $keeper->setUserName($value["username"]);
                    $keeper->setPassword($value["password"]);
                    $keeper->setStatus($value["status"]);
                    $keeper->setName($value["name"]);
                    $keeper->setLastname($value["lastname"]);
                    $keeper->setDni($value["dni"]);
                    $keeper->setPfp($value["pfp"]);
                    $keeper->setTypeCare($value["typeCare"]);
                    $keeper->setPrice($value["price"]);
                    $keeper->setTypePet($value["typePet"]);
                    $keeper->setScore($value["score"]);
                    $keeper->setInitDate($value["initDate"]);
                    $keeper->setEndDate($value["endDate"]);
                    $keeper->setVisitPerDay($value["visitPerDay"]);
                }
            }
            return $keeper;
        } catch (Exception $ex) {
            throw $ex;
        }
    }



    //Esta bien para traer la info en gral de Keeper y para entender la logica
    //Pero para displayear la info completa no sirve,deberia hacer un left join/right join para que no haya keepers repetidos  pero si sus avails
    public function getKeeperFullInfo()
    {
        try {

            $arrayFullInfo = array();

            $query = "SELECT * FROM " . $this->tableName .";";

            $this->connection = Connection::GetInstance();

            $resultSet = $this->connection->Execute($query);

            foreach ($resultSet as $row){
                    $keeper = new Keeper();

                    $keeper->setId($row["id"]);
                    $keeper->setkeeperCode($row["keeperCode"]);
                    $keeper->setEmail($row["email"]);
                    $keeper->setUserName($row["username"]);
                    $keeper->setPassword($row["password"]);
                    $keeper->setStatus($row["status"]);
                    $keeper->setName($row["name"]);
                    $keeper->setLastname($row["lastname"]);
                    $keeper->setDni($row["dni"]);
                    $keeper->setPfp($row["pfp"]);
                    $keeper->setTypeCare($row["typeCare"]);
                    $keeper->setPrice($row["price"]);
                    $keeper->setTypePet($row["typePet"]);
                    $keeper->setScore($row["score"]);
                    $keeper->setInitDate($row["initDate"]);
                    $keeper->setEndDate($row["endDate"]);
                    $keeper->setVisitPerDay($row["visitPerDay"]);


                    array_push($arrayFullInfo, $keeper);
                } 
            
            return $arrayFullInfo;

            //var_dump($arrayFullInfo);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    //Ahora
    public function getKeepersPagination($pageNumber, $resultsPerPage)
    {
        try {

            $offset = ($pageNumber - 1) * $resultsPerPage;

            $arrayFullInfo = array();

            $query = "SELECT * FROM " . $this->tableName . " LIMIT $offset, $resultsPerPage;";

            $this->connection = Connection::GetInstance();

            // $parameters["offSet"] =  $offset;
            // $parameters["resultsPp"] =  $resultsPerPage;

            $resultSet = $this->connection->Execute($query);

            $arrayKeepers = array();
            foreach ($resultSet as $row) {

    
                    $keeper = new Keeper();

                    $keeper->setId($row["id"]);
                    $keeper->setkeeperCode($row["keeperCode"]);
                    $keeper->setEmail($row["email"]);
                    $keeper->setUserName($row["username"]);
                    $keeper->setPassword($row["password"]);
                    $keeper->setStatus($row["status"]);
                    $keeper->setName($row["name"]);
                    $keeper->setLastname($row["lastname"]);
                    $keeper->setDni($row["dni"]);
                    $keeper->setPfp($row["pfp"]);
                    $keeper->setTypeCare($row["typeCare"]);
                    $keeper->setPrice($row["price"]);
                    $keeper->setTypePet($row["typePet"]);
                    $keeper->setScore($row["score"]);
                    $keeper->setInitDate($row["initDate"]);
                    $keeper->setEndDate($row["endDate"]);
                    $keeper->setVisitPerDay($row["visitPerDay"]);


                    array_push($arrayKeepers, $keeper);
            }

            return $arrayKeepers;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function getKeepersByDates($initDate, $endDate,$size, $typePet,$visitPerDay,$pageNumber, $resultsPerPage)
    {
        try {


            $offset = ($pageNumber - 1) * $resultsPerPage;

            $query = "CALL GetFilteredKeepers(?,?,?,?,?,?,?);";
            //"CALL GetFilteredKeepers(:p_initDate,:p_endDate,:p_initHour,:p_endHour,:p_size,:p_typePet);"

            $parameters["p_initDate"] = $initDate;
            $parameters["p_endDate"] = $endDate;
            $parameters["p_typePet"] = $typePet;
            $parameters["p_sizePet"] = $size;
            $parameters["p_visitPerDay"] = $visitPerDay;
            $parameters["p_offset"] = $offset;
            $parameters["p_resultsPerPage"] = $resultsPerPage;

            $this->connection = Connection::GetInstance();
            var_dump($parameters);
            $resultSet = $this->connection->Execute($query, $parameters, QueryType::StoredProccedure);
            $arrayFullInfo = array();
            foreach ($resultSet as $row) {

                    $keeper = new Keeper();

                    $keeper->setId($row["id"]);
                    $keeper->setkeeperCode($row["keeperCode"]);
                    $keeper->setEmail($row["email"]);
                    $keeper->setUserName($row["username"]);
                    $keeper->setPassword($row["password"]);
                    $keeper->setStatus($row["status"]);
                    $keeper->setName($row["name"]);
                    $keeper->setLastname($row["lastname"]);
                    $keeper->setDni($row["dni"]);
                    $keeper->setPfp($row["pfp"]);
                    $keeper->setTypeCare($row["typeCare"]);
                    $keeper->setPrice($row["price"]);
                    $keeper->setTypePet($row["typePet"]);
                    $keeper->setScore($row["score"]);
                    $keeper->setInitDate($row["initDate"]);
                    $keeper->setEndDate($row["endDate"]);
                    $keeper->setVisitPerDay($row["visitPerDay"]);


                    array_push($arrayFullInfo, $keeper);
                } 
            
            var_dump($arrayFullInfo);
            return $arrayFullInfo;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    //Con esto queda obsoleto todas las contraseñas guardadas en plano,solo logean aquellos keepers con contraseña hasheada
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
            var_dump($pwd);
            return $pwd;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    //Revalidar que coincida los atributos del pet con lo que cuida el keeper para ver que el booking ta ok
    public function revalidateKeeperPet($keeperCode, $petCode)
    {
        try {
            $query = "SELECT COUNT(*) AS result
            FROM " . $this->tableName . " 
            WHERE keeperCode = :keeperCode
            AND typePet = (
                SELECT typePet
                FROM pet
                WHERE petCode = :petCode
            )
            AND typeCare = (
                SELECT size
                FROM pet
                WHERE petCode = :petCode
            );";

            $this->connection = Connection::GetInstance();

            $parameters["keeperCode"] = $keeperCode;
            $parameters["petCode"] = $petCode;

            $resultSet = $this->connection->Execute($query, $parameters);

            $arrayRes = array_shift($resultSet);
            $result = $arrayRes["result"];

            return $result;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function updateAvailability($keeperCode,$initDate,$endDate)
    {
        try{
            $query = "UPDATE ".$this->tableName." 
            SET initDate = :initDate,endDate = :endDate 
            WHERE keeperCode = :keeperCode;";

            $this->connection = Connection::GetInstance();

            $parameters["keeperCode"] = $keeperCode;
            $parameters["initDate"] = $initDate;
            $parameters["endDate"] = $endDate;

            $result = $this->connection->ExecuteNonQuery($query,$parameters);

            return $result;
        }catch(Exception $ex)
        {
            throw $ex;
        }
    }

    public function updatePfp($keeperCode, $pfp)
    {
        try {
            $query = "UPDATE keeper SET pfp = :pfp WHERE keeperCode = :keeperCode ;";

            $this->connection = Connection::GetInstance();

            $parameters["keeperCode"] = $keeperCode;
            $parameters["pfp"] = $pfp;

            return $this->connection->ExecuteNonQuery($query, $parameters);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function updateEmail($keeperCode,$email){
        try{
            $query = "UPDATE ".$this->tableName." 
            SET email = :email 
            WHERE keeperCode = :keeperCode;";

            $this->connection = Connection::GetInstance();

            $parameters["keeperCode"] = $keeperCode;
            $parameters["email"] = $email;

            $result = $this->connection->ExecuteNonQuery($query,$parameters);

            return $result;

        }catch(Exception $ex)
        {
            throw $ex;
        }
    }

    public function updateBio($keeperCode,$bio){
        try{
            $query = "UPDATE ".$this->tableName." 
            SET bio = :bio
            WHERE keeperCode = :keeperCode;";

            $this->connection = Connection::GetInstance();

            $parameters["keeperCode"] = $keeperCode;
            $parameters["bio"] = $bio;

            $result = $this->connection->ExecuteNonQuery($query,$parameters);

            return $result;
        }catch(Exception $ex){
            throw $ex;
        }
    }

    public function updatePrice($keeperCode,$price)
    {
        try{
            $query = "UPDATE ".$this->tableName." 
            SET price = :price
            WHERE keeperCode = :keeperCode;";

            $this->connection = Connection::GetInstance();

            $parameters["keeperCode"] = $keeperCode;
            $parameters["price"] = $price;

            $result = $this->connection->ExecuteNonQuery($query,$parameters);

            return $result;
        }catch(Exception $ex){
            throw $ex;
        }
    }

    public function getDatesByCode($keeperCode)
    {
        try{

            $query = "SELECT initDate,endDate FROM ".$this->tableName." 
            WHERE keeperCode = :keeperCode;";

            $this->connection = Connection::GetInstance();

            $parameter["keeperCode"] = $keeperCode;

            $resultSet = $this->connection->Execute($query,$parameter);

            $arrayDates = array();
            foreach($resultSet as $row)
            {
                $arrayDates["initDate"] = $row["initDate"];
                $arrayDates["endDate"] = $row["endDate"];
            }

            return $arrayDates;
        }catch(Exception $ex)
        {
            throw $ex;
        }
    }
}
