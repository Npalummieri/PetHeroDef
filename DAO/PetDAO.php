<?php 

namespace DAO;

use \Exception as Exception;
use DAO\Connection as Connection;
use Models\Status as Status;

use Models\Pet as Pet;


class PetDAO {

    private $tableName = "pet";
    private $connection;

    public function Add(Pet $pet)
    {
        try
        {
         
            //var_dump($pet);
            $query = "INSERT INTO ".$this->tableName." (petCode,name,pfp,ownerCode,size,breed,vaccPlan,video,typePet,age)
            VALUES (:petCode,:name,:pfp,:ownerCode,:size,:breed,:vaccPlan,:video,:typePet,:age);";
            
            $this->connection = Connection::GetInstance();            
            $parameters["petCode"] = $pet->getPetCode();
            $parameters["name"] = $pet->getName();
            $parameters["pfp"] = $pet->getPfp();
            $parameters["ownerCode"] = $pet->getOwnerCode();
            $parameters["size"] = $pet->getSize();
            $parameters["breed"] = $pet->getBreed();
            $parameters["vaccPlan"] = $pet->getVaccPlan();
            $parameters["video"] = $pet->getVideo();
            $parameters["typePet"] = $pet->getTypePet(); 
            $parameters["age"] = $pet->getAge(); 
            
            //var_dump($parameters);
            $this->connection = Connection::GetInstance();

            
            $result = $this->connection->ExecuteNonQuery($query,$parameters);
            if($result == 1)
            {
                $petCode = $pet->getPetCode();
            }else{
                $petCode = null;
            }

            return $petCode;
        }catch(Exception $ex)
        {
            throw $ex;
        }
    }

    public function getAllByOwner($ownerCode)
    {
        try{

            $query = "SELECT * FROM ".$this->tableName." 
            WHERE ownerCode = :ownerCode;";

            $parameters["ownerCode"] = $ownerCode;

            $this->connection = Connection::GetInstance();

            $resultSet = $this->connection->Execute($query,$parameters);



            $petArray = array();
            foreach($resultSet as $petArr)
            {
                $pet = new Pet();

                $pet->setId($petArr["id"]);
                $pet->setPetCode($petArr["petCode"]);
                $pet->setName($petArr["name"]);
                $pet->setPfp($petArr["pfp"]);
                $pet->setOwnerCode($petArr["ownerCode"]);
                $pet->setSize($petArr["size"]);
                $pet->setBreed($petArr["breed"]);
                $pet->setVaccPlan($petArr["vaccPlan"]);
                $pet->setVideo($petArr["video"]);
                $pet->setTypePet($petArr["typePet"]);
                $pet->setAge($petArr["age"]);

                array_push($petArray,$pet);
            }

            return $petArray;
        }catch(Exception $ex)
        {
            throw $ex;
        }
    }

    public function getAllByTypeAndOwner($ownerCode,$typePet)
    {
        try{

            $query = "SELECT * FROM ".$this->tableName." 
            WHERE ownerCode = :ownerCode AND typePet = :typePet ";

            $parameters["ownerCode"] = $ownerCode;
            $parameters["typePet"] = $typePet;
            $this->connection = Connection::GetInstance();

            $resultSet = $this->connection->Execute($query,$parameters);

            //var_dump($resultSet);

            $petArray = array();
            foreach($resultSet as $petArr)
            {
                $pet = new Pet();

                $pet->setId($petArr["id"]);
                $pet->setPetCode($petArr["petCode"]);
                $pet->setName($petArr["name"]);
                $pet->setPfp($petArr["pfp"]);
                $pet->setOwnerCode($petArr["ownerCode"]);
                $pet->setSize($petArr["size"]);
                $pet->setBreed($petArr["breed"]);
                $pet->setVaccPlan($petArr["vaccPlan"]);
                $pet->setVideo($petArr["video"]);
                $pet->setTypePet($petArr["typePet"]);
                $pet->setAge($petArr["age"]);

                array_push($petArray,$pet);
            }

            return $petArray;
        }catch(Exception $ex)
        {
            throw $ex;
        }
    }


    public function getPetsFilteredOwner($ownerCode,$typePet,$typeSize)
    {
        try{
           
            $query = "SELECT * FROM ".$this->tableName." 
            WHERE ownerCode = :ownerCode AND typePet = :typePet AND size = :size; ";

            $parameters["ownerCode"] = $ownerCode;
            $parameters["typePet"] = $typePet;
            $parameters["size"] = $typeSize;
            $this->connection = Connection::GetInstance();

            $resultSet = $this->connection->Execute($query,$parameters);

            //var_dump($resultSet);

            $petArray = array();
            foreach($resultSet as $petArr)
            {
                $pet = new Pet();

                $pet->setId($petArr["id"]);
                $pet->setPetCode($petArr["petCode"]);
                $pet->setName($petArr["name"]);
                $pet->setPfp($petArr["pfp"]);
                $pet->setOwnerCode($petArr["ownerCode"]);
                $pet->setSize($petArr["size"]);
                $pet->setBreed($petArr["breed"]);
                $pet->setVaccPlan($petArr["vaccPlan"]);
                $pet->setVideo($petArr["video"]);
                $pet->setTypePet($petArr["typePet"]);
                $pet->setAge($petArr["age"]);

                array_push($petArray,$pet);
            }

            return $petArray;
        }catch(Exception $ex)
        {
            throw $ex;
        }
    }

