<?php


namespace Services;

use \Exception as Exception;
use DAO\MessageDAO as MessageDAO;
use Models\Message as Message;
use DAO\BookingDAO as BookingDAO;
use DAO\conversationDAO as ConversationDAO;

class MessageService{
    
    private $messageDAO;
    private $bookingDAO;
    private $conversationDAO;

    public function __construct() {
        $this->messageDAO = new MessageDAO();
        $this->bookingDAO = new BookingDAO();
        $this->conversationDAO = new ConversationDAO();
    }


    public function srv_UsersAvaiToText($codeUser)
    {
        try
        {
            
            $arrayUsersAvai = $this->messageDAO->getUsersFromBook($codeUser);
            
        }catch(Exception $ex)
        {
            
            echo $ex->getMessage();
        }
        return $arrayUsersAvai;
    }

    public function srv_getInfoConver($chatCode)
    {
        try{
            
            $arrayConver =  $this->conversationDAO->getUsersFromConver($chatCode);

            return $arrayConver;
        }catch(Exception $ex)
        {
            echo $ex->getMessage();
        }
    }

    public function srv_sendMessage($senderCode,$msgText,$chatCode)
    {
        //echo "HOLA SOY CHATCODE".$chatCode;
        //validar:
            //Que existan los codigos // Que ambos usuarios tengan un booking = paidup o Coupon = paidup
        try{
           
            //Si el chat efectivamente posee un id,existe por ende ya contiene ambos usuarios de la conver
            if($chatCode != 0 && $chatCode != "")
            {
                
                $codes = $this->conversationDAO->getUsersFromConver($chatCode);

                    if($senderCode === $codes["ownerCode"])
                    {
                        $receiverCode = $codes["keeperCode"];
                        $senderCode = $codes["ownerCode"];
                    }else if ($senderCode === $codes["keeperCode"]){
                        $receiverCode = $codes["ownerCode"];
                        $senderCode = $codes["keeperCode"];
                    }
                       
                    //Guardo el codigo obtenido directamente de la BD para compararlo con el que llega del controller originado de la sesion
                    // $senderCode = $codes["ownerCode"];
                
            }
            
            //Se llama el dao para la insercion del msje
            $result  = $this->messageDAO->sendMessage($senderCode,$receiverCode,$msgText,$chatCode,0);
            return $result;

        }catch(Exception $ex)
        {
            echo $ex->getMessage();
        }
    }
    public function srv_GetMessages($codeSender,$chatCode)
    {
        try{
            //Habria que validar:
            //Que existan los codigos // Que ambos usuarios tengan un booking = paidup o Coupon = paidup
            $checkCodes = $this->conversationDAO->getUsersFromConver($chatCode);
            
            
            if($checkCodes["keeperCode"] == $codeSender)
            {
                $receiverCode = $checkCodes["ownerCode"];
            }else if($checkCodes["ownerCode"] == $codeSender)
            {
                $receiverCode = $checkCodes["keeperCode"];
            }
            $arrayMsges = $this->messageDAO->receiveMessage($codeSender,$receiverCode,$chatCode);
            
            return $arrayMsges;
        }catch(Exception $ex)
        {
            echo $ex->getMessage();
        }
        return $arrayMsges;
    }

    public function srv_getAvailTalk($codeLogged)
    {
        try{
            //$availTalk = $this->messageDAO->getBothBookingsUsers($codeLogged);
            $availTalk = $this->conversationDAO->getConverByUserCode($codeLogged);
            return $availTalk;
        }catch(Exception $ex)
        {
            echo $ex->getMessage();
        }
    }

    public function srv_checkPrevConv($receiverCode,$senderCode){
        try{

            //Si devuelve 0 es que no hay conversacion entre esos 2
            $result = $this->messageDAO->getChatCode($senderCode,$receiverCode);

            if($result == 0)
            {
                $numbs = range(100000, 999999);
                shuffle($numbs);
                $uniqNum = implode('', array_slice($numbs, 0, 6));
                //Buscamos codigo 
                $checkConv = $this->messageDAO->getUsersFromChat($uniqNum);
                echo "CHECKCONV :";
                var_dump($checkConv);

                if($checkConv == null)
                {
                    $result = $uniqNum;
                }
            }
            
            //Ya sea un codigo nuevo o una conversacion existente devuelve el codigo de la misma
            return $result;
        }catch(Exception $ex)
        {
            echo $ex->getMessage();
        }
    }

    public function srv_getUsersConver($chatCode)
    {
        try{
            $codes = $this->conversationDAO->getUsersFromConver($chatCode);
            if($codes["receiverCode"] == null && $codes["senderCode"] == null)
            {
                return null;
            }else{
                return $codes;
            }
        }catch(Exception $ex)
        {
            $ex->getMessage();
        }
    }
}

?>