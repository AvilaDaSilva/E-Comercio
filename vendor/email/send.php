<?php

require 'mail/smtp/smtp.php';
require 'mail/sasl/sasl.php';

function enviaEmail($msg) {
    \date_default_timezone_set('America/Sao_Paulo');
    $from = 'no-reply@dimosolucoes.com.br';
    $to = implode(PHP_EOL, ['joaovicente@unochapeco.edu.br']);
    $smtp = new smtp_class;
    $smtp->host_name = "smtp.gmail.com";
    $smtp->host_port = 465;
    $smtp->user = 'no-reply@dimosolucoes.com.br';
    $smtp->password = 'Yp30R3s3t55';
    $smtp->ssl = 1;
    $smtp->debug = 0;
    $smtp->html_debug = 0;
    $header = [
        "From: $from",
        "To: $to",
        "Subject: Erro NF-e",
        "Date: " . strftime("%a, %d %b %Y %H:%M:%S %Z"),
        "Content-Type: Text/HTML; charset=utf-8"];

    if ($smtp->SendMessage($from, [$to], $header, $msg)) {
        echo "Enviado e-mail\n";
    } else {
        echo "Erro ao enviar e-mail: $smtp->error\n";
    }
}
