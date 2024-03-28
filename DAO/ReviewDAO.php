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

            echo "RESULTQ";
            var_dump($resultQ);
            if($resultQ == 1)
            {   
                $this->connection = Connection::GetInstance();
                $queryTwo = "SELECT updateKeepScoreFunc(:p_keeperCode);";


                $parametersTwo["p_keeperCode"] = $review->getCodeKeeper();

                $resultCall = $this->connection->ExecuteNonQuery($queryTwo,$parametersTwo);
            }
            echo "RESULT CALL";
            var_dump($resultCall);
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
            WHERE r.keeperCode = :codeKeeper
            ORDER BY r.timestamp DESC;";

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

    public function canReview($ownerCode,$keeperCode)
    {
        try{

            $query = "SELECT COUNT(*) FROM booking
            WHERE ownerCode = :ownerCode AND keeperCode = :keeperCode AND status = :status;";


            $queryTwo = "SELECT COUNT(*) FROM ".$this->tableName." 
            WHERE ownerCode = :ownerCode AND keeperCode = :keeperCode;";
            $this->connection = Connection::GetInstance();

            $parameters["ownerCode"] = $ownerCode;
            $parameters["keeperCode"] = $keeperCode;
            $parameters["status"] = "finished";

            $result = $this->connection->Execute($query,$parameters);

            $parametersTwo["ownerCode"] = $ownerCode;
            $parametersTwo["keeperCode"] = $keeperCode;

            $resultCountReview = $this->connection->Execute($queryTwo,$parametersTwo);

            if($result[0][0] >= 1 && $resultCountReview <= 2)
            {
                $result = 1;
            }
            return $result;
        }catch(Exception $ex)
        {
            throw $ex;
        }
    }

    public function checkLimitReviews($ownerCode,$keeperCode)
    {
        try{

        }catch(Exception $ex)
        {
            throw $ex;
        }
    }

    public function delete($reviewCode,$ownerCode)
    {
        try{

            $query = "SELECT * FROM ".$this->tableName." 
            WHERE reviewCode = :reviewCode AND ownerCode = :ownerCode;";

            $this->connection = Connection::GetInstance();

            $parameters["reviewCode"] = $reviewCode;
            $parameters["ownerCode"] =$ownerCode;

            $resultSet = $this->connection->Execute($query,$parameters);

            $review = new Review();
            foreach($resultSet as $row)
            {
                $review->setCodeKeeper($row["keeperCode"]);
                $review->setCodeOwner($row["ownerCode"]);
                $review->setComment($row["comment"]);
                $review->setScore($row["score"]);
                $review->setTimeStamp($row["timeStamp"]);
                $review->setCodeReview($row["reviewCode"]);
            }
            //Parece innecesario el ownerCode pero es una revalidacion que el que borra es el propio user
            $queryDelete = "DELETE FROM ".$this->tableName." 
            WHERE reviewCode = :reviewCode AND ownerCode = :ownerCode ;";

            $result = $this->connection->ExecuteNonQuery($queryDelete,$parameters);

            $arrayResult["review"] = $review;
            $arrayResult["deleted"] = $result;
            return $arrayResult;

            
        }catch(Exception $ex)
        {
            throw $ex;
        }
    }

    



}

?>