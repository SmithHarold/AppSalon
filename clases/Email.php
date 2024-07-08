<?php

namespace Clases;

use PHPMailer\PHPMailer\PHPMailer;

class Email {
    public $nombre;
    public $email;
    public $token;

    public function __construct($nombre, $email, $token)
    {
        $this->nombre = $nombre;
        $this->email = $email;
        $this->token = $token;
        
    }

    public function enviarConfirmacion() {
        //crear el objeto de email
        $mail = new PHPMailer();


        //configurar el SMTP (protocolo para el envio de emails)
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;//para autenticarse
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');


        // $mail->SMTPSecure = 'tls';//protocolo de cifrado de la informacion

        //configuarar el conternido del email
        $mail->Subject = 'Confirma tu cuenta';


        //habilitar HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';



         //definir el contenido
         $contenido = "<html>";
         $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> has creado tu cuenta en App Salon, Solo debes confirmarla presionando el siguiente enlace. </p>"; 
         $contenido .= "<p>Presiona aqui: <a href='" . $_ENV['PROJECT_URL'] . "/confirmar-cuenta?token=" . $this->token . "'>Confirmar Cuenta</a></p>";
         $contenido .= "<p>Sin no solicitaste una cuenta, ignora este mensaje.</p>";
         $contenido .= "</html>";

         //Habilitar el contenido
         $mail->Body = $contenido;
        //  $mail->AltBody = 'Esto es texto alternativo sin HTML';

        //enviar el email
        $mail->send();
    }

    public function enviarInstrucciones() {
        //crear el objeto de email
        $mail = new PHPMailer();

        //configurar el SMTP (protocolo para el envio de emails)
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;//para autenticarse
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');


        // $mail->SMTPSecure = 'tls';//protocolo de cifrado de la informacion

        //configuarar el conternido del email
        $mail->Subject = 'Reestablece tu contraseña';


        //habilitar HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';



         //definir el contenido
         $contenido = "<html>";
         $contenido .= "<p> Hola <strong> " . $this->nombre . "</strong> has solicitado reestablecer tu contraseña, sigue el siguiente enlace para hacerlo. </p>"; 
         $contenido .= "<p>Presiona aqui: <a href='" . $_ENV['PROJECT_URL'] . "/recuperar?token=" . $this->token . "'>Reestablecer Contraseña</a></p>";
         $contenido .= "<p>Si no solicitaste este cambio, ignora este mensaje.</p>";
         $contenido .= "</html>";

         //Habilitar el contenido
         $mail->Body = $contenido;
        //  $mail->AltBody = 'Esto es texto alternativo sin HTML';

        //enviar el email
        $mail->send();
    }
}