    public function updateVideo($petCode,$video)
    {
        try{
            $query = "UPDATE pet SET video = :video WHERE petCode = :petCode ;";

            $this->connection = Connection::GetInstance();

            $parameters["petCode"] = $petCode;
            $parameters["video"] = $video;

            $result = $this->connection->ExecuteNonQuery($query,$parameters);

            return $result;
        }catch(Exception $ex)
        {
            throw $ex;
            
        }
    }


    public function updateVacc($petCode,$vacc)
    {
        try{

            $query = "UPDATE pet SET vaccPlan = :vaccPlan WHERE petCode = :petCode ;";

            $this->connection = Connection::GetInstance();

            $parameters["petCode"] = $petCode;
            $parameters["vaccPlan"] = $vacc;

            $result = $this->connection->ExecuteNonQuery($query,$parameters);

            return $result;
        }catch(Exception $ex)
        {
            throw $ex;
            
        }
    }

    public function updatePfp($petCode,$pfp)
    {
        try{
            $query = "UPDATE ".$this->tableName." 
            SET pfp = :pfp 
            WHERE petCode = :petCode ;";

            $this->connection = Connection::GetInstance();

            $parameters["petCode"] = $petCode;
            $parameters["pfp"] = $pfp;

            $result = $this->connection->ExecuteNonQuery($query,$parameters);

            return $result;
        }catch(Exception $ex)
        {
            throw $ex;
            
        }
    }

    public function updateAge($petCode,$age)
    {
        try{
            $query = "UPDATE pet SET age = :age WHERE petCode = :petCode ;";

            $this->connection = Connection::GetInstance();

            $parameters["petCode"] = $petCode;
            $parameters["age"] = $age;

            return $this->connection->ExecuteNonQuery($query,$parameters);
        }catch(Exception $ex)
        {
            throw $ex;
            
        }
    }

    public function updateSize($petCode,$size)
    {
        try{
            $query = "UPDATE pet SET size = :size WHERE petCode = :petCode ;";

            $this->connection = Connection::GetInstance();

            $parameters["petCode"] = $petCode;
            $parameters["size"] = $size;

            return $this->connection->ExecuteNonQuery($query,$parameters);
        }catch(Exception $ex)
        {
            throw $ex;
            
        }
    }


    public function checkOwnerByPet($petCode,$ownerCode)
    {
        try{
            $query = "SELECT COUNT(*) FROM ".$this->tableName."
            WHERE petCode = :petCode AND ownerCode = :ownerCode;";

            $this->connection = Connection::GetInstance();

            $parameters["petCode"] = $petCode;
            $parameters["ownerCode"] = $ownerCode;

            $result = $this->connection->Execute($query,$parameters);

            return $result[0][0];
        }catch(Exception $ex){
            throw $ex;
        }
    }

    public function getPet($petCode)
    {
        try{
            $query = "SELECT * FROM ".$this->tableName." 
            WHERE petCode = :petCode ;";

            $this->connection = Connection::GetInstance();

            $parameters["petCode"] = $petCode;

            $resultSet = $this->connection->Execute($query,$parameters);

            $pet = new Pet();
            foreach($resultSet as $row)
            {
                $pet->setId($row["id"]);
                $pet->setpetCode($row["petCode"]);
                $pet->setName($row["name"]);
                $pet->setPfp($row["pfp"]);
                $pet->setOwnerCode($row["ownerCode"]);
                $pet->setSize($row["size"]);
                $pet->setBreed($row["breed"]);
                $pet->setVaccPlan($row["vaccPlan"]);
                $pet->setVideo($row["video"]);
                $pet->setTypePet($row["typePet"]);
                $pet->setAge($row["age"]);
            }

            return $pet;
        }catch(Exception $ex){
            throw $ex;
        }
    }

    public function checkPetBookings($petCode)
    {
        try{
            $query = "SELECT COUNT(*) FROM booking 
            WHERE petCode = :petCode AND (status = :pen OR status = :conf OR status = :paid );";

            $this->connection = Connection::GetInstance();

            $parameters["petCode"] = $petCode;
            $parameters["pen"] = Status::PENDING; 
            $parameters["conf"] = Status::CONFIRMED; 
            $parameters["paid"] = Status::PAIDUP; 

            $result = $this->connection->Execute($query,$parameters);

            return $result[0][0];
            }catch(Exception $ex)
        {
            throw $ex;
        }
    }
    public function deletePet($petCode)
    {
        try{

            $query = "DELETE FROM ".$this->tableName." 
            WHERE petCode = :petCode ;";

            $this->connection = Connection::GetInstance();

            $parameter["petCode"] = $petCode;

            return $this->connection->ExecuteNonQuery($query,$parameter);
        }catch(Exception $ex)
        {
            throw $ex;
        }
    }

    public function getAllPfps()
    {
        try{
            $query = "SELECT pfp FROM ".$this->tableName;

            $this->connection = Connection::GetInstance();

            $resultSet = $this->connection->Execute($query);

            $arrImages = array();
            for($i = 0; $i < count($resultSet);$i++)
            {
                array_push($arrImages,$resultSet[$i]["pfp"]);
            }
           //var_dump($arrImages);
        }catch(Exception $ex)
        {
            throw $ex;
        }
        return $arrImages;
    }

}

?>