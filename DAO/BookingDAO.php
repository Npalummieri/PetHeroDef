<?php

namespace DAO;

use \Exception as Exception;
use DAO\QueryType as QueryType;
use DAO\Connection as Connection;
use Models\Booking as Booking;
use Models\Owner as Owner;
use Models\Keeper as Keeper;
use Models\Pet as Pet;



class BookingDAO{

    private $tableName = "booking";
    private $connection;

    public function Add(Booking $booking)
    {

        try {
            $query = "INSERT INTO ".$this->tableName."(bookCode,ownerCode,keeperCode,petCode,initDate,endDate,status,totalPrice,totalDays,visitPerDay) 
            VALUES (:bookCode,:ownerCode,:keeperCode,:petCode,:initDate,:endDate,:status,:totalPrice,:totalDays,:visitPerDay);";

            $this->connection = Connection::GetInstance();

            $parameters["bookCode"] = $booking->getBookCode();
            $parameters["ownerCode"] = $booking->getOwnerCode();
            $parameters["keeperCode"] = $booking->getKeeperCode();
            $parameters["petCode"] = $booking->getPetCode();
            $parameters["initDate"] = $booking->getInitDate();
            $parameters["endDate"] = $booking->getEndDate();
            $parameters["status"] = "pending";
            $parameters["totalPrice"] = $booking->getTotalPrice();
            $parameters["totalDays"] = $booking->getTotalDays();
            $parameters["visitPerDay"] = $booking->getVisitPerDay();

            $this->connection = Connection::GetInstance();

            $result = $this->connection->ExecuteNonQuery($query,$parameters);

            if($result == 1)
            {
                $bookCode = $booking->getBookCode();
            }else{
                $bookCode = null;
            }

            return $bookCode;
        } catch (Exception $ex) 
        {
            throw $ex;
        }
    }

    public function checkDoubleBooking($ownerCode,$keeperCode,$petCode,$initDate,$endDate)
    {
        try{

            $query = "SELECT COUNT(*) as result FROM ".$this->tableName."
            WHERE ownerCode = :ownerCode
            AND keeperCode = :keeperCode
            AND petCode = :petCode
            AND initDate = :initDate
            AND endDate = :endDate;";

            $this->connection = Connection::GetInstance();

            $parameters["ownerCode"] = $ownerCode;
            $parameters["keeperCode"] = $keeperCode;
            $parameters["petCode"] = $petCode;
            $parameters["initDate"] = $initDate;
            $parameters["endDate"] = $endDate;



            $result = $this->connection->Execute($query,$parameters);

            return $result[0]["result"];

        }catch(Exception $ex)
        {
            throw $ex;
        }
    }

    public function GetByCode($bookCode)
    {
        try{
            $query = "SELECT * FROM ".$this->tableName."
            WHERE bookCode = :bookCode;";

            $this->connection = Connection::GetInstance();

            $parameters["bookCode"] = $bookCode;

            $resultSet = $this->connection->Execute($query,$parameters);

            foreach($resultSet as $value)
            {
                $booking = new Booking();

                $booking->setId($value["id"]);
                $booking->setBookCode($value["bookCode"]);
                $booking->setOwnerCode($value["ownerCode"]);
                $booking->setKeeperCode($value["keeperCode"]);
                $booking->setPetCode($value["petCode"]);
                $booking->setInitDate($value["initDate"]);
                $booking->setEndDate($value["endDate"]);
                $booking->setStatus($value["status"]);
                $booking->setTotalPrice($value["totalPrice"]);
                $booking->setTotalDays($value["totalDays"]);
                $booking->setVisitPerDay($value["visitPerDay"]);
                $booking->setTotalPrice($value["totalPrice"]);

                
            }

            return $booking;
        }catch(Exception $ex)
        {
            throw $ex;
        }
    }

