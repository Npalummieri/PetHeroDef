<?php

namespace DAO;

use \PDO as PDO;
use \Exception as Exception;
use DAO\QueryType as QueryType;

class Connection
{
    private $pdo = null;
    private $pdoStatement = null;
    private static $instance = null;

    private function __construct()
    {
        try {
            $this->pdo = new PDO("mysql:host=" . DB_HOST . "; dbname=" . DB_NAME, DB_USER, DB_PASS);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public static function GetInstance()
    {
        if (self::$instance == null)
            self::$instance = new Connection();

        return self::$instance;
    }

    public function Execute($query, $parameters = array(), $queryType = QueryType::Query)
    {
        try {
            $this->Prepare($query);

            $this->BindParameters($parameters, $queryType);

            $this->pdoStatement->execute();

            return $this->pdoStatement->fetchAll(); //Con PDO::FETCH_OBJ evitas la duplacion de valores,creo que viene en PDO::FETCH_BOTH como default...
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function ExecuteNonQuery($query, $parameters = array(), $queryType = QueryType::Query)
    {
        try {
            $this->Prepare($query);

            $this->BindParameters($parameters, $queryType);

            $this->pdoStatement->execute();

            return $this->pdoStatement->rowCount();
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    private function Prepare($query)
    {
        try {
            $this->pdoStatement = $this->pdo->prepare($query);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    private function BindParameters($parameters = array(), $queryType = QueryType::Query)
    {
        $i = 0;

        foreach ($parameters as $parameterName => $value) {
            $i++;

            if ($queryType == QueryType::Query)
                $this->pdoStatement->bindParam(":" . $parameterName, $parameters[$parameterName]);
            else
                $this->pdoStatement->bindParam($i, $parameters[$parameterName]);
        }
    }


    ///Leve modificacion del archivo dado en el proyecto 

    public function LastInsertId($tableName)
    {

        try {

            $this->pdo->beginTransaction(); //Inicio de transaccion
            $statemnt = $this->pdo->prepare("SELECT AUTO_INCREMENT FROM information_schema.TABLES 
                WHERE TABLE_SCHEMA = :db_name AND TABLE_NAME = :table_name;");

            $db_name = DB_NAME;
            $statemnt->bindParam(":db_name", $db_name);
            $statemnt->bindParam(":table_name", $tableName);

            $statemnt->execute();
            $nextId = $statemnt->fetchColumn(); //Al levantar una unica columna (AUTO_INCREMENT) devuelve el dato almacenado en ella
            //echo "NEXTID EN LASTINSERT ID  :".$nextId;
            $this->pdo->commit(); //Confirmas lo realizado

            return $nextId;
        } catch (Exception $ex) {
            $this->pdo->rollBack(); //Deshaces la transaccion
            //Mejor seria arrojar la except
            echo $ex->getMessage();
        }
    }



    public function BeginTransaction()
    {
        return $this->pdo->beginTransaction();
    }

    public function Commit()
    {
        return $this->pdo->commit();
    }

    public function RollBack()
    {
        return $this->pdo->rollBack();
    }
}
