<?php

namespace DAO;

use \Exception as Exception;
use DAO\QueryType as QueryType;
use DAO\Connection as Connection;
use Models\Keeper as Keeper;
use Interfaces\IRepositoriesBasic as IRepositoriesBasic;
use Interfaces\IRepositoriesExtendUser as IRepositoriesExtendUser;

class KeeperDAO implements IRepositoriesBasic,IRepositoriesExtendUser
{

    private $tableName = "keeper";
    private $connection;


    /**
     * @param Keeper $keeper
     */
    public function Add($keeper)
    {
        
        try {

            if (!$keeper instanceof Keeper) {
                throw new Exception('Se espera que el parametro sea una instancia de Keeper');
            }

            $query = "INSERT INTO " . $this->tableName . " (keeperCode,email,username,password,status,name,lastname,dni,pfp,typeCare,price,typePet,score,initDate,endDate,visitPerDay)
            VALUES (:keeperCode,:email,:username,:password,:status,:name,:lastname,:dni,:pfp,:typeCare,:price,:typePet,:score,:initDate,:endDate,:visitPerDay); ";


            $this->connection = Connection::GetInstance();


            $parameters["keeperCode"] = $keeper->getKeeperCode();
            $parameters["email"] = $keeper->getEmail();
            $parameters["username"] = $keeper->getUserName();
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
            throw $ex;
        }
    }

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
        } catch (Exception $ex) {
            throw $ex;
        }
        return $keeperList;
    }

    public function delete($code)
	{
		try{
			
			$query = "DELETE FROM ".$this->tableName." 
			WHERE keeperCode = :keeperCode;";
			
			$this->connection = Connection::GetInstance();
			
			$parameter["keeperCode"] = $code;
			
			return $this->connection->ExecuteNonQuery($query,$parameter);
			
			
		}catch(Exception $ex)
		{
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
                    $keeper->setBio($value["bio"]);
                }
            }
        } catch (Exception $ex) {
            throw $ex;
        }
        return $keeper;
    }

    public function updateStatus($code, $status)
    {
        try {

            $query = "UPDATE " . $this->tableName . " 
            SET status = :status  
            WHERE keeperCode = :code ;";

            $this->connection = Connection::GetInstance();

            $parameters["code"] = $code;
            $parameters["status"] = $status;

            return $this->connection->ExecuteNonQuery($query, $parameters);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function searchByCode($keeperCode)
    {
        try {
            $query = "SELECT * FROM " . $this->tableName . "
            WHERE keeperCode = :keeperCode;";

            $parameters["keeperCode"] = $keeperCode;

            $this->connection = Connection::GetInstance();

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
                    $keeper->setBio($value["bio"]);
                }
            }
        } catch (Exception $ex) {
            throw $ex;
        }
        return $keeper;
    }

    public function checkDni($dni)
    {
        try {
            $query = "SELECT COUNT(*) FROM " . $this->tableName . " 
            WHERE dni = :dni;";

            $this->connection = Connection::GetInstance();

            $parameter["dni"] = $dni;

            $result = $this->connection->Execute($query, $parameter);

            return $result[0][0];
        } catch (Exception $ex) {
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

    public function searchByUsername($username)
    {
        try {
            $query = "SELECT * FROM " . $this->tableName . "
            WHERE username = :username;";

            $parameters["username"] = $username;

            $this->connection = Connection::GetInstance();

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

    public function updateEmail($keeperCode, $email)
    {
        try {
            $query = "UPDATE " . $this->tableName . " 
            SET email = :email 
            WHERE keeperCode = :keeperCode;";

            $this->connection = Connection::GetInstance();

            $parameters["keeperCode"] = $keeperCode;
            $parameters["email"] = $email;

            $result = $this->connection->ExecuteNonQuery($query, $parameters);

            return $result;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function updateUsername($keeperCode, $username)
    {
        try {

            $query = "UPDATE " . $this->tableName . " 
            SET username = :username 
            WHERE keeperCode = :keeperCode ;";

            $this->connection = Connection::GetInstance();

            $parameters["username"] = $username;
            $parameters["keeperCode"] = $keeperCode;

            return $this->connection->ExecuteNonQuery($query, $parameters);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function updateName($keeperCode, $name)
    {
        try {
            $query = "UPDATE " . $this->tableName . " 
            SET name = :name 
            WHERE keeperCode = :keeperCode ;";

            $this->connection = Connection::GetInstance();

            $parameters["name"] = $name;
            $parameters["keeperCode"] = $keeperCode;

            return $this->connection->ExecuteNonQuery($query, $parameters);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function updateLastname($keeperCode, $lastname)
    {
        try {
            $query = "UPDATE " . $this->tableName . " 
            SET lastname = :lastname 
            WHERE keeperCode = :keeperCode ;";

            $this->connection = Connection::GetInstance();

            $parameters["lastname"] = $lastname;
            $parameters["keeperCode"] = $keeperCode;

            return $this->connection->ExecuteNonQuery($query, $parameters);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function getKeeperFullInfo()
    {
        try {

            $arrayFullInfo = array();

            $query = "SELECT * FROM " . $this->tableName . ";";

            $this->connection = Connection::GetInstance();

            $resultSet = $this->connection->Execute($query);

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
        } catch (Exception $ex) {
            throw $ex;
        }
        return $arrayFullInfo;
    }

    //Ahora
    public function getKeepersPagination($pageNumber, $resultsPerPage)
    {
        try {

            $offset = ($pageNumber - 1) * $resultsPerPage;

            $arrayFullInfo = array();

            $query = "SELECT * FROM " . $this->tableName . " LIMIT $offset, $resultsPerPage;";

            $this->connection = Connection::GetInstance();


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

    public function getKeepersByDates($initDate, $endDate, $size, $typePet, $visitPerDay, $pageNumber, $resultsPerPage)
    {
        try {


            $offset = ($pageNumber - 1) * $resultsPerPage;

            $query = "CALL GetFilteredKeepers(?,?,?,?,?,?,?);";


            $parameters["p_initDate"] = $initDate;
            $parameters["p_endDate"] = $endDate;
            $parameters["p_typePet"] = $typePet;
            $parameters["p_sizePet"] = $size;
            $parameters["p_visitPerDay"] = $visitPerDay;
            $parameters["p_offset"] = $offset;
            $parameters["p_resultsPerPage"] = $resultsPerPage;

            $this->connection = Connection::GetInstance();
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


            return $arrayFullInfo;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    
    //Validate the attr from the pet === keeper takecare
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

    public function updateAvailability($keeperCode, $initDate, $endDate)
    {
        try {
            $query = "UPDATE " . $this->tableName . " 
            SET initDate = :initDate,endDate = :endDate 
            WHERE keeperCode = :keeperCode;";

            $this->connection = Connection::GetInstance();

            $parameters["keeperCode"] = $keeperCode;
            $parameters["initDate"] = $initDate;
            $parameters["endDate"] = $endDate;

            $result = $this->connection->ExecuteNonQuery($query, $parameters);
        } catch (Exception $ex) {
            throw $ex;
        }
        return $result;
    }

    public function updateBio($keeperCode, $bio)
    {
        try {
            $query = "UPDATE " . $this->tableName . " 
            SET bio = :bio
            WHERE keeperCode = :keeperCode;";

            $this->connection = Connection::GetInstance();

            $parameters["keeperCode"] = $keeperCode;
            $parameters["bio"] = $bio;

            $result = $this->connection->ExecuteNonQuery($query, $parameters);

            return $result;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function updatePrice($keeperCode, $price)
    {
        try {
            $query = "UPDATE " . $this->tableName . " 
            SET price = :price
            WHERE keeperCode = :keeperCode;";

            $this->connection = Connection::GetInstance();

            $parameters["keeperCode"] = $keeperCode;
            $parameters["price"] = $price;

            $result = $this->connection->ExecuteNonQuery($query, $parameters);

            return $result;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function getDatesByCode($keeperCode)
    {
        try {

            $query = "SELECT initDate,endDate FROM " . $this->tableName . " 
            WHERE keeperCode = :keeperCode;";

            $this->connection = Connection::GetInstance();

            $parameter["keeperCode"] = $keeperCode;

            $resultSet = $this->connection->Execute($query, $parameter);

            $arrayDates = array();
            foreach ($resultSet as $row) {
                $arrayDates["initDate"] = $row["initDate"];
                $arrayDates["endDate"] = $row["endDate"];
            }

            return $arrayDates;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function updateVisitDay($keeperCode, $visit)
    {
        try {
            $query = "UPDATE " . $this->tableName . " 
            SET visitPerDay = :visit 
            WHERE keeperCode = :keeperCode";

            $parameters["keeperCode"] = $keeperCode;
            $parameters["visit"] = $visit;
            $this->connection = Connection::GetInstance();

            $result = $this->connection->ExecuteNonQuery($query, $parameters);

            return $result;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function updateTypeCare($keeperCode, $typeCare)
    {
        try {
            $query = "UPDATE " . $this->tableName . " 
            SET typeCare = :typeCare 
            WHERE keeperCode = :keeperCode ;";

            $this->connection = Connection::GetInstance();

            $parameters["typeCare"] = $typeCare;
            $parameters["keeperCode"] = $keeperCode;

            return $this->connection->ExecuteNonQuery($query, $parameters);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function updateTypePet($keeperCode, $typePet)
    {
        try {
            $query = "UPDATE " . $this->tableName . " 
            SET typePet = :typePet 
            WHERE keeperCode = :keeperCode ;";

            $this->connection = Connection::GetInstance();

            $parameters["typePet"] = $typePet;
            $parameters["keeperCode"] = $keeperCode;

            return $this->connection->ExecuteNonQuery($query, $parameters);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function updateScore($keeperCode, $score)
    {
        try {
            $query = "UPDATE " . $this->tableName . " 
            SET score = :score 
            WHERE keeperCode = :keeperCode ;";

            $this->connection = Connection::GetInstance();

            $parameters["score"] = $score;
            $parameters["keeperCode"] = $keeperCode;

            return $this->connection->ExecuteNonQuery($query, $parameters);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function getFilteredKeepsAdm($code)
    {
        try {
            $query = "SELECT * FROM " . $this->tableName;
            if (strpos($code, "KEP") !== false) {
                $query .= " WHERE keeperCode LIKE CONCAT(:code, '%')";
            } elseif (filter_var($code, FILTER_VALIDATE_EMAIL)) {
                $query .= " WHERE email LIKE CONCAT(:code, '%')";
            } elseif ((preg_match("/^\d{8}$/", $code) == 1)) {
                $query .= " WHERE dni LIKE CONCAT(:code, '%')";
            }
            $this->connection = Connection::GetInstance();

            $parameter["code"] = $code;

            $resultSet = $this->connection->Execute($query, $parameter);

            $keepersFiltered = array();
            foreach ($resultSet as $keeper) {
                $keep = new Keeper();

                $keep->setId($keeper["id"]);
                $keep->setkeeperCode($keeper["keeperCode"]);
                $keep->setEmail($keeper["email"]);
                $keep->setUserName($keeper["username"]);
                $keep->setPassword($keeper["password"]);
                $keep->setStatus($keeper["status"]);
                $keep->setName($keeper["name"]);
                $keep->setLastname($keeper["lastname"]);
                $keep->setDni($keeper["dni"]);
                $keep->setPfp($keeper["pfp"]);
                $keep->setTypeCare($keeper["typeCare"]);
                $keep->setPrice($keeper["price"]);
                $keep->setTypePet($keeper["typePet"]);
                $keep->setScore($keeper["score"]);
                $keep->setInitDate($keeper["initDate"]);
                $keep->setEndDate($keeper["endDate"]);
                $keep->setVisitPerDay($keeper["visitPerDay"]);

                array_push($keepersFiltered, $keep);
            }

            return $keepersFiltered;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

}
