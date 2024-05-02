<?php


namespace Services;

use \Exception as Exception;
use DAO\MessageDAO as MessageDAO;
use DAO\conversationDAO as ConversationDAO;
use Models\Keeper;

class MessageService
{

    private $messageDAO;
    private $conversationDAO;

    public function __construct()
    {
        $this->messageDAO = new MessageDAO();
        $this->conversationDAO = new ConversationDAO();
    }


    public function srv_UsersAvaiToText($codeUser)
    {
        try {

            $arrayUsersAvai = $this->messageDAO->getUsersFromBook($codeUser);
        } catch (Exception $ex) {

            $arrayUsersAvai = $ex->getMessage();
        }
        return $arrayUsersAvai;
    }

    public function srv_getInfoConver($chatCode)
    {
        try {

            $arrayConver =  $this->conversationDAO->getUsersFromConver($chatCode);

            
        } catch (Exception $ex) {
            $arrayConver = $ex->getMessage();
        }
        return $arrayConver;
    }

    public function srv_sendMessage($senderCode, $msgText, $chatCode)
    {

        try {


            if ($chatCode != 0 && $chatCode != "") {

                $codes = $this->conversationDAO->getUsersFromConver($chatCode);

                if ($senderCode === $codes["ownerCode"]) {
                    $receiverCode = $codes["keeperCode"];
                    $senderCode = $codes["ownerCode"];
                } else if ($senderCode === $codes["keeperCode"]) {
                    $receiverCode = $codes["ownerCode"];
                    $senderCode = $codes["keeperCode"];
                }

                //Guardo el codigo obtenido directamente de la BD para compararlo con el que llega del controller originado de la sesion
                // $senderCode = $codes["ownerCode"];

            }

            $msgText = filter_var($msgText, FILTER_SANITIZE_SPECIAL_CHARS);
            $msgText = htmlspecialchars($msgText);
            $result  = $this->messageDAO->sendMessage($senderCode, $receiverCode, $msgText, $chatCode, 0);
            
        } catch (Exception $ex) {
            $result = $ex->getMessage();
        }
        return $result;
    }
    public function srv_GetMessages($codeSender, $chatCode)
    {
        try {

            $checkCodes = $this->conversationDAO->getUsersFromConver($chatCode);


            if ($checkCodes["keeperCode"] == $codeSender) {
                $receiverCode = $checkCodes["ownerCode"];
            } else if ($checkCodes["ownerCode"] == $codeSender) {
                $receiverCode = $checkCodes["keeperCode"];
            }
            $arrayMsges = $this->messageDAO->receiveMessage($codeSender, $receiverCode, $chatCode);

            return $arrayMsges;
        } catch (Exception $ex) {
            $arrayMsges = $ex->getMessage();
        }
        return $arrayMsges;
    }

    public function srv_getAvailTalk($codeLogged)
    {
        try {
            //$availTalk = $this->messageDAO->getBothBookingsUsers($codeLogged);
            $availTalk = $this->conversationDAO->getConverByUserCode($codeLogged);
            $newAvailTalk = array();
            foreach($availTalk as $conver)
            {
                $unreadMsges = $this->messageDAO->getUnseen($conver["codeConv"],$codeLogged);
                $conver["unread_messages"] = $unreadMsges;
                array_push($newAvailTalk,$conver);
            }
            
        } catch (Exception $ex) {
            $newAvailTalk =  $ex->getMessage();
        }
        return $newAvailTalk;
    }

    public function srv_checkPrevConv($receiverCode, $senderCode)
    {
        try {

            //0 == prevConver
            $result = $this->messageDAO->getChatCode($senderCode, $receiverCode);

            if ($result == 0) {
                $numbs = range(100000, 999999);
                shuffle($numbs);
                $uniqNum = implode('', array_slice($numbs, 0, 6));

                $checkConv = $this->messageDAO->getUsersFromChat($uniqNum);


                if ($checkConv == null) {
                    $result = $uniqNum;
                }
            }

            //return newCode or a code from an older conversation
            return $result;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function srv_getUsersConver($chatCode)
    {
        try {
            $codes = $this->conversationDAO->getUsersFromConver($chatCode);
            if ($codes["receiverCode"] == null && $codes["senderCode"] == null) {
                return null;
            } else {
                return $codes;
            }
        } catch (Exception $ex) {
            $ex->getMessage();
        }
    }

    public function srv_getUnreadMsg($converCode,$userLogged)
    {
        try{
            if($userLogged instanceof Keeper)
            {   
                $result = $this->messageDAO->getUnseen($converCode,$userLogged->getKeeperCode());
            }else{
                $result = $this->messageDAO->getUnseen($converCode,$userLogged->getOwnerCode());
            }
            return $result;
        }catch(Exception $ex)
        {
            $result = $ex->getMessage();
        }
    }
}
