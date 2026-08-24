<?php

add_action(
  'phpmailer_init', 
  function($phpmailer) {

    $phpmailer->isSMTP();
    $phpmailer->Host = 'ssl0.ovh.net';
    $phpmailer->SMTPAuth = true;
    $phpmailer->Port = 465;
    $phpmailer->Username = 'hola@noshibari.com';
    $phpmailer->Password = 'JsAU8)0987654';
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

// Añadir etiquetas Open Graph y Twitter Cards sin plugins
add_action(
  'wp_head',  
  function () {
    $imagen_defecto = 'https://noshibari.art/wp-content/uploads/sites/5/2025/04/noshibari-art-logo-e.png';

    if (is_singular() && !is_front_page()) {
        global $post;
        $titulo = esc_attr(get_the_title());
        $descripcion = esc_attr(wp_trim_words(get_the_excerpt(), 25, '...'));
        $url = get_permalink();
        
        if (has_post_thumbnail($post->ID)) {
            $imagen_id = get_post_thumbnail_id($post->ID);
            $imagen_array = wp_get_attachment_image_src($imagen_id, 'full');
            $imagen = $imagen_array[0];
        } else {
            $imagen = $imagen_defecto;
        }
    } else {
        $titulo = get_bloginfo('name') . ' – ' . get_bloginfo('description');
        $descripcion = 'Proyecto colectivo de experimentación y docencia sobre shibari contemporáneo en Barcelona.';
        $url = home_url('/');
        $imagen = $imagen_defecto;
    }

    echo '<!-- Etiquetas Open Graph & Twitter -->' . "\n";
    echo '<meta property="og:locale" content="es_ES" />' . "\n";
    echo '<meta property="og:type" content="website" />' . "\n";
    echo '<meta property="og:title" content="' . $titulo . '" />' . "\n";
    echo '<meta property="og:description" content="' . $descripcion . '" />' . "\n";
    echo '<meta property="og:url" content="' . esc_url($url) . '" />' . "\n";
    echo '<meta property="og:site_name" content="' . get_bloginfo('name') . '" />' . "\n";
    echo '<meta property="og:image" content="' . esc_url($imagen) . '" />' . "\n";
    echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
    echo '<meta name="twitter:title" content="' . $titulo . '" />' . "\n";
    echo '<meta name="twitter:description" content="' . $descripcion . '" />' . "\n";
    echo '<meta name="twitter:image" content="' . esc_url($imagen) . '" />' . "\n";
  }
);

add_action(
  'wp_head', 
  function () {
    if (is_front_page() || is_home()) {
        $meta_desc = "Proyecto colectivo de experimentación y docencia sobre shibari contemporáneo en Barcelona. Cursos, talleres y sesiones privadas.";
    } elseif (is_singular()) {
        global $post;
        $meta_desc = esc_attr(wp_trim_words($post->post_excerpt ? $post->post_excerpt : $post->post_content, 25, '...'));
    }
    
    if (!empty($meta_desc)) {
        echo '<meta name="description" content="' . $meta_desc . '" />' . "\n";
    }
  }
);