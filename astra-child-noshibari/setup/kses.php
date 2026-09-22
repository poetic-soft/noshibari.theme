<?php
/**
 * En multisite KSES recorta <script> del contenido aunque el autor sea
 * administrador del sitio. Sin esto, el JSON-LD del ciclo /publicacion
 * llega como JSON suelto en el bloque Custom HTML.
 *
 * Solo deja pasar type="application/ld+json". Cualquier otro <script>
 * se elimina al guardar.
 */
add_filter('wp_kses_allowed_html', static function (array $tags, $context): array {
    if ($context !== 'post') {
        return $tags;
    }
    $tags['script'] = [
        'type' => true,
    ];
    return $tags;
}, 10, 2);