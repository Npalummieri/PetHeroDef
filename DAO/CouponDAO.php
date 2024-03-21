<?php

namespace DAO;

use Models\Coupon as Coupon;
use Exception;

class CouponDAO{

    private $tableName = "coupon";
    private $connection;

    public function Add(Coupon $coupon)
    {
        try{

            $query = "INSERT INTO ".$this->tableName."(couponCode,bookCode,price,status)
            VALUES (:couponCode,:bookCode,:price,:status);";

            $this->connection = Connection::GetInstance();

            $parameters["couponCode"] = $coupon->getCouponCode();
            $parameters["bookCode"] = $coupon->getBookCode();
            $parameters["price"] = $coupon->getPrice();
            $parameters["status"] = "pending";

           $result = $this->connection->ExecuteNonQuery($query,$parameters);

           if($result == 1)
           {
                $coupCode = $coupon->getCouponCode();
           }else{
                $coupCode = null;
           }

           return $coupCode;

        }catch(Exception $ex)
        {
            throw $ex;
        }
    }

    public function getCouponByCode($couponCode)
    {
        try{

            $query = "SELECT * FROM ".$this->tableName."
            WHERE couponCode = :couponCode;";

            $parameters["couponCode"] = $couponCode;

            $this->connection = Connection::GetInstance();

            $resultSet = $this->connection->Execute($query,$parameters);

            $coupon = new Coupon();

            foreach($resultSet as $value)
            {
                $coupon->setId($value["id"]);
                $coupon->setCouponCode($value["couponCode"]);
                $coupon->setBookCode($value["bookCode"]);
                $coupon->setPrice($value["price"]); 
                $coupon->setstatus($value["status"]); 
            }

            return $coupon;

        }catch(Exception $ex)
        {
            throw $ex;
        }
    }

    //Retorna 1 array con la info del cupon especificado por couponCode
    public function getFullInfoCoupon($couponCode)
    {
        try{
            //A partir del couponCode obtengo el bookCode entonces a partir del bookCode obtengo los otros 3 code para el where
            //En teoria con todo esto puedo obtener un resultSet que tenga toda la información particular de una reserva
            //La cuestion es si hago todo trabajando con DAOS + queries directamente o lo laburo en service trayendo esa info de a uno
            $query = "SELECT c.couponCode,c.bookCode,b.initDate,b.endDate,b.totalPrice,p.name as namePet,p.typePet,p.breed,p.size,o.email as emailOwner,o.name as ownerName,o.lastname as olastname,k.name as kname,k.lastname as klastname,k.email as emailKeeper,k.pfp as pfpk,c.status as statusCoup
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
            //Consultar que diferencia habria entre c.CouponCode y b.CouponCode en la clausula where,ya que en si arroja el mismo resultado
            
            $parameters["couponCode"] = $couponCode;
            //$parameters["bookCode"] = $bookCode;

            $this->connection = Connection::GetInstance();

            $resultSet = $this->connection->Execute($query,$parameters);

            $couponArrayInfo = array();

            foreach($resultSet as $value)
            {
                $couponArrayInfo["couponCode"] = $value["couponCode"];
                $couponArrayInfo["bookCode"] = $value["bookCode"];
                $couponArrayInfo["initDate"] = $value["initDate"];
                $couponArrayInfo["endDate"] = $value["endDate"];
                $couponArrayInfo["totalPrice"] = $value["totalPrice"];
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

            return $couponArrayInfo;
        }catch(Exception $ex)
        {
            throw $ex;
        }
    }

    public function getAllCouponsByOwner($ownerCode)
    {
        try{

            $query = "SELECT c.couponCode,c.bookCode
            FROM ".$this->tableName." as c
            JOIN booking as b
            ON c.bookCode = b.bookCode
            WHERE b.ownerCode = :ownerCode;";

            $this->connection = Connection::GetInstance();

            $parameters["ownerCode"] = $ownerCode;

            $resultSet = $this->connection->Execute($query,$parameters);

            $couponsByOwnerCodeArr = array();
            foreach($resultSet as $value)
            {
                $coupInfo["couponCode"] = $value["couponCode"];
                $coupInfo["bookCode"] = $value["bookCode"];
                

                $couponFullInfo = $this->getFullInfoCoupon($coupInfo["couponCode"],$coupInfo["bookCode"]);

                array_push($couponsByOwnerCodeArr,$couponFullInfo);
                
            }

            return $couponsByOwnerCodeArr;
        }catch(Exception $ex)
        {
            throw $ex;
        }
    }

    //Simulamos que el pago se hizo con exito por lo que el status de coupon y booking se ponen como paidup
    //Habria que ver si el booking tambien ponerlo como paidup u otro adjetivo que no sea confirmed
    public function paidUpCoupon($couponCode)
    {
        try{
            
            $query = "UPDATE ".$this->tableName." as c
            SET c.status = :statusCoup
            WHERE c.couponCode = :couponCode;"; /*JOIN booking as b
            ON b.bookCode = c.bookCode AND c.bookCode = :bookCode ;*/

            $this->connection = Connection::GetInstance();

            
            $parameters["statusCoup"] = "paidup";
            $parameters["couponCode"] = $couponCode;
            //$parameters["bookCode"] = $bookCode; O sea esta bien asegurarse pero de ultima el bookCode lo obtenes del mismo Coupon

            //Ver si devuelve 1 directamente o es un FETCH::ASSOC
             $res = $this->connection->ExecuteNonQuery($query,$parameters);

             
             //Si devuelve 1 es que se paidupeo
             return $res;
        }catch(Exception $ex)
        {
            throw $ex;
            echo $ex->getMessage();
        }
    }

    public function declineCoupon($couponCode)
    {
        try{
            $query = "UPDATE coupon 
            SET status = :status 
            WHERE couponCode = :couponCode;";

            $this->connection = Connection::GetInstance();

            $parameters["couponCode"] = $couponCode;

            $result = $this->connection->ExecuteNonQuery($query,$parameters);

            echo "Result query updatecoupon :";
            var_dump($result);
            if($result == 1)
            {
                $queryForJoin = "SELECT bookCode FROM ".$this->tableName." 
                WHERE couponCode = :couponCode;";

                
                $resultqJoin = $this->connection->Execute($queryForJoin,$parameters);

                echo "resultQjoin : ";
                var_dump($resultqJoin);
                $parameter["bookCode"] = $resultqJoin[0]["bookCode"];

                $queryBooking = "UPDATE booking AS b
                JOIN coupon AS c 
                ON b.bookCode = c.bookCode
                SET b.status = :status;
                WHERE b.bookCode = :bookCode ;";

                echo "resultUpdate de la query update del booking ";
                $resultUpdate = $this->connection->ExecuteNonQuery($queryBooking,$parameter);
                var_dump($resultUpdate);
            }

            return $resultUpdate;
        }catch(Exception $ex)
        {
            throw $ex;
        }
    }

    public function getCoupCodeByBook($bookCode)
    {
        try{

            $query = "SELECT couponCode FROM ".$this->tableName." 
            WHERE bookCode = :bookCode;";

            $this->connection = Connection::GetInstance();

            $parameter["bookCode"] = $bookCode;

            $resultSet = $this->connection->Execute($query,$parameter);

            if(empty($resultSet))
            {
                $resp = null;
            }else{
                $resp = $resultSet[0][0];
            }

            return $resp;

        }catch(Exception $ex)
        {
            throw $ex;
        }
    }

} 


?>