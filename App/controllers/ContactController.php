<?php
namespace App\controllers;

class ContactController
{
    private $datas ;
    private $errors = [];

    /**
     * __construct
     *
     * @param mixed $datas the dataset comes from the contact form sent by the user
     *
     * @return void
     */
    public function __construct(array $datas)
    {
        $this->datas = $datas;
    }
    /**
     * ChekName allows to check the pseudonym presence
     * Otherwise an error will be affected in the errors array
     *
     * @param string $name the username comes from the contact form
     *
     * @return void
     */
    public function chekName(string $name):void
    {
        if (!array_key_exists('username', $this->datas) || $name === '') {
            $this->errors[] = 'vous devez renseigner un nom !';
        }
    }
    /**
     * The function chekEmail allows to check the email user presence
     * Otherwise an error will be affected in the errors array
     *
     * @param string $mail
     *
     * @return void
     */
    public function chekEmail(string $mail):void
    {
        if (!array_key_exists('email', $this->datas) || $mail === '') {
            $this->errors[] = 'vous devez renseigner un email !';
        } elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = 'vous devez entrer une adresse mail valide';
        }
    }
    /**
     * This function chekMessage allows to check the message presence
     * Otherwise an error will be affected in the errors array
     *
     * @param mixed $message message sent by user
     *
     * @return void
     */
    public function chekMessage(string $message):void
    {
        if (!isset($message) || $message === '') {
            $this->errors[] = "vous devez remplire le champ message";
        }
    }
    /**
     * The function getFaildes return us the errors array used in other function
     *
     * @return array
     */
    public function getFaildes():array
    {
        return $this->errors;
    }
    /**
     * The function sendMail allow to send an email to my acount
     *
     * @return string $success if the mail sent else $errorMessage
     */
    public function sendMail():string
    {
        $to ='allabsamir777@gmail.com';
        $subject = 'contact de mon blog';
        $message = $this->datas['message'];
        $from=$this->datas['email'];
        $header="MIME-Version:1.0\r\n";
        $header.= 'From :"blog.com"<'.htmlentities($from).'>'."\n".'Répondre à :
         <'.htmlentities($from).'>'."\n";
        $header.='Content-Type:text/html; charset="utf-8"'."/n";
        $header.='Content-Transfer-Encoding:8bit';
        'X-Mailer: PHP/' . phpversion();
        $success = mail($to, $subject, $message, $header);
        if (!$success) {
            $errorMessage = error_get_last()['message'];
            return $errorMessage;
        }
        return $success;
    }
}
