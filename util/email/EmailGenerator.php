<?php

namespace util\email;

use util\config\Config;
use util\file\File;
use util\logger\Logger;
use util\PHPMailer\Email;

class EmailGenerator {

    private $file;
    private $data;
    private $email;
    private $url_sistema;
    private $permission_user_group;
    private static $instance;

    public function __construct() {
        $this->data = array();
        $this->file = new File();
        $this->email = new Email();

        $this->url_sistema = Config::get('url_sistema');
        $this->data['sistema'] = $this->url_sistema;
        $this->data['logo'] = $this->url_sistema . 'assets/img/comercam.jpg';
    }

    public static function init() {
        if (!isset(self::$instance)) {
            self::$instance = new EmailGenerator();
        }

        return self::$instance;
    }

    private function sendEmail($correo, $template, $ignore = false) {
       /**
        * Envio de correo si es modo desarrollador
        */
       if (Config::get("is_dev", "sys_config")) {
          if ($ignore) {
             $emailModel = new Email();                                                                                                            //Crea el contenido, y template del email
             return $response = $emailModel->enviarEmail(Config::get("from_dev", "mail_config"), null, $this->data['asunto'], $this->file->getContentForEmail($template, $this->data), Config::get("correo_dev", "sys_config"));
             //return $this->email->envia_email(NULL, $correo, $this->data['asunto'], $this->file->getContentForEmail($template, $this->data));
          }
       } else { //Envio de correo para modo produccion
          if ($ignore) {
             $emailModel = new Email();
             return $response = $emailModel->enviarEmail(Config::get("from_prod", "mail_config"), $correo, $this->data['asunto'], $this->file->getContentForEmail($template, $this->data));
             //return $this->email->envia_email(NULL, $correo, $this->data['asunto'], $this->file->getContentForEmail($template, $this->data));
          }
       }

        return false;
    }

    public function sendWelcomeEmail($correo, $nombre_usuario) {
        $this->data['nombre_usuario'] = $nombre_usuario;
        $this->data['asunto'] = _("Bienvenido a TocaJazz");

        return $this->sendEmail($correo, "welcome");
    }

    public function sendWelcomeAndPasswordEmail($correo, $nombre_usuario, $password) {
        $this->data['password'] = $password;
        $this->data['nombre_usuario'] = $nombre_usuario;
        $this->data['asunto'] = _("Bienvenido a TocaJazz");

        return $this->sendEmail($correo, "welcome_password");
    }

    public function sendUserBlocked($correo, $accion, $reason) {
        $this->data['reason'] = $reason;
        $this->data['accion'] = $accion;
        $this->data['asunto'] = sprintf(_("Cuenta de usuario %s"), $accion);

        return $this->sendEmail($correo, "blocked_login");
    }

    public function sendDataUserModified($correo_before, $correo, $nombre_usuario_before, $nombre_usuario, $status_before, $status) {
        $this->data['status'] = $status;
        $this->data['correo'] = $correo;
        $this->data['status_before'] = $status_before;
        $this->data['correo_before'] = $correo_before;
        $this->data['nombre_usuario'] = $nombre_usuario;
        $this->data['asunto'] = _("Cuenta de usuario modificada");
        $this->data['nombre_usuario_before'] = $nombre_usuario_before;

        return $this->sendEmail($correo, "user_data_modified");
    }

	public function sendRecoverPassword( $correo, $token) {
		$this->data['link'] = $this->url_sistema.'login/recover_password';
		$this->data['asunto'] = "Solicitud de recuperación de contraseña";
		$this->data['token'] = $token;

		return $this->sendEmail($correo, "recover", true);
	}

    public function sendRecoveredPassword($correo) {
        $this->data['asunto'] = _("Recuperacion de contrasena exitosa");

        return $this->sendEmail($correo, "recovered", true);
    }

    public function sendAgreementPayment($start_date, $end_date, $next_payment_date, $frequency, $course, $price, $correo) {
        $this->data['price'] = $price;
        $this->data['course'] = $course;
        $this->data['end_date'] = $end_date;
        $this->data['frequency'] = $frequency;
        $this->data['start_date'] = $start_date;
        $this->data['next_payment_date'] = $next_payment_date;
        $this->data['asunto'] = _("Pago de suscripcion exitoso");

        return $this->sendEmail($correo, "agreement_payment", true);
    }

    public function sendAgreementFailed($start_date, $end_date, $frequency, $course, $price, $correo) {
        $this->data['price'] = $price;
        $this->data['course'] = $course;
        $this->data['end_date'] = $end_date;
        $this->data['frequency'] = $frequency;
        $this->data['start_date'] = $start_date;
        $this->data['asunto'] = _("Pago de suscripcion erroneo");

        return $this->sendEmail($correo, "agreement_failed", true);
    }

    public function sendRequestEvaluation($correo, $curso, $evaluacion, $nombre_usuario, $link) {
        $this->data['curso'] = $curso;
        $this->data['evaluacion'] = $evaluacion;
        $this->data['nombre_usuario'] = $nombre_usuario;
        $this->data['url_calificar'] = $this->url_sistema . $link;
        $this->data['asunto'] = _("Nueva solicitud de evaluacion");

        return $this->sendEmail($correo, "request_evaluation", true);
    }

    public function sendCancelPayment($user_email, $reason, $payment_product, $pasarela, $method) {
        $this->data['reason'] = $reason;
        $this->data['method'] = $method;
        $this->data['email'] = $user_email;
        $this->data['pasarela'] = $pasarela;
        $this->data['payment_product'] = $payment_product;
        $this->data['asunto'] = _("Pago/suscripcion cancelada");

        return $this->sendEmail("cvillarino@grupoicarus.com.mx", "payment_cancel", true);
    }

    public function sendCancelMembership($user_email, $usuario, $curso, $membresia) {
        $this->data['curso'] = $curso;
        $this->data['usuario'] = $usuario;
        $this->data['membresia'] = $membresia;
        $this->data['asunto'] = _("Membresia cancelada");

        return $this->sendEmail($user_email, "membership_canceled");
    }

    public function sendPaymentLessonNotification($user_email, $curso, $leccion) {
        $this->data['curso'] = $curso;
        $this->data['asunto'] = _("Pago de leccion exitoso");
        $this->data['nombre_leccion'] = $leccion->getTitle();
        $this->data['precio_leccion'] = "$ " . number_format($leccion->getPrice(), 2) . " USD";
        $this->data['url_leccion'] = $this->url_sistema . 'dashboard/leccion_usuario/' . $leccion->getId();

        return $this->sendEmail($user_email, "payment_lesson");
    }

    public function sendPaymentCard(&$requestParams, $asunto, $template) {
        $this->data['asunto'] = $asunto;
        $this->data['spei_brand'] = $this->url_sistema . 'assets/img/spei_brand.png';
        $this->data['oxxo_brand'] = $this->url_sistema . 'assets/img/oxxo_brand.png';

        $price = $requestParams->fromSession('price');
        $currency = $requestParams->fromSession('currency');
        $reference = $requestParams->fromSession('reference');
        $email = $requestParams->fromSession('email_card_payment');
        $expiration_date = $requestParams->fromSession('expiration_date');

        ob_start();

        extract($this->data);

        include 'templates/email/top.php';
        include $template . '.php';
        include 'templates/email/bottom.php';

        $email_body = ob_get_clean();

        if (!empty($email)) {
            $requestParams->unsetSessionParam('email_card_payment');
            $this->email->envia_email(NULL, $email, $asunto, $email_body);
        }

        return $email_body;
    }
}