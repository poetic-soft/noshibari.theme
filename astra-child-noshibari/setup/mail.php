<?php

add_action(
  'phpmailer_init', 
  function($phpmailer) {

    $phpmailer->isSMTP();
    $phpmailer->Host = 'ssl0.ovh.net';
    $phpmailer->SMTPAuth = true;
    $phpmailer->Port = 465;
    $phpmailer->Username = 'hola@noshibari.com';
    $phpmailer->Password = 'JsAU8)0000';
    $phpmailer->SMTPSecure = 'ssl';
    $phpmailer->From = 'hola@noshibari.com';
    $phpmailer->FromName = 'NOSHIBARI';    
    $phpmailer->isHTML(true);
  }
);

add_action(
  'wp_mail_failed',
  function ($wp_error) {

    error_log('wp_mail_failed');
    error_log(json_encode($wp_error));
  } ,
  10, 
  1 
);