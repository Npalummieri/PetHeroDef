<?php

namespace DAO;

use DateTime;
use Models\Coupon as Coupon;
use Exception;
use Models\Status;


class CouponDAO
{

    private $tableName = "coupon";
    private $connection;

    public function Add(Coupon $coupon)
    {
        try {

            $query = "INSERT INTO " . $this->tableName . "(couponCode,bookCode,price,status)
            VALUES (:couponCode,:bookCode,:price,:status);";

            $this->connection = Connection::GetInstance();

            $parameters["couponCode"] = $coupon->getCouponCode();
            $parameters["bookCode"] = $coupon->getBookCode();
            $parameters["price"] = $coupon->getPrice();
            $parameters["status"] = Status::PENDING;

            $result = $this->connection->ExecuteNonQuery($query, $parameters);

            if ($result == 1) {
                $coupCode = $coupon->getCouponCode();
            } else {
                $coupCode = null;
            }

            return $coupCode;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function searchByCode($couponCode)
    {
        try {

            $query = "SELECT * FROM " . $this->tableName . "
            WHERE couponCode = :couponCode;";

            $parameters["couponCode"] = $couponCode;

            $this->connection = Connection::GetInstance();

            $resultSet = $this->connection->Execute($query, $parameters);

            $coupon = new Coupon();

            foreach ($resultSet as $value) {
                $coupon->setId($value["id"]);
                $coupon->setCouponCode($value["couponCode"]);
                $coupon->setBookCode($value["bookCode"]);
                $coupon->setPrice($value["price"]);
                $coupon->setstatus($value["status"]);
            }

            return $coupon;
        } catch (Exception $ex) {
            throw $ex;
        }
    }


    public function getFullInfoCoupon($couponCode)
    {
        try {
            $query = "SELECT c.couponCode,c.bookCode,b.ownerCode as ownerCode,b.keeperCode as keeperCode,b.initDate,b.endDate,b.totalPrice,b.visitPerDay,p.name as namePet,p.typePet,p.breed,p.size,o.email as emailOwner,o.name as ownerName,o.lastname as olastname,k.name as kname,k.lastname as klastname,k.email as emailKeeper,k.pfp as pfpk,c.status as statusCoup
            FROM coupon as c
            JOIN booking as b
            ON b.bookCode = c.bookCode
            JOIN owner as o
            ON b.ownerCode = o.ownerCode
            JOIN keeper as k
            ON k.keeperCode = b.keeperCode
            JOIN pet as p
            ON p.petCode = b.petCode
            WHERE c.couponCode = :couponCode;"; //AND c.bookCode = :bookCode;";


            $parameters["couponCode"] = $couponCode;

            $this->connection = Connection::GetInstance();

            $resultSet = $this->connection->Execute($query, $parameters);

            $couponArrayInfo = array();

            foreach ($resultSet as $value) {
                $couponArrayInfo["couponCode"] = $value["couponCode"];
                $couponArrayInfo["bookCode"] = $value["bookCode"];
                $couponArrayInfo["keeperCode"] = $value["keeperCode"];
                $couponArrayInfo["ownerCode"] = $value["ownerCode"];
                $couponArrayInfo["initDate"] = $value["initDate"];
                $couponArrayInfo["endDate"] = $value["endDate"];
                $couponArrayInfo["totalPrice"] = $value["totalPrice"];
                $couponArrayInfo["visitPerDay"] = $value["visitPerDay"];
                $couponArrayInfo["namePet"] = $value["namePet"];
                $couponArrayInfo["typePet"] = $value["typePet"];
                $couponArrayInfo["breed"] = $value["breed"];
                $couponArrayInfo["size"] = $value["size"];
                $couponArrayInfo["emailOwner"] = $value["emailOwner"];
                $couponArrayInfo["ownerName"] = $value["ownerName"];
                $couponArrayInfo["olastname"] = $value["olastname"];
                $couponArrayInfo["kname"] = $value["kname"];
                $couponArrayInfo["klastname"] = $value["klastname"];
                $couponArrayInfo["emailKeeper"] = $value["emailKeeper"];
                $couponArrayInfo["pfpk"] = $value["pfpk"];
                $couponArrayInfo["statusCoup"] = $value["statusCoup"];
            }
        } catch (Exception $ex) {
            throw $ex;
        }
        return $couponArrayInfo;
    }

    public function getAllCouponsByOwner($ownerCode)
    {
        try {

            $query = "SELECT c.couponCode,c.bookCode
            FROM " . $this->tableName . " as c
            JOIN booking as b
            ON c.bookCode = b.bookCode
            WHERE b.ownerCode = :ownerCode;";

            $this->connection = Connection::GetInstance();

            $parameters["ownerCode"] = $ownerCode;

            $resultSet = $this->connection->Execute($query, $parameters);

            $couponsByOwnerCodeArr = array();
            foreach ($resultSet as $value) {
                $coupInfo["couponCode"] = $value["couponCode"];
                $coupInfo["bookCode"] = $value["bookCode"];


                $couponFullInfo = $this->getFullInfoCoupon($coupInfo["couponCode"], $coupInfo["bookCode"]);

                array_push($couponsByOwnerCodeArr, $couponFullInfo);
            }

            return $couponsByOwnerCodeArr;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function updateStatus($couponCode,$status){
        try {

            $query = "UPDATE " . $this->tableName . " as c
            SET c.status = :statusCoup
            WHERE c.couponCode = :couponCode;"; /*JOIN booking as b
            ON b.bookCode = c.bookCode AND c.bookCode = :bookCode ;*/

            $this->connection = Connection::GetInstance();

            $parameters["statusCoup"] = $status;
            $parameters["couponCode"] = $couponCode;

            $res = $this->connection->ExecuteNonQuery($query, $parameters);

            return $res;
        } catch (Exception $ex) {
            throw $ex;
        }
    }


    //Paidup exit --> coupon & booking = paidup
    public function paidUpCoupon($couponCode)
    {
        try {

            $query = "UPDATE " . $this->tableName . " as c
            SET c.status = :statusCoup
            WHERE c.couponCode = :couponCode;"; /*JOIN booking as b
            ON b.bookCode = c.bookCode AND c.bookCode = :bookCode ;*/

            $this->connection = Connection::GetInstance();

            $parameters["statusCoup"] = Status::PAIDUP;
            $parameters["couponCode"] = $couponCode;

            $res = $this->connection->ExecuteNonQuery($query, $parameters);

            return $res;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function declineCoupon($couponCode)
    {
        try {
            $query = "UPDATE coupon 
            SET status = :status 
            WHERE couponCode = :couponCode;";

            $this->connection = Connection::GetInstance();

            $parameters["couponCode"] = $couponCode;
            $parameters["status"] = Status::CANCELLED;
            $result = $this->connection->ExecuteNonQuery($query, $parameters);


            if ($result == 1) {
                $queryForJoin = "SELECT bookCode FROM " . $this->tableName . " 
                WHERE couponCode = :couponCode;";

                $parameterQ["couponCode"] = $couponCode;


                $resultqJoin = $this->connection->Execute($queryForJoin, $parameterQ);

                //Medio innecesario el join teniendo en cuenta que el bookCode/coupCode es 1:1 entre Coup/Book
                $queryBooking = "UPDATE booking AS b
                JOIN coupon AS c 
                ON b.bookCode = c.bookCode
                SET b.status = :status;
                WHERE b.bookCode = :bookCode;";

                $parameter["bookCode"] = $resultqJoin[0]["bookCode"];
                //Cancelled para seguir la logica de que el dueño la CANCELA y no la rechaza(rejected) el keeper
                $parameter["status"] = Status::CANCELLED;

                $resultUpdate = $this->connection->ExecuteNonQuery($queryBooking, $parameter);
            }

            if ($resultUpdate == 1) {
                $bookCodeValue = $resultqJoin[0]["bookCode"];
                $queryPunish = "UPDATE owner as o 
                JOIN booking as b
                ON b.ownerCode = o.ownerCode
                SET o.status = :status , suspensiondate = :suspdate
                WHERE b.bookCode = :bookCode";

                $this->connection = Connection::GetInstance();

                $suspensionDate = new DateTime();
                $suspensionDateFormatted = $suspensionDate->format('Y-m-d H:i:s');

                $parametersP["bookCode"] = $bookCodeValue;
                $parametersP["status"] = Status::SUSPENDED;
                $parametersP["suspdate"] = $suspensionDateFormatted; 

                $resultPunish = $this->connection->ExecuteNonQuery($queryPunish, $parametersP);
            }
            return $resultPunish;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function getCoupCodeByBookCode($bookCode)
    {
        try {

            $query = "SELECT couponCode FROM " . $this->tableName . " 
            WHERE bookCode = :bookCode;";

            $this->connection = Connection::GetInstance();

            $parameter["bookCode"] = $bookCode;

            $resultSet = $this->connection->Execute($query, $parameter);
            
            
        } catch (Exception $ex) {
            throw $ex;
        }
        return $resultSet[0][0];
    }

    //check if the coupon we try to see matchs with the ownerLogged in their booking
    public function checkCouponOwner($couponCode, $ownerCodeLogged)
    {
        try {
            $query = "SELECT COUNT(*) FROM " . $this->tableName . " as c
            JOIN booking as b
            ON c.bookCode = b.bookCode 
            WHERE couponCode = :couponCode AND b.ownerCode = :ownerCodeLogged;";

            $parameters["couponCode"] = $couponCode;
            $parameters["ownerCodeLogged"] = $ownerCodeLogged;

            $this->connection = Connection::GetInstance();

            $result = $this->connection->Execute($query, $parameters);

            return $result[0][0];
        } catch (Exception $ex) {
            throw $ex;
        }
    }
	
	public function getAll()
	{
		try {

            $query = "SELECT * FROM " . $this->tableName;

            $this->connection = Connection::GetInstance();

            $resultSet = $this->connection->Execute($query);

            
			$arrayCoups = array();
			
            foreach ($resultSet as $value) {
				
				$coupon = new Coupon();
				
                $coupon->setId($value["id"]);
                $coupon->setCouponCode($value["couponCode"]);
                $coupon->setBookCode($value["bookCode"]);
                $coupon->setPrice($value["price"]);
                $coupon->setstatus($value["status"]);
				
				array_push($arrayCoups,$coupon);
            }

            
        } catch (Exception $ex) {
            throw $ex;
        }
		return $arrayCoups;
	}
	
	//Only modify bookings = finished / paidup
	public function updatePrice($coupCode,$price)
	{
		try{
			$query = "UPDATE ".$this->tableName." 
			SET price = :price
			WHERE couponCode = :coupCode AND (status != :finished AND status != :paidup);";
			
			$this->connection = Connection::GetInstance();
			
			$parameters["coupCode"] = $coupCode;
			$parameters["price"] = $price;
			$parameters["finished"] = Status::FINISHED ;
			$parameters["paidup"] = Status::PAIDUP;
			
			return $this->connection->ExecuteNonQuery($query,$parameters);
		}catch(Exception $ex)
		{
			throw $ex;
		}
	}
	
	public function getFilteredCoupsAdm($code)
	{
		try{
			$query ="SELECT * FROM ".$this->tableName;
			if (strpos($code, "BOOK") !== false) {
            $query .= " WHERE bookCode LIKE CONCAT(:code, '%')";
        } elseif (strpos($code, "COU") !== false) {
            $query .= " WHERE coupCode LIKE CONCAT(:code, '%')";
		}
			$this->connection = Connection::GetInstance();
			
			$parameter["code"] = $code;
			
			$resultSet = $this->connection->Execute($query,$parameter);
			
			$couponsFiltered = array();
			foreach($resultSet as $coupon)
			{
				$coup = new Coupon();
				
				$coup->setId($coupon["id"]);
                $coup->setBookCode($coupon["bookCode"]);
                $coup->setCouponCode($coupon["couponCode"]);
                $coup->setPrice($coupon["price"]);
                $coup->setStatus($coupon["status"]);
       	
				array_push($couponsFiltered,$coup);
				
			}
			
			return $couponsFiltered;
		}catch(Exception $ex)
		{
			throw $ex;
		}
	}
}
