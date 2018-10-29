<?php

function reArrayFiles($file) {
  $file_ary = array();
  $file_count = count($file['name']);
  $file_key = array_keys($file);
  
  for($i=0;$i<$file_count;$i++) {
    foreach($file_key as $val) {
      $file_ary[$i][$val] = $file[$val][$i];
    }
  }
  return $file_ary;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$path = realpath(dirname(__FILE__));

require $path.'/lib/PHPMailer/Exception.php';
require $path.'/lib/PHPMailer/PHPMailer.php';
require $path.'/lib/PHPMailer/SMTP.php';

if ($_POST) {

  if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
    $response = [
      'error' => 'Sorry Request must be Ajax POST'
    ];

    die(json_encode($response));
  }

  $msg        = '';
  $subject    = '';
  $data       = [];
  $site       = $_SERVER['SERVER_NAME'];
  $from_email = 'noreply@' . $site;
  $to_email   = 'test@test.com';

  $files = [];

  if (isset($_FILES['files']) && !empty($_FILES['files'])) {
    $files = reArrayFiles($_FILES['files']);
  }


  foreach ($_POST as $k => $v) {
    if ($k == 'form-spec-comments') break; // no tilda additional info
    if (strpos($v, 'upwidget') === 0) continue; // no tilda attachmnets
    if ($k == 'tildaspec-formname') {
      $data['Форма'] = $v;
    } else {
      $data[$k] = $v;
    }
  }

  $data['Referer'] = $_POST['tildaspec-referer'];
  $subject = 'Заявка с формы ['.$data['Форма'].'] на сайте '.$site;

  foreach ($data as $k => $v) {
    $msg .= '<b>'.$k.':</b> '.$v.'<br>';
  }

  $mail = new PHPMailer;
  $mail->CharSet  = 'UTF-8';
  $mail->setFrom($from_email, 'Mailer');
  $mail->Subject  = $subject;
  $mail->Body     = $msg;
  $mail->IsHTML(true);
  $mail->addAddress($to_email);

  if (!empty($files)) {
    foreach ($files as $file) {
      $mail->AddAttachment($file['tmp_name'], $file['name']);
    }
  }

  if ($mail->send()) {
    $response = [
      'message' => 'OK' // Message sent successfully!
    ];
  } else {
    $response = [
      'error' => 'Could not send mail! Please check your PHP mail configuration.'
    ];
  }

  die(json_encode($response));
} ?>