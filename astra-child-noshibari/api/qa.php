<?php

function noshibari_qa( WP_REST_Request $req ) {

  $res = new WP_REST_Response();

  try {
  
    $title = $req->get_param('title');
    $qa = $req->get_param('qa');
    
    $mailsent = wp_mail(
      'noshibariart@gmail.com',
      'QA',
      $title . ' · ' . $qa
    );

    if(!$mailsent) throw new Exception('Error enviando mensaje, vuelve a intentarlo, por favor.', 500);

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

    register_rest_route(
      'noshibari',
      'qa',
      [
        'methods' => 'POST',
        'callback' => 'noshibari_qa',
        'permission_callback' => '__return_true'
      ]
    );
  }
);