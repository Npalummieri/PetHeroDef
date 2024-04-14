<?php

namespace DAO;

use \Exception as Exception;
use Models\Review as Review;
use DAO\Connection as Connection;

class ReviewDAO
{

    private $tableName = "review";
    private $connection = null;

    public function Add(Review $review)
    {
        try {


            $query = "INSERT INTO " . $this->tableName . " (keeperCode,ownerCode,comment,score,reviewCode) VALUES
            (:codeKeeper,:codeOwner,:comment,:score,:reviewCode);";

            $this->connection = Connection::GetInstance();

            $parameters["codeKeeper"] = $review->getCodeKeeper();
            $parameters["codeOwner"] = $review->getCodeOwner();
            $parameters["comment"] = $review->getComment();
            $parameters["score"] = $review->getScore();
            $parameters["reviewCode"] = $review->getCodeReview();

            $resultQ = $this->connection->ExecuteNonQuery($query, $parameters);

            if ($resultQ == 1) {
                $this->connection = Connection::GetInstance();
                $queryTwo = "SELECT updateKeepScoreFunc(:p_keeperCode);";


                $parametersTwo["p_keeperCode"] = $review->getCodeKeeper();

                $resultCall = $this->connection->ExecuteNonQuery($queryTwo, $parametersTwo);
            }

            return $resultQ;
        } catch (Exception $ex) {
            throw $ex;
        }
    }




    //Review + info
    public function getAllByKeeperCode($keeperCode)
    {
        try {

            $query = "SELECT r.keeperCode,r.ownerCode,r.comment,r.score,r.timeStamp,r.reviewCode,o.email,o.name,o.lastname,o.pfp FROM " . $this->tableName . " as r 
            JOIN owner as o
            ON o.ownerCode = r.ownerCode
            WHERE r.keeperCode = :codeKeeper
            ORDER BY r.timestamp DESC;";

            $this->connection = Connection::GetInstance();

            $parameters["codeKeeper"] = $keeperCode;

            $resultSet = $this->connection->Execute($query, $parameters);

            $arrayReviews = array();

            foreach ($resultSet as $row) {
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


                array_push($arrayReviews, $review);
            }

            return $arrayReviews;
        } catch (Exception $ex) {
            throw $ex;
        }
    }


    public function getAllByOwnerCode($ownerCode)
    {
        try {

            $query = "SELECT * FROM " . $this->tableName . "
            WHERE codeOwner = :codeOwner;";

            $this->connection = Connection::GetInstance();

            $parameters["codeOwner"] = $ownerCode;

            $resultSet = $this->connection->Execute($query, $parameters);

            $arrayReviews = array();

            foreach ($resultSet as $row) {
                $review = new Review();

                $review->setCodeKeeper($row["keeperCode"]);
                $review->setCodeOwner($row["ownerCode"]);
                $review->setComment($row["comment"]);
                $review->setScore($row["score"]);
                $review->setTimeStamp($row["timeStamp"]);
                $review->setCodeReview($row["reviewCode"]);

                array_push($arrayReviews, $review);
            }

            return $arrayReviews;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    //checkPrevBooks on status finished & no more than 3 reviews
    public function canReview($ownerCode, $keeperCode)
    {
        try {

            $query = "SELECT COUNT(*) FROM booking
            WHERE ownerCode = :ownerCode AND keeperCode = :keeperCode AND status = :status;";


            $queryTwo = "SELECT COUNT(*) FROM " . $this->tableName . " 
            WHERE ownerCode = :ownerCode AND keeperCode = :keeperCode;";

            $this->connection = Connection::GetInstance();

            $parameters["ownerCode"] = $ownerCode;
            $parameters["keeperCode"] = $keeperCode;
            $parameters["status"] = "finished";

            //if 1 or +1 could review
            $result = $this->connection->Execute($query, $parameters);

            $parametersTwo["ownerCode"] = $ownerCode;
            $parametersTwo["keeperCode"] = $keeperCode;

            $resultCountReview = $this->connection->Execute($queryTwo, $parametersTwo);

            $results["result"] = $result[0][0];
            $results["resultCountReview"] = $resultCountReview[0][0];
            return $results;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function delete($reviewCode, $ownerCode)
    {
        try {

            $query = "SELECT * FROM " . $this->tableName . " 
            WHERE reviewCode = :reviewCode AND ownerCode = :ownerCode;";

            $this->connection = Connection::GetInstance();

            $parameters["reviewCode"] = $reviewCode;
            $parameters["ownerCode"] = $ownerCode;

            $resultSet = $this->connection->Execute($query, $parameters);

            $review = new Review();
            foreach ($resultSet as $row) {
                $review->setCodeKeeper($row["keeperCode"]);
                $review->setCodeOwner($row["ownerCode"]);
                $review->setComment($row["comment"]);
                $review->setScore($row["score"]);
                $review->setTimeStamp($row["timeStamp"]);
                $review->setCodeReview($row["reviewCode"]);
            }
            //Using ownerCode make it safer
            $queryDelete = "DELETE FROM " . $this->tableName . " 
            WHERE reviewCode = :reviewCode AND ownerCode = :ownerCode ;";



            $result = $this->connection->ExecuteNonQuery($queryDelete, $parameters);

            if ($result == 1) {
                $queryTwo = "SELECT updateKeepScoreFunc(:p_keeperCode);";
                $this->connection = Connection::GetInstance();
                $paramTwo["p_keeperCode"] = $review->getCodeKeeper();
                $this->connection->ExecuteNonQuery($queryTwo, $paramTwo);
            }

            $arrayResult["review"] = $review;
            $arrayResult["deleted"] = $result;

            return $arrayResult;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