    public function getBookingsByKeeper($keeperCode)
    {
        try{

            $bookingArray = array();

            $query = "SELECT * FROM".$this->tableName."
            WHERE keeperCode = :keeperCode;";

            $parameters["keeperCode"] = $keeperCode;

            $this->connection = Connection::GetInstance();

            $result = $this->connection->Execute($query,$parameters);

            foreach($result as $value)
            {
                $booking = new Booking();

                $booking->setId($value["id"]);
                $booking->setBookCode($value["bookCode"]);
                $booking->setOwnerCode($value["ownerCode"]);
                $booking->setKeeperCode($value["keeperCode"]);
                $booking->setPetCode($value["petCode"]);
                $booking->setInitDate($value["initDate"]);
                $booking->setEndDate($value["endDate"]);
                $booking->setStatus($value["status"]);
                $booking->setTotalPrice($value["totalPrice"]);

                array_push($bookingArray,$booking);
            }

            return $bookingArray;

        }catch(Exception $ex)
        {
            throw $ex;
        }
    }

    //Es un getAllBookings normal pero tiene un par de JOINS para 
    //poder llevar directamente la info respecto a quien reservó (nameOwner) y que mascota (namePet)
    public function getAllMyBookings($userCode)
    {
        var_dump($userCode);
        try{

            $query = "SELECT b.id,b.bookCode,b.ownerCode,b.keeperCode,b.petCode,b.initDate,b.endDate,b.status,b.totalPrice, o.name as ownerName,p.name as petName,p.pfp
            FROM ".$this->tableName." as b
            JOIN pet as p
            ON  b.petCode = p.petCode ";
            //Hay que chequear con !== false ya que puede devolver 0 y por haberlo encontrado en la pos0 de la cadena
            if(strpos($userCode,"KEP") !== false )
            {
                $query .= " JOIN owner as o
                ON b.ownerCode = o.ownerCode
                JOIN keeper as k
                ON k.keeperCode = :userCode 
                WHERE b.keeperCode = :userCode ";
            }else if(strpos($userCode,"OWN")!== false)
            {
                $query .= " JOIN keeper as k
                ON b.keeperCode = k.keeperCode
                JOIN owner as o
                ON o.ownerCode = :userCode 
                WHERE b.ownerCode = :userCode ";
            }

            $query .= "ORDER BY b.initDate DESC;";
            
            $this->connection = Connection::GetInstance();

            $parameters["userCode"] = $userCode;

            $resultSet = $this->connection->Execute($query,$parameters);

            foreach($resultSet as $bookingInfo)
            {
                $booking = new Booking();

                $booking->setId($bookingInfo["id"]);
                $booking->setBookCode($bookingInfo["bookCode"]);
                $booking->setOwnerCode($bookingInfo["ownerCode"]);
                $booking->setKeeperCode($bookingInfo["keeperCode"]);
                $booking->setPetCode($bookingInfo["petCode"]);
                $booking->setInitDate($bookingInfo["initDate"]);
                $booking->setEndDate($bookingInfo["endDate"]);
                $booking->setStatus($bookingInfo["status"]);
                $booking->setTotalPrice($bookingInfo["totalPrice"]);

                 $arrayTmp = [
                    "booking" => $booking,
                    "ownerName" => $bookingInfo["ownerName"],
                    "petName" => $bookingInfo["petName"],
                    "pfp" => $bookingInfo["pfp"]
                    ]
                ;
                $megArray[$bookingInfo["bookCode"]] = $arrayTmp;
            }

            return $megArray;
        }catch(Exception $ex)
        {
            throw $ex;
        }
    }

