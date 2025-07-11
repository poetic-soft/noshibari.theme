<?php

add_shortcode(
  'qa',
  function ($atts) {

    global $post;

    $posttitle = $post ? $post->post_title : 'no post';

    $sendtext = isset($atts['send']) ?
      $atts['send']
      :
      'Preguntar';
    $errortext = isset($atts['error']) ? 
      $atts['error'] 
      :  
      'Error enviando mensaje, vuelve a intentarlo, por favor.';
    $oktext = isset($atts['ok']) ? 
      $atts['ok'] 
      :  
      'Gracias!. Hemos recibido tu pregunta. Pronto la puedes consultar en la sección de -Preguntas y Respuestas-';

    return '<div class="shortcode qa">
      <form>
        <div class="field">
          <textarea 
            id="qa"
            name="qa" 
            data-message-error="'. $errortext . '"  
            data-message-ok="'. $oktext . '" 
            required 
          ></textarea>
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
          ' . $sendtext . '
        </button>
      </form>
      <div class="message"></div>
    </div>';
  }
);