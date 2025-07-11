<?php

require_once(get_stylesheet_directory() . '/tools/firebase/vendor/autoload.php');
use Firebase\JWT\JWT;

function noshibari_jitsi_jwt(WP_REST_Request $req) {
  
  $res = new WP_REST_Response();

  try {    
    
    $apikey = get_option('noshibari_settings_jitsi_apikey', false);
    $appid = get_option('noshibari_settings_jitsi_appid', false);

    if(!$apikey && $appid) {

      throw new Exception('Faltan key o id.', 500);
    }

    $private_key = file_get_contents(__DIR__ . '/jitsi.pk');

    $params = $req->get_params();
    $email = $params['mail'];
    $title = $params['title'];

    $API_KEY = $apikey;
    $APP_ID = $appid; 
    $USER_EMAIL = $email;
    $USER_NAME = $email;
    $USER_IS_MODERATOR = true;
    $USER_AVATAR_URL = 'https://noshibari.art/wp-content/uploads/sites/5/2022/02/noshibari-art-logo-c-300x300.jpg';
    $USER_ID = $email;
    $LIVESTREAMING_IS_ENABLED = true;
    $RECORDING_IS_ENABLED = true;
    $OUTBOUND_IS_ENABLED = false;
    $TRANSCRIPTION_IS_ENABLED = false;
    $EXP_DELAY_SEC = 7200;
    $NBF_DELAY_SEC = 0;

    function create_jaas_token(
      $api_key,
      $app_id,
      $user_email,
      $user_name,
      $user_is_moderator,
      $user_avatar_url,
      $user_id,
      $live_streaming_enabled,
      $recording_enabled,
      $outbound_enabled,
      $transcription_enabled,
      $exp_delay,
      $nbf_delay,
      $private_key
    ) {

      $payload = array(
        'iss' => 'chat',
        'aud' => 'jitsi',
        'exp' => time() + $exp_delay,
        'nbf' => time() - $nbf_delay,
        'room'=> '*',
        'sub' => $app_id,
        'context' => [
          'user' => [
            'moderator' => $user_is_moderator ? 'true' : 'false',
            'email' => $user_email,
            'name' => $user_name,
            'avatar' => $user_avatar_url,
            'id' => $user_id
          ],
          'features' => [
            'recording' => $recording_enabled ? 'true' : 'false',
            'livestreaming' => $live_streaming_enabled ? 'true' : 'false',
            'transcription' => $transcription_enabled ? 'true' : 'false',
            'outbound-call' => $outbound_enabled ? 'true' : 'false'
          ]
        ]
      );

      return JWT::encode(
        $payload, 
        $private_key, 
        'RS256', 
        $api_key
      );
    }

    $token = create_jaas_token(
      $API_KEY,
      $APP_ID,
      $USER_EMAIL,
      $USER_NAME,
      $USER_IS_MODERATOR,
      $USER_AVATAR_URL,
      $USER_ID,
      $LIVESTREAMING_IS_ENABLED,
      $RECORDING_IS_ENABLED,
      $OUTBOUND_IS_ENABLED,
      $TRANSCRIPTION_IS_ENABLED,
      $EXP_DELAY_SEC,
      $NBF_DELAY_SEC,
      $private_key
    );

    $res->set_data($token);

  } catch (Exception $e) {
    
    $res->set_status($e->getCode());
    $res->set_data($e->getMessage());
  }

  return [
    'appid' => $appid,
    'jwt' => $res,
    'room' => $title
  ];
}

add_action(
  'rest_api_init',
  function () {

    register_rest_route(
      'noshibari',
      'jitsi/jwt',
      array(
        array(
          'methods'  => 'POST',
          'callback' => 'noshibari_jitsi_jwt',
          'permission_callback' => '__return_true'
        )
      )
    );
  }
);