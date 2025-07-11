<?php

require_once(get_stylesheet_directory() . '/tools/guzzle/vendor/autoload.php');
use GuzzleHttp\Client;
require_once(get_stylesheet_directory() . '/tools/firebase/vendor/autoload.php');
use Firebase\JWT\JWT;

function noshibari_maillist_list(WP_REST_Request $req) {
  
  $res = new WP_REST_Response();

  try {     

    $creds = json_decode(file_get_contents(__DIR__ . '/noshibari-art-1703019679358-66664b4bb927.json'), true);
    $now = time();
    $jwt_payload = [
      'iss' => $creds['client_email'],
      'scope' => 'https://www.googleapis.com/auth/spreadsheets.readonly',
      'aud' => $creds['token_uri'],
      'exp' => $now + 3600,
      'iat' => $now,
    ];
    $jwt = JWT::encode($jwt_payload, $creds['private_key'], 'RS256');
    $client = new Client();
    $response = $client->post($creds['token_uri'], [
      'form_params' => [
        'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
        'assertion' => $jwt
      ]
    ]);
    $token = json_decode($response->getBody(), true)['access_token'];
    $spreadsheetId = '1OUPTGu_-ClP5T2pqbQk06HbFlUa1woIEyzToDv4c5NE';
    $range = 'mails!A1:A1000';
    $response = $client->get("https://sheets.googleapis.com/v4/spreadsheets/{$spreadsheetId}/values/{$range}", [
      'headers' => [
        'Authorization' => "Bearer $token"
      ]
    ]);

    $data = json_decode($response->getBody(), true);
    $list = array_map(
      function($value) {
        
        return $value[0];
      },
      $data['values']
    );
    $uniquevalues = [];
    $repeatedvalues = [];
    foreach($list as $value) {

      if(in_array($value, $uniquevalues)) {

        $repeatedvalues[] = $value;

      } else {

        $uniquevalues[] = $value;
      }
    }

    $res->set_data([
      'count' => count($uniquevalues),
      'unique' => $uniquevalues,
      'repeated' => $repeatedvalues
    ]);

  } catch (Exception $e) {
    
    $res->set_status($e->getCode());
    $res->set_data($e->getMessage());
  }

  return $res;
}

function noshibari_maillist_checkmail_leer($fp) {

  return fgets($fp, 1024);
}

function noshibari_maillist_checkmail_leerescribir($fp, $cmd) {

  fwrite($fp, $cmd . "\r\n");
}

function noshibari_maillist_checkmail(WP_REST_Request $req) {
  
  $res = new WP_REST_Response();

  try {  
    
    $email = $req->get_param('mail');

    $access_key = "1f474852e9b0ba7dc449a8f0a0e1d72f";

    $url = "http://apilayer.net/api/check?access_key=$access_key&email=" . urlencode($email) . "&smtp=1&format=1";

    $response = file_get_contents($url);
    $data = json_decode($response, true);
    $valid = $data['smtp_check'];

    if($valid) {

      $res->set_data([
        'valid' => true,
        'check' => $data,
        'reasons' => 'Email válido y existe en servidor'
      ]);

    } else {

      $res->set_data([
        'valid' => false,
        'check' => $data,
        'reasons' => 'Email inválido o no entregable'
      ]);
    }

  } catch (Exception $e) {
    
    $res->set_status($e->getCode());
    $res->set_data($e->getMessage());
  }

  return $res;
}

add_action(
  'rest_api_init',
  function () {

    register_rest_route(
      'noshibari',
      'maillist/list',
      array(
        array(
          'methods'  => 'GET',
          'callback' => 'noshibari_maillist_list',
          'permission_callback' => '__return_true'
        )
      )
    );

    register_rest_route(
      'noshibari',
      'maillist/checkmail',
      array(
        array(
          'methods'  => 'POST',
          'callback' => 'noshibari_maillist_checkmail',
          'permission_callback' => '__return_true'
        )
      )
    );
  }
);