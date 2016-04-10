<?php

namespace Core\Service;

use Core\Service\Service;
use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Mime;

/**
 * Serviço para enviar e-mails
 * @category Core
 * @package Service
 * @author Cezar
 */
class Email extends Service {

    protected $message;
    protected $from;
    protected $to;
    protected $cc;
    protected $title;
    protected $text;
    protected $options;
    protected $arquivo;

    /**
     * Função responsável por enviar e-mail
     */
    public function send($texto, $from, $to, $arquivo = null, $title, $cc = null, $urlArquivo = null, $options = null) {
        $this->message = new Message();

        if (isset($options)) {
            $this->setOptionsAlt($options[0]);
        } else {
            $this->setOptions();
        }
        $msgSistema = "\n\nEssa é uma mensagem gerada automaticamente pelo sistema. Por favor, não responda. \n\n";

        $texto = $texto . $msgSistema;
        $text = new MimePart($texto);
        $text->type = "text/plain";
        $html = new MimePart('');
        $html->type = 'text/html';

        $files = null;
        if ($arquivo) {
            foreach ($arquivo as $key => $a) {
                $data = file_get_contents(getcwd() . "$urlArquivo[$key]");
                $file = new MimePart($data);
                if (strstr($urlArquivo[$key], '.xml')) {
                    $type = 'xml';
                } else {
                    $type = 'pdf';
                }
                $file->type = "application/$type";
                $file->filename = "$a.$type";
                $file->disposition = Mime::DISPOSITION_ATTACHMENT;
                $file->encoding = 'quoted-printable';
                $files[] = $file;
            }
        }

        $body = new MimeMessage();
        if ($files) {
            $body->setParts(array($text, $html));
            foreach ($files as $file) {
                $body->addPart($file);
            }
        } else {
            $body->setParts(array($text, $html));
        }

        $this->message->setBody($body);
        $this->message->setFrom('no-reply@dimosolucoes.com.br', $from);
        $this->message->addTo($to['email'], $to['name']);

        if ($cc) {
            foreach ($cc as $copia)
                $this->message->addBcc($copia);
        }

        $this->message->setSubject($title);

        $transport = new SmtpTransport();
        $transport->setOptions($this->options);
        $transport->send($this->message);
    }

    //Seta as options de acordo com o sistema selecionado

    private function setOptions() {
        $this->options = new SmtpOptions(array(
            "name" => "dimosolucoes.com.br",
            "host" => "smtp.gmail.com",
            "port" => 465,
            "connection_class" => "login",
            "connection_config" => array(
                "username" => "no-reply@dimosolucoes.com.br",
                'password' => "Yp30R3s3t55",
                "ssl" => "ssl"
        )));
    }

    private function setOptionsAlt($options) {
        if ($options['tls'] == null) {
            $ssl = "ssl";
        } else {
            $ssl = $options['tls'];
        }
        $this->options = new SmtpOptions(array(
            "name" => $options['smtp_nome'],
            "host" => "smtp.gmail.com",
            "port" => (int) $options['porta'],
            "connection_class" => "login",
            "connection_config" => array(
                "username" => $options['usuario'],
                'password' => base64_decode($options['senha']),
                "ssl" => $ssl
        )));
    }

}

?>
