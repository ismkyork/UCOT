<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    public string $fromEmail  = 'ucot2025@gmail.com';
    public string $fromName   = 'UCOT - Sistema de Retiros';
    public string $recipients = '';

    public string $userAgent = 'CodeIgniter';

    // CAMBIAR DE 'mail' A 'smtp'
    public string $protocol = 'smtp';

    public string $mailPath = '/usr/sbin/sendmail';

    // CONFIGURACIÓN SMTP DE GMAIL
    public string $SMTPHost = 'smtp.gmail.com';
    public string $SMTPUser = 'ucot2025@gmail.com';
    public string $SMTPPass = 'mihl dsml ybvv wsgt'; // ⚠️ CAMBIA ESTO por tu contraseña de aplicación
    public int $SMTPPort = 587;
    public int $SMTPTimeout = 10;
    public bool $SMTPKeepAlive = false;
    public string $SMTPCrypto = 'tls'; // IMPORTANTE: 'tls' para Gmail

    public bool $wordWrap = true;
    public int $wrapChars = 76;
    
    // CAMBIAR DE 'text' A 'html'
    public string $mailType = 'html';
    
    public string $charset = 'UTF-8';
    public bool $validate = true;
    public int $priority = 3;
    public string $CRLF = "\r\n";
    public string $newline = "\r\n";
    public bool $BCCBatchMode = false;
    public int $BCCBatchSize = 200;
    public bool $DSN = false;
}
