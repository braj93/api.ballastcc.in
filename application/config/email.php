<?php

defined('BASEPATH') OR exit('No direct script access allowed');
$config['protocol'] = 'smtp';
$config['smtp_host'] = 'email-smtp.us-west-2.amazonaws.com';
$config['smtp_user'] = 'AKIA3VLOTZUOKI4RHE6Z';
$config['smtp_pass'] = 'BCIRy8I5eqlANLKGEsgVFjiQo7UuGJ8A0L72Sd+oVjTg';
$config['smtp_crypto'] = 'tls';
$config['smtp_port'] = '587';
$config['mailtype'] = 'html';
$config['newline'] = "\r\n";
$config['wordwrap'] = TRUE;
// switch (ENVIRONMENT) {
//     case 'production':
//         $config['protocol'] = 'mail';
//         $config['mailtype'] = 'html';
//         $config['newline'] = "\r\n";
//         $config['wordwrap'] = TRUE;
//         break;
//     case 'staging':
//         $config['protocol'] = 'mail';
//         $config['mailtype'] = 'html';
//         $config['newline'] = "\r\n";
//         $config['wordwrap'] = TRUE;
//         break;
//     case 'development':
//         $config['protocol'] = 'mail';
//         $config['mailtype'] = 'html';
//         $config['newline'] = "\r\n";
//         $config['wordwrap'] = TRUE;
//         break;
//     case 'local':
//         $config['protocol'] = 'smtp';
//         $config['smtp_host'] = SMTP_HOST;
//         $config['smtp_user'] = SMTP_USER;
//         $config['smtp_pass'] = SMTP_PASSWORD;
//         $config['smtp_crypto'] = 'tls';
//         $config['smtp_port'] = '587';
//         $config['mailtype'] = 'html';
//         $config['newline'] = "\r\n";
//         $config['wordwrap'] = TRUE;
//         break;
//     default:
// }