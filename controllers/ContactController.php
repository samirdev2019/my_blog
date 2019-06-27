<?php
class ControllerContact{
    private $datas ;
    private $errors = [];

    public function __construct(array $datas){
        $this->datas = $datas;
    }
    public function chekName($name){
        if(!array_key_exists('username',$this->datas) || $name === ''){
            $this->errors[] = 'vous devez renseigner un nom !';
        }
        

    }
    public function chekEmail($mail){
        if(!array_key_exists('email',$this->datas) || $mail === ''){
            $this->errors[] = 'vous devez renseigner un email !';
        }elseif(!filter_var($mail,FILTER_VALIDATE_EMAIL)){
            $this->errors[] = 'vous devez entrer une adresse mail valide';
        }
        

    }
    public function chekMessage($message){
        if(!isset($message) || $message === ''){
            $this->errors[] = "vous devez remplire le champ message";
        }
    }
    public function getFaildes(){
        return $this->errors;
    }
    public function sendMail(){
        $to ='allabsamir777@gmail.com';
        $subject = 'contact de mon blog';
        $message = $this->datas['message'];
        // sending mail with this header  cased a problem with the free smtp 
        $header="MIME-Version:1.0\r\n";
        $header.= 'From :"blog.com"<'.$this->datas['email'].'>'."\n".'Répondre à : <'.$this->datas['email'].'>'."\n";
        $header.='Content-Type:text/html; charset="utf-8"'."/n";
        $header.='Content-Transfer-Encoding:8bit';
        'X-Mailer: PHP/' . phpversion();
        $success = mail($to,$subject,$message,$header);
        
        if (!$success) {
            $errorMessage = error_get_last()['message'];
            return $errorMessage;
        }
        return $success;
    }
}


