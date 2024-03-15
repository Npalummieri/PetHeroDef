<?php

namespace Controllers;

use Models\Message as Message;
use DAO\MessageDAO as MessageDAO;
use Services\MessageService as MessageService;
use Utils\Session as Session;
use Models\Keeper as Keeper;
use Models\Owner as Owner;

class MessageController{

    private $messageService;

    public function __construct()
    {
        $this->messageService = new MessageService();
    }

    //Funcion que va a comenzar la conversacion y generar un codeChat en base al primer mensaje para que quede 'asentado' 
    /*el espacio donde hablaran
    public function startConversation($senderCode,$receiverCode,$msgText)
    {
        $this->messageService->srv_setConver($senderCode,$receiverCode,$msgText);
    }*/

    public function sendMessage($msgText,$converCode)
    {
        
        $logged = Session::GetLoggedUser();
        $arrayCodes = $this->messageService->srv_getInfoConver($converCode);
        
        
        if($logged instanceof Keeper)
        {
            $senderCode = $logged->getKeeperCode();
        }else if($logged instanceof Owner)
        {
            $senderCode = $logged->getOwnerCode();
        }

        //La logica es si del converCode recuperado alguno de los 2 codigos corresponde a la conversacion se asigna aquel que recibe 
        //Por eso si el receiver ahora es el sender ,el sender sera que el reciba
        //Si el sender coincide el que recibe se mantiene como tal
        if($arrayCodes["keeperCode"] === $senderCode)
        {
            $receiverCode = $arrayCodes["ownerCode"];
        }else if ($arrayCodes["ownerCode"] === $senderCode)
        {
            $receiverCode = $arrayCodes["keeperCode"];
        }

        if($arrayCodes != null)
        {
            $msgSended = $this->messageService->srv_sendMessage($senderCode,$msgText,$converCode);
        }
        
       
        $msgeJsonEncod = json_encode($msgSended);

        echo $msgeJsonEncod;
    }

    //Importante que los nombres de la variable coincidan en JS tambn
    public function getMessages($converCode)
    {   
        $loggedUser = Session::GetLoggedUser();
        $senderCode = null;
        $arrayMessages = array();
        if($loggedUser instanceof Keeper)
        {
            $senderCode = $loggedUser->getKeeperCode();
        }else if($loggedUser instanceof Owner)
        {
            $senderCode = $loggedUser->getOwnerCode();
        }

        //echo "SENDER CODE $senderCode LOGGED USER".$loggedUser->getKeeperCode()." CONVER CODE : $converCode";
        if($senderCode != null)
        {
            $arrayMessages = $this->messageService->srv_GetMessages($senderCode,$converCode);
        }
        
        $arrayMessages["currentUserCode"] = $senderCode;
        $jsonArray = json_encode($arrayMessages);

        echo $jsonArray;
       
    }

    //traer los usuarios con los que tenga booking confirmed
    public function toInbox()
    {
        $loggedUser = Session::GetLoggedUser();
        
        if(is_a($loggedUser,"Models\Keeper"))
        {
            $code = $loggedUser->getKeeperCode();
        }else if(is_a($loggedUser,"Models\Owner"))
        {
            $code = $loggedUser->getOwnerCode();
        }

        $arrayInfoConver = $this->messageService->srv_getInfoConver($code);
        //var_dump($loggedUser);
        //$userAvaiArr = $this->messageService->srv_UsersAvaiToText($code);
        require_once(VIEWS_PATH."messagesView.php");
    }

    public function getConverInfo($codeChat)
    {
        $arrayInfoConver = $this->messageService->srv_getInfoConver($codeChat);
        require_once(VIEWS_PATH."messagesView.php");
    }

    // public function getConversation($codeLogged)
    // {
    //     echo "HOLA?";
    //     $arrayInfoConver = $this->messageService->srv_getInfoConver($codeLogged);
    //     require_once(VIEWS_PATH."messagesView.php");
    // }
    public function messageView($userAvaiArr)
    {
        require_once(VIEWS_PATH."messagesView.php");
    }

    public function getAvailTalk()
    {
        $loggedUser = Session::GetLoggedUser();
        if($loggedUser != null)
        {
            if(is_a($loggedUser,"Models\Keeper"))
            {
               $availTalk = $this->messageService->srv_getAvailTalk($loggedUser->getKeeperCode());
               $availTalk[0]["logged"] = "keeper";
            }else if(is_a($loggedUser,"Models\Owner"))
            {
               $availTalk =  $this->messageService->srv_getAvailTalk($loggedUser->getOwnerCode());
               $availTalk[0]["logged"] = "owner";
            }
        }
        //var_dump($availTalk);
        $encodedContent = json_encode($availTalk);
        //var_dump($encodedContent);
        echo $encodedContent;
    }

    public function checkChatCode($userToSendCode,$chatCode)
    {

                
        $logged = Session::GetLoggedUser();
        $arrayCodes = $this->messageService->srv_getUsersConver($chatCode);
        
        
        if(is_a($logged,"Models/Keeper"))
        {
            
            $senderCode = $logged->getKeeperCode();
        }else
        {
            $senderCode = $logged->getOwnerCode();
        }

        //La logica es si del chatCode recuperado alguno de los 2 codigos corresponde a la conversacion se asigna aquel que recibe 
        //Por eso si el receiver ahora es el sender ,el sender sera que el reciba
        //Si el sender coincide el que recibe se mantiene como tal
        if($arrayCodes["receiverCode"] === $senderCode)
        {
            $receiverCode = $arrayCodes["senderCode"];
        }else if ($arrayCodes["senderCode"] === $senderCode)
        {
            $receiverCode = $arrayCodes["receiverCode"];
        }

        //Si no hay codigo de chat puede que la conversacion sea nueva
        if($chatCode == null)
        {
            //Rechequeamos si hay conversacion entre estas 2 partes,si la hay devuelve el chatCode en BD
            //Sino devuelve un nuevo chatCode
            $rspChatCode = $this->messageService->srv_checkPrevConv($senderCode,$receiverCode);
        }
        
        $rspEncoded = json_encode($rspChatCode);

        echo $rspEncoded;
    }



}

?>