    public function getMyBookingsByStatus($userCode, $status)
    {
        try {
            if ($status != "") {
                $query = "SELECT b.id,b.bookCode,b.ownerCode,b.keeperCode,b.petCode,b.initDate,b.endDate,b.status,b.totalPrice,o.name as ownerName,p.name as petName,p.pfp
            FROM " . $this->tableName . " as b
            JOIN pet as p
            ON  b.petCode = p.petCode ";
                
                if (strpos($userCode, "KEP") !== false) {
                    $query .= " JOIN owner as o
                ON b.ownerCode = o.ownerCode
                WHERE b.keeperCode = :userCode AND b.status = :status ";
                } else if (strpos($userCode, "OWN") !== false) {
                    $query .= " JOIN keeper as k
                ON b.keeperCode = k.keeperCode 
                JOIN owner as o 
                ON b.ownerCode = o.ownerCode
                WHERE b.ownerCode = :userCode AND b.status = :status ";
                }

                $query .= "ORDER BY b.initDate DESC;";
                $parameters["status"] = $status;
                $parameters["userCode"] = $userCode;

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query, $parameters);

                $myBookingsArray = array();
                foreach ($resultSet as $bookingInfo) {
                    $booking = new Booking();

                    $booking->setId($bookingInfo["id"]);
                    $booking->setBookCode($bookingInfo["bookCode"]);
                    $booking->setOwnerCode($bookingInfo["ownerCode"]);
                    $booking->setKeeperCode($bookingInfo["keeperCode"]);
                    $booking->setPetCode($bookingInfo["petCode"]);
                    $booking->setInitDate($bookingInfo["initDate"]);
                    $booking->setEndDate($bookingInfo["endDate"]);
                    $booking->setStatus($bookingInfo["status"]);
                    $booking->setTotalPrice($bookingInfo["totalPrice"]);

                    $arrayTmp = [
                        "booking" => $booking,
                        "ownerName" => $bookingInfo["ownerName"],
                        "petName" => $bookingInfo["petName"],
                        "pfp" => $bookingInfo["pfp"]
                    ];

                    $megArray[$bookingInfo["bookCode"]] = $arrayTmp;
                }
            } else {
                $megArray = $this->getAllMyBookings($userCode);
            }



           
        } catch (Exception $ex) {
            throw $ex;
        }
        return $megArray;
    }


    
    public function getMyBookings($initDate,$endDate,$status,$userCode)
    {
        try
        {
            $query = "SELECT b.id,b.bookCode,b.ownerCode,b.keeperCode,b.petCode,b.initDate,b.endDate,b.status,b.totalPrice,o.name as ownerName,p.name as petName,p.pfp
            FROM ".$this->tableName." as b
            JOIN pet as p
            ON  b.petCode = p.petCode ";

            if(strpos($userCode,"KEP") !== false )
            {
                $query .= " JOIN owner as o
                ON b.ownerCode = o.ownerCode
                WHERE b.keeperCode = :userCode AND b.status = :status ";
            }else if(strpos($userCode,"OWN")!== false)
            {
                $query .= " JOIN keeper as k
                ON b.keeperCode = k.keeperCode 
                JOIN owner as o 
                ON b.ownerCode = o.ownerCode
                WHERE b.ownerCode = :userCode AND b.status = :status ";
            }

            if($initDate != null && $endDate != null)
            {
                $query .= "AND b.initDate >= :initDate
                AND b.endDate <= :endDate;";
                $parameters["initDate"] = $initDate;
                $parameters["endDate"] = $endDate;
            }
            


            $parameters["status"] = $status;
            $parameters["userCode"] = $userCode;

            $this->connection = Connection::GetInstance();

            $resultSet = $this->connection->Execute($query,$parameters);

            $myBookingsArray = array();
            $megArray = array();
            foreach($resultSet as $bookingInfo)
            {
                $booking = new Booking();

                $booking->setId($bookingInfo["id"]);
                $booking->setBookCode($bookingInfo["bookCode"]);
                $booking->setOwnerCode($bookingInfo["ownerCode"]);
                $booking->setKeeperCode($bookingInfo["keeperCode"]);
                $booking->setPetCode($bookingInfo["petCode"]);
                $booking->setInitDate($bookingInfo["initDate"]);
                $booking->setEndDate($bookingInfo["endDate"]);
                $booking->setStatus($bookingInfo["status"]);
                $booking->setTotalPrice($bookingInfo["totalPrice"]);

                
                 $arrayTmp = [
                    "booking" => $booking,
                    "ownerName" => $bookingInfo["ownerName"],
                    "petName" => $bookingInfo["petName"],
                    "pfp" => $bookingInfo["pfp"]
                    ]
                ;
                $megArray[$bookingInfo["bookCode"]] = $arrayTmp;
            }
            
            return $megArray;
        }catch(Exception $ex)
        {
            throw $ex;
        }
    }

    public function getBookingByCodeLogged($userCode,$codeBook)
    {
        try{

            $query = "SELECT b.id,b.bookCode,b.ownerCode,b.keeperCode,b.petCode,b.initDate,b.endDate,b.status,b.totalPrice,o.name as ownerName,o.email as oemail,k.name kname,k.email as kemail,p.name as petName,p.pfp,
            p.typePet,p.size,p.breed,p.age
            FROM ".$this->tableName." as b
            JOIN pet as p
            ON  b.petCode = p.petCode
            JOIN keeper as k
            ON k.keeperCode = b.keeperCode ";

           
            if(strpos($userCode,"KEP") !== false )
            {
                $query .= " JOIN owner as o
                ON b.ownerCode = o.ownerCode 
                WHERE b.bookCode = :bookCode;";
            }else if(strpos($userCode,"OWN") !== false)
            {
                $query .= "JOIN owner as o
                ON o.ownerCode = b.ownerCode 
                WHERE b.bookCode = :bookCode;";
            }

            $parameters["bookCode"] = $codeBook;

            $this->connection = Connection::GetInstance();

            $resultSet = $this->connection->Execute($query,$parameters);

            $fullBookInfo = array();
            foreach($resultSet as $fullBook)
            {

                $fullBookInfo["id"] = $fullBook["id"];
                $fullBookInfo["bookCode"] = $fullBook["bookCode"];
                $fullBookInfo["ownerCode"] = $fullBook["ownerCode"];
                $fullBookInfo["keeperCode"] = $fullBook["keeperCode"];
                $fullBookInfo["petCode"] = $fullBook["petCode"];
                $fullBookInfo["initDate"] = $fullBook["initDate"];
                $fullBookInfo["endDate"] = $fullBook["endDate"];
                $fullBookInfo["status"] = $fullBook["status"];
                $fullBookInfo["totalPrice"] = $fullBook["totalPrice"];
                $fullBookInfo["ownerName"] = $fullBook["ownerName"];
                $fullBookInfo["oemail"] = $fullBook["oemail"];
                $fullBookInfo["kname"] = $fullBook["kname"];
                $fullBookInfo["kemail"] = $fullBook["kemail"];
                $fullBookInfo["petName"] = $fullBook["petName"];
                $fullBookInfo["pfp"] = $fullBook["pfp"];
                $fullBookInfo["typePet"] = $fullBook["typePet"];
                $fullBookInfo["size"] = $fullBook["size"];
                $fullBookInfo["breed"] = $fullBook["breed"];
                
            }

            return $fullBookInfo;

        }catch(Exception $ex)
        {
            throw $ex;
        }
    }


    public function modifyBookingStatus($codeBook,$status)
    {
        try{

            $query = "UPDATE ".$this->tableName." 
            SET status = :status
            WHERE bookCode = :codeBook;";

            $parameters["status"] = $status;
            $parameters["codeBook"] = $codeBook;

            $this->connection = Connection::GetInstance();

            $result = $this->connection->ExecuteNonQuery($query,$parameters);

            return $result;

        }catch(Exception $ex)
        {
            echo $ex->getMessage();
        }
    }

    public function checkFirstBreed(Booking $booking)
    {
        try{
            $query = "SELECT checkPetBreedAvailability(:p_initDate,:p_endDate,:p_keeperCode,:p_petCode);";

            $this->connection = Connection::GetInstance();

            $parameters["p_initDate"] = $booking->getInitDate();
            $parameters["p_endDate"] = $booking->getEndDate();
            $parameters["p_keeperCode"] = $booking->getKeeperCode();
            $parameters["p_petCode"] = $booking->getPetCode();

            $result = $this->connection->Execute($query,$parameters);



            return $result[0][0];
        }catch(Exception $ex)
        {
            throw $ex;
        }
    }

    //Check if exists previous bookings between Keeper & Owner involved
    public function checkPrevBook($codeKeeper,$codeOwner)
    {
        try
        {

            $query = "SELECT COUNT(*) AS result
            FROM booking
            WHERE ownerCode = :ownerCode AND keeperCode = :keeperCode and status = 'confirmed'
            LIMIT 1;";

            $this->connection = Connection::GetInstance();

            $parameters["ownerCode"] = $codeOwner;
            $parameters["keeperCode"] = $codeKeeper;

            $result = $this->connection->Execute($query,$parameters);

            return array_shift($result);
            
 
        }catch(Exception $ex)
        {
            throw $ex;
        }
    }

    //Kind of 'cancel cascade' 
    public function cancelBooking($bookCode)
    {
        try{
            $query = "UPDATE ".$this->tableName." as b
            SET b.status = :status 
            WHERE b.bookCode = :bbookCode AND b.initDate < CURDATE();";

            $this->connection = Connection::GetInstance();

            $parameters["bbookCode"] = $bookCode;
            $parameters["status"] = "cancelled";

            $result = $this->connection->ExecuteNonQuery($query,$parameters);

            if($result == 1)
            {
                $queryTwo = "UPDATE coupon
                SET status = :status
                WHERE bookCode = :bbookCode ;";

                $resultTwo = $this->connection->ExecuteNonQuery($queryTwo,$parameters);
            }

            //Which return?
            return $result; 

        }catch(Exception $ex){
            throw $ex;
        }
    }

    public function getDatesByCode($bookCode)
    {
        try{

            $query = "SELECT initDate,endDate FROM ".$this->tableName." 
            WHERE bookCode = :bookCode;";

            $this->connection = Connection::GetInstance();

            $parameter["bookCode"] = $bookCode;

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

        //1 available 0 notavailable
        public function checkOverBooking(Booking $booking)
        {
            try{
                $query = "SELECT checkOverBook(:p_keeperCode,:p_petCode,:p_initDate,:p_endDate);";

                $this->connection = Connection::GetInstance();

                $parameters["p_keeperCode"] = $booking->getKeeperCode();
                $parameters["p_petCode"] = $booking->getPetCode();
                $parameters["p_initDate"] = $booking->getInitDate();
                $parameters["p_endDate"] = $booking->getEndDate();

                $result = $this->connection->Execute($query,$parameters);

                return $result[0][0];
            }catch(Exception $ex)
            {
                throw $ex;
            }
        }


        //Practicamente igual a checkOverBook pero tiene en cuenta que si devuelve 1 es pq la reserva a confirmar es la misma que en pendiente
        //Ya si hay 2 no se puede confirmar (Si quedar en pendiente...)
        public function checkOverBookingConfirm(Booking $booking)
        {
            try{
                $query = "SELECT COUNT(*) FROM ".$this->tableName." AS b
                WHERE (b.keeperCode = :p_keeperCode AND b.petCode = :p_petCode)
                    AND (b.status = 'confirmed' OR b.status = 'pending' OR b.status = 'paidup' OR b.status = 'finished')
                    AND (
                        (:p_initDate BETWEEN b.initDate AND b.endDate)  
                        OR (:p_endDate BETWEEN b.initDate AND b.endDate) 
                        OR (b.initDate BETWEEN :p_initDate AND :p_endDate) 
                        OR (b.endDate BETWEEN :p_initDate AND :p_endDate)
                    );";

                $this->connection = Connection::GetInstance();

                $parameters["p_keeperCode"] = $booking->getKeeperCode();
                $parameters["p_petCode"] = $booking->getPetCode();
                $parameters["p_initDate"] = $booking->getInitDate();
                $parameters["p_endDate"] = $booking->getEndDate();

                $result = $this->connection->Execute($query,$parameters);

                return $result[0][0];
            }catch(Exception $ex)
            {
                throw $ex;
            }
        }
    }



    






?>