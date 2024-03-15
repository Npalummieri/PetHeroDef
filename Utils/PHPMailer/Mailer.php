<?php 

namespace Utils\PHPMailer;


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
use Dompdf\Dompdf as Dompdf;
use Dompdf\Options as Options;


require 'Utils\PHPMailer\Exception.php';
require 'Utils\PHPMailer\PHPMailer.php';
require 'Utils\PHPMailer\SMTP.php';
require 'Utils\vendor\autoload.php';



class Mailer{

    private $mail;
    private $dompdf;
    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        //con \mpdf busco en todos los namespaces y no solo en el que estoy ahora (PHPMAILER)
        $this->dompdf = new Dompdf();
        try
        {
                    //Server settings
            $this->mail->SMTPDebug = 0;                      //Enable verbose debug output
            $this->mail->isSMTP();                                            //Send using SMTP
            $this->mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $this->mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $this->mail->Username   = 'cuenta7ds1@gmail.com';                     //SMTP username
            $this->mail->Password   = 'zqtz zozz idjl cvcd';                               //SMTP password
            $this->mail->SMTPSecure = "tls";            //Enable implicit TLS encryption
            $this->mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        }catch(Exception $ex)
        {
            echo "Something failed with the settings. Mailer Error: {$this->mail->ErrorInfo}";
        }
    }

    public function sendingEmail($recipient,$fullCoup,$file)
    {
        try {

            
            //Recorremos el mismo array con func anon,y va checkeando el valor de c/ value en particular sin foreach y loops
            $filteredInfo = array_filter($fullCoup, function($value){
                return !is_null($value) && $value !== 0 && $value !== '';
            }
        );

        //Compare how many elements in each array,we assume that if the filteredInfo has less elements some of the originals could be 0 or ' ' or null
        if(count($fullCoup) == count($filteredInfo))
        {
            //Recipients
            $this->mail->setFrom('cuenta7ds1@gmail.com', 'Mailer');// ¿Enviar desde una constante? 
            $this->mail->addAddress($recipient, '');     //Add a recipient
            //$this->mail->addAddress('ellen@example.com');               //Name is optional
            //$this->mail->addReplyTo('info@example.com', 'Information');
            //$this->mail->addCC('cc@example.com');
            //$this->mail->addBCC('bcc@example.com');
        
            //Content
            $this->mail->isHTML(true);                                  //Set email format to HTML
            $this->mail->Subject = 'Paid up confirmation!';
            //$this->mail->Body    = 'We attached your coupon information related to your booking! Thanks you!</b>';
            $this->mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            // Generar HTML desde un archivo PHP
            ob_start();
            include $file;
            $htmlContent = ob_get_clean();

            // Agregar un mensaje al principio del contenido HTML
            $htmlContent = '<p>This is your coupon <strong> '.$fullCoup["ownerName"].' </strong> with all the reservation info. DO NOT SHARE </p>' . $htmlContent;
 
            //base64= encodeo y el application/pdf es para aclararle al cliente que tipo de archivo recibira (MiME -id-)
            $this->mail->msgHTML($htmlContent);
            $this->dompdf->loadHtml($htmlContent);
            // Configura las opciones (si es necesario)
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $this->dompdf->setOptions($options);

            // Renderizar el PDF
            $this->dompdf->render();

            $pdfString = $this->dompdf->output();
            $this->mail->addStringAttachment($pdfString, 'coupon.pdf', 'base64', 'application/pdf');
            $this->mail->send();
            echo 'Message has been sent';
            $sended = true;
        }else{
            throw new Exception("Something's wrong with the coupon.Contact support");
            $sended = false;
        }
        } catch (Exception $e) {

            $sended = false;
            throw $e;
            echo $e->getMessage();
            echo "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
        }
        return $sended;
    }


}


?>