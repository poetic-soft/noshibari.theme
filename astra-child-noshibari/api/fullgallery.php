<?php

function noshibari_fullgallery_images( WP_REST_Request $req ) {
      
  $res = new WP_REST_Response();  

  try { 
  
    $path = $req->get_param('path');
    $directorio = ABSPATH . $path;
    $extensionesImagen = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'];
    $archivos = scandir($directorio);
    $imagenes = array_filter(
      $archivos, 
      function($archivo) use ($directorio, $extensionesImagen) {

        $rutaCompleta = $directorio . DIRECTORY_SEPARATOR . $archivo;
        $extension = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));
        
        return is_file($rutaCompleta) && in_array($extension, $extensionesImagen);
      }
    );

    $res->set_data(array_values($imagenes));
  
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
      'fullgallery/images',
      [
        'methods'  => 'POST',
        'callback' => 'noshibari_fullgallery_images',
        'permission_callback' => '__return_true'
      ]
    );
  }
);