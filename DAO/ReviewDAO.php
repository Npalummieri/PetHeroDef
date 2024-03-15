<?php

namespace DAO;

use \Exception as Exception;
use Models\Review as Review;
use DAO\QueryType as QueryType;
use DAO\Connection as Connection;

class ReviewDAO{

    private $tableName = "review";
    private $connection = null;

    public function Add(Review $review)
    {
        try{
            echo "HOLA TOY EN DAO ?";
            //Ver como manejo el codeReview si con id_autoincrement o con un codigo como el resto de objs
            $query = "INSERT INTO ".$this->tableName." (keeperCode,ownerCode,comment,score,reviewCode) VALUES
            (:codeKeeper,:codeOwner,:comment,:score,:reviewCode);";

            $this->connection = Connection::GetInstance();

            $parameters["codeKeeper"] = $review->getCodeKeeper();
            $parameters["codeOwner"] = $review->getCodeOwner();
            $parameters["comment"] = $review->getComment();
            $parameters["score"] = $review->getScore();
            $parameters["reviewCode"] = $review->getCodeReview();
            
            $resultQ = $this->connection->ExecuteNonQuery($query,$parameters);

            $query2 = "CALL updateKeepScoreSP(?)";

            echo "RESULTQ";
            var_dump($resultQ);
            if($resultQ == 1)
            {   
                $parameters2["p_keeperCode"] = $review->getCodeKeeper();

                $this->connection->ExecuteNonQuery($query2,$parameters2,QueryType::StoredProccedure);
            }

            return $resultQ;
            
        }catch(Exception $ex)
        {
            throw $ex;
        }
    }

    //public function canReview()


    //Review + info de quien la escribe
    public function getAllByKeeperCode($keeperCode)
    {
        try{

            //Ver si necesito order by
            $query = "SELECT r.keeperCode,r.ownerCode,r.comment,r.score,r.timeStamp,r.reviewCode,o.email,o.name,o.lastname,o.pfp FROM ".$this->tableName." as r 
            JOIN owner as o
            ON o.ownerCode = r.ownerCode
            WHERE r.keeperCode = :codeKeeper;";

            $this->connection = Connection::GetInstance();

            $parameters["codeKeeper"] = $keeperCode;

            $resultSet = $this->connection->Execute($query,$parameters);

            $arrayReviews = array();

            foreach($resultSet as $row)
            {
                $review["keeperCode"] = $row["keeperCode"];
                $review["ownerCode"] = $row["ownerCode"];
                $review["comment"] = $row["comment"];
                $review["score"] = $row["score"];
                $review["timeStamp"] = $row["timeStamp"];
                $review["reviewCode"] = $row["reviewCode"];
                $review["email"] = $row["email"];
                $review["name"] = $row["name"];
                $review["lastname"] = $row["lastname"];
                $review["pfp"] = $row["pfp"];


                array_push($arrayReviews,$review);
            }

            return $arrayReviews;
        }catch(Exception $ex)
        {
            throw $ex;
        }
    }

    //Podria hacer todo en una funcion que verifique el codigo recibido y en base a eso busque todas las review de un usuario
    //GetAllByCode con un WHERE si codeOwner = :code OR codeKeeper = :code,total es unico...
    public function getAllByOwnerCode($ownerCode)
    {
        try{

            //Ver si necesito order by
            $query = "SELECT * FROM ".$this->tableName."
            WHERE codeOwner = :codeOwner;";

            $this->connection = Connection::GetInstance();

            $parameters["codeOwner"] = $ownerCode;

            $resultSet = $this->connection->Execute($query,$parameters);

            $arrayReviews = array();

            foreach($resultSet as $row)
            {
                $review = new Review();

                $review->setCodeKeeper($row["keeperCode"]);
                $review->setCodeOwner($row["ownerCode"]);
                $review->setComment($row["comment"]);
                $review->setScore($row["score"]);
                $review->setTimeStamp($row["timeStamp"]);
                $review->setCodeReview($row["reviewCode"]);

                array_push($arrayReviews,$review);
            }

            return $arrayReviews;
        }catch(Exception $ex)
        {
            throw $ex;
        }
    }


    private function delete($codeReview)
    {
        try
        {
            $query = "DELETE FROM ".$this->tableName."
            WHERE codeReview = :codeReview;";
        }catch(Exception $ex){
            throw $ex;
        }
    }

    public function canReview($ownerCode,$keeperCode)
    {
        try{

            $query = "SELECT COUNT(*) FROM booking
            WHERE ownerCode = :ownerCode AND keeperCode = :keeperCode AND status = :status;";

            $this->connection = Connection::GetInstance();

            $parameters["ownerCode"] = $ownerCode;
            $parameters["keeperCode"] = $keeperCode;
            $parameters["status"] = "finished";

            $result = $this->connection->Execute($query,$parameters);
            echo "CAN REVIEW RESULT DAO :";
            var_dump($result[0][0]);
            return $result[0][0];
        }catch(Exception $ex)
        {
            throw $ex;
        }
    }

    



}

?>