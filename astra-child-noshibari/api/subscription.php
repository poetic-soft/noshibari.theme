<?php

function noshibari_subscribe( WP_REST_Request $req ) {

  $res = new WP_REST_Response();

  try {
  
    $email = $req->get_param('email');
    $title = $req->get_param('title');
    
    $mailsent = wp_mail(
      'noshibariart@gmail.com',
      'Subscription email',
      $email . ' ' . $title
    );

    if(!$mailsent) throw new Exception('Error enviando email', 500);

    $res->set_data('ok');
    
  } catch (Exception $e) {
    
    $res->set_status($e->getCode());
    $res->set_data($e->getMessage());
  }

  return $res;
}

add_action(
  'rest_api_init',
  function () {

    error_log('api suscrip');

    register_rest_route(
      'noshibari',
      'subscribe',
      [
        'methods' => 'POST',
        'callback' => 'noshibari_subscribe',
        'permission_callback' => '__return_true'
      ]
    );
  }
);