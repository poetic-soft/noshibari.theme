<?php

require_once(get_stylesheet_directory() . '/tools/firebase/vendor/autoload.php');

use Firebase\JWT\JWT;

add_shortcode(
  'jitsi',
  function ($atts) {

    global $post;

    $posttitle = $post ? $post->post_title : 'no post';

    $connecttext = isset($atts['connect']) ?
      $atts['connect']
      :
      'Conectar';
    $invalidtext = isset($atts['invalid']) ? 
      $atts['invalid'] 
      :  
      'Escribe una dirección de correo válida';
    $errortext = isset($atts['error']) ? 
      $atts['error'] 
      :  
      'Error conectando, vuelve a intentarlo, por favor.';
    $oktext = isset($atts['ok']) ? 
      $atts['ok'] 
      :  
      'Conectando...';

    return '<div class="shortcode jitsi">
      <form>
        <div class="field">
          <input 
            id="email"
            name="email"
            type="email"
            data-message-invalid="'. $invalidtext . '" 
            data-message-error="'. $errortext . '"  
            data-message-ok="'. $oktext . '" 
            required 
          />
          <input
            id="title"
            name="title"
            type="hidden"
            value="' . $posttitle . '"
          />
        </div>
        <button 
          class="wp-block-button"
          disabled
        >
          ' . $connecttext . '
        </button>
      </form>
      <div class="message"></div>
    </div>';
  }
);