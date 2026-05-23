<?php

/**
 * Funciones principales del tema Gym Fitness.
 *
 * @package gymfitness
 */

if (! function_exists('gymfitness_setup')) {
    /**
     * Configuracion inicial del tema.
     */
    function gymfitness_setup()
    {
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script'));

        // Tarjetas de clases/blog: hasta 1200px de lado largo, sin recorte duro.
        add_image_size('gymfitness-card', 1200, 1200, false);

        register_nav_menus(
            array(
                'menu-principal' => __('Menu principal', 'gymfitness'),
            )
        );

        // Editor clásico de widgets (lista arrastrable). Los widgets del tema aparecen ahí.
        remove_theme_support('widgets-block-editor');
    }
}


add_action('after_setup_theme', 'gymfitness_setup');

/**
 * Sincroniza grupos ACF con JSON en el tema (acf-json).
 */
function gymfitness_acf_json_save_path() {
    return get_template_directory() . '/acf-json';
}
add_filter( 'acf/settings/save_json', 'gymfitness_acf_json_save_path' );

function gymfitness_acf_json_load_paths( $paths ) {
    $paths[] = get_template_directory() . '/acf-json';
    return $paths;
}
add_filter( 'acf/settings/load_json', 'gymfitness_acf_json_load_paths' );

/**
 * Carga widgets personalizados del tema.
 */
require_once get_template_directory() . '/widgets.php';

/**
 * Registro de áreas de widgets y widgets personalizados.
 */
function gymfitness_widgets_init()
{
    register_sidebar(
        array(
            'name'          => __('Detalle de clase (aside)', 'gymfitness'),
            'id'            => 'aside-clase',
            'description'   => __('Widgets del aside. Se muestran con get_sidebar() y sidebar.php.', 'gymfitness'),
            'before_widget' => '<section id="%1$s" class="widget clase-aside__widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h3 class="widget-title clase-aside__widget-title">',
            'after_title'   => '</h3>',
        )
    );

    register_widget( 'GymFitness_Clases_Aside_Widget' );
}
add_action('widgets_init', 'gymfitness_widgets_init');

if (! function_exists('gymfitness_content_has_wpcf7')) {
    /**
     * Comprueba si el contenido incluye un formulario Contact Form 7.
     */
    function gymfitness_content_has_wpcf7(string $content): bool
    {
        if ($content === '') {
            return false;
        }

        if (has_shortcode($content, 'contact-form-7')) {
            return true;
        }

        return str_contains($content, 'wp:contact-form-7/');
    }
}

if (! function_exists('gymfitness_post_has_contact_form_7')) {
    /**
     * Comprueba si una entrada/página muestra CF7 en su contenido.
     */
    function gymfitness_post_has_contact_form_7(int $post_id = 0): bool
    {
        if (! function_exists('wpcf7_contact_form')) {
            return false;
        }

        $post_id = $post_id > 0 ? $post_id : (int) get_queried_object_id();
        if ($post_id <= 0) {
            return false;
        }

        $post = get_post($post_id);
        if (! $post instanceof WP_Post) {
            return false;
        }

        return gymfitness_content_has_wpcf7($post->post_content);
    }
}

if (! function_exists('gymfitness_needs_url_normalize_script')) {
    /**
     * URL normalize: comentarios con campo web o formularios CF7 con [url].
     */
    function gymfitness_needs_url_normalize_script(): bool
    {
        if (is_singular() && comments_open() && post_type_supports(get_post_type(), 'comments')) {
            return true;
        }

        if (is_singular() || is_page()) {
            return gymfitness_post_has_contact_form_7();
        }

        return false;
    }
}

if (! function_exists('gymfitness_enqueue_theme_script')) {
    /**
     * Encola un script del tema con versión por fecha de modificación del archivo.
     */
    function gymfitness_enqueue_theme_script(string $handle, string $relative_path, array $deps = array()): void
    {
        $path = get_template_directory() . $relative_path;

        wp_enqueue_script(
            $handle,
            get_template_directory_uri() . $relative_path,
            $deps,
            file_exists($path) ? (string) filemtime($path) : wp_get_theme()->get('Version'),
            true
        );
    }
}

/**
 * Estilos y scripts del tema (solo donde hacen falta).
 */
function gymfitness_enqueue_assets(): void
{
    wp_enqueue_style(
        'normalize',
        'https://necolas.github.io/normalize.css/8.0.1/normalize.css',
        array(),
        '8.0.1'
    );
    wp_enqueue_style(
        'gymfitness-style',
        get_stylesheet_uri(),
        array('normalize'),
        wp_get_theme()->get('Version')
    );

    gymfitness_enqueue_theme_script('gymfitness-menu-hamburguesa', '/js/menu-hamburguesa.js');

    if (is_front_page()) {
        gymfitness_enqueue_theme_script('gymfitness-nav-sticky', '/js/nav-sticky.js');

        wp_enqueue_script(
            'animejs',
            'https://cdnjs.cloudflare.com/ajax/libs/animejs/2.0.2/anime.min.js',
            array(),
            '2.0.2',
            true
        );
        gymfitness_enqueue_theme_script('gymfitness-hero-ml9', '/js/hero-ml9.js', array('animejs'));
        gymfitness_enqueue_theme_script('gymfitness-testimonios-carrusel', '/js/testimonios-carrusel.js');
    }

    if (is_page_template('page-galeria.php')) {
        gymfitness_enqueue_theme_script('gymfitness-lightbox', '/js/lightbox.js');
    }

    if (gymfitness_needs_url_normalize_script()) {
        gymfitness_enqueue_theme_script('gymfitness-url-normalize', '/js/url-normalize.js');
    }
}
add_action('wp_enqueue_scripts', 'gymfitness_enqueue_assets');

if (! function_exists('gymfitness_normalize_url_value')) {
    /**
     * Añade https:// si el usuario escribe solo el dominio (p. ej. www.empresa.es).
     */
    function gymfitness_normalize_url_value(string $url): string
    {
        $url = trim($url);
        if ($url === '') {
            return '';
        }
        if (! preg_match('/^https?:\/\//i', $url)) {
            $url = 'https://' . ltrim($url, '/');
        }
        return $url;
    }
}

if (! function_exists('gymfitness_wpcf7_is_url')) {
    /**
     * Acepta URLs de CF7 sin protocolo tras normalizarlas.
     *
     * @param bool   $result Resultado original.
     * @param string $text   URL enviada.
     */
    function gymfitness_wpcf7_is_url(bool $result, string $text): bool
    {
        if ($result) {
            return true;
        }

        $normalized = gymfitness_normalize_url_value($text);
        if ($normalized === $text) {
            return false;
        }

        $scheme = wp_parse_url($normalized, PHP_URL_SCHEME);

        return (bool) $scheme && in_array($scheme, wp_allowed_protocols(), true);
    }
}
add_filter('wpcf7_is_url', 'gymfitness_wpcf7_is_url', 10, 2);

if (! function_exists('gymfitness_preprocess_comment_url')) {
    /**
     * Normaliza la URL del formulario de comentarios antes de guardar.
     *
     * @param array<string, mixed> $comment_data Datos del comentario.
     * @return array<string, mixed>
     */
    function gymfitness_preprocess_comment_url(array $comment_data): array
    {
        if (! empty($comment_data['comment_author_url'])) {
            $comment_data['comment_author_url'] = gymfitness_normalize_url_value(
                (string) $comment_data['comment_author_url']
            );
        }

        return $comment_data;
    }
}
add_filter('preprocess_comment', 'gymfitness_preprocess_comment_url');

if (! function_exists('gymfitness_comment_form_field_url')) {
    /**
     * Placeholder claro en el campo web de comentarios.
     */
    function gymfitness_comment_form_field_url(string $field): string
    {
        return str_replace(
            '<input',
            '<input placeholder="https://www.tuempresa.es"',
            $field
        );
    }
}
add_filter('comment_form_field_url', 'gymfitness_comment_form_field_url');

if (! function_exists('gymfitness_enqueue_comment_reply')) {
    /**
     * Script de respuestas anidadas en comentarios.
     */
    function gymfitness_enqueue_comment_reply(): void
    {
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }
}
add_action('wp_enqueue_scripts', 'gymfitness_enqueue_comment_reply');

if (! function_exists('gymfitness_comment_form_defaults')) {
    /**
     * Textos y clases del formulario de comentarios.
     *
     * @param array<string, mixed> $defaults Valores por defecto de comment_form().
     * @return array<string, mixed>
     */
    function gymfitness_comment_form_defaults(array $defaults): array
    {
        return array_merge(
            $defaults,
            array(
                'title_reply'          => __('Deja un comentario', 'gymfitness'),
                'title_reply_to'       => __('Responder a %s', 'gymfitness'),
                'cancel_reply_link'    => __('Cancelar respuesta', 'gymfitness'),
                'label_submit'         => __('Publicar comentario', 'gymfitness'),
                'comment_notes_before' => '',
                'comment_notes_after'  => '',
                'class_form'           => 'entrada-comentarios__form comment-form',
                'class_submit'         => 'entrada-comentarios__enviar',
                'submit_button'        => '<button type="submit" name="%1$s" id="%2$s" class="%3$s entrada-comentarios__enviar">%4$s</button>',
            )
        );
    }
}
add_filter('comment_form_defaults', 'gymfitness_comment_form_defaults');

if (! function_exists('gymfitness_pre_comment_approved')) {
    /**
     * Los comentarios de visitantes (sin sesión) quedan pendientes de revisión.
     *
     * @param int|string $approved     Estado calculado por WordPress.
     * @param array      $commentdata Datos del comentario.
     * @return int|string
     */
    function gymfitness_pre_comment_approved($approved, array $commentdata)
    {
        if (is_user_logged_in()) {
            return $approved;
        }

        if (in_array($approved, array('spam', 'trash'), true)) {
            return $approved;
        }

        return 0;
    }
}
add_filter('pre_comment_approved', 'gymfitness_pre_comment_approved', 10, 2);

if (! function_exists('gymfitness_comment_post_redirect')) {
    /**
     * Añade parámetros y ancla para mostrar confirmación tras enviar un comentario.
     *
     * @param string     $location URL de redirección.
     * @param WP_Comment $comment  Comentario recién creado.
     */
    function gymfitness_comment_post_redirect(string $location, WP_Comment $comment): string
    {
        $hash_pos = strpos($location, '#');
        if (false !== $hash_pos) {
            $location = substr($location, 0, $hash_pos);
        }

        $estado = '1' === (string) $comment->comment_approved ? 'publicado' : 'moderacion';

        $location = add_query_arg(
            array(
                'comentario_enviado' => '1',
                'comentario_estado'  => $estado,
            ),
            $location
        );

        return $location;
    }
}
add_filter('comment_post_redirect', 'gymfitness_comment_post_redirect', 10, 2);

if (! function_exists('gymfitness_comments_array_only_approved')) {
    /**
     * Solo muestra comentarios aprobados en la entrada (sin vista previa de pendientes).
     *
     * @param WP_Comment[] $comments Comentarios del listado.
     * @return WP_Comment[]
     */
    function gymfitness_comments_array_only_approved(array $comments): array
    {
        return array_values(
            array_filter(
                $comments,
                static function ($comment) {
                    return $comment instanceof WP_Comment && '1' === (string) $comment->comment_approved;
                }
            )
        );
    }
}
add_filter('comments_array', 'gymfitness_comments_array_only_approved');

if (! function_exists('gymfitness_enqueue_comment_feedback_script')) {
    /**
     * Centra el aviso de confirmación tras enviar un comentario.
     */
    function gymfitness_enqueue_comment_feedback_script(): void
    {
        if (! is_singular() || ! isset($_GET['comentario_enviado'])) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            return;
        }

        wp_enqueue_script(
            'gymfitness-comentario-feedback',
            get_template_directory_uri() . '/js/comentario-feedback.js',
            array(),
            filemtime(get_template_directory() . '/js/comentario-feedback.js'),
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'gymfitness_enqueue_comment_feedback_script');

if (! function_exists('gymfitness_render_comment_feedback_notice')) {
    /**
     * Muestra un aviso visible tras enviar el formulario de comentarios.
     */
    function gymfitness_render_comment_feedback_notice(): void
    {
        $enviado_por_param = isset($_GET['comentario_enviado']) && '1' === $_GET['comentario_enviado']; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $enviado_por_wp    = isset($_GET['unapproved'], $_GET['moderation-hash']); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

        if (! $enviado_por_param && ! $enviado_por_wp) {
            return;
        }

        $estado = '';
        if ($enviado_por_param && isset($_GET['comentario_estado'])) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            $estado = sanitize_key(wp_unslash($_GET['comentario_estado'])); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        }
        if ($estado === '' && $enviado_por_wp) {
            $estado = 'moderacion';
        }
        if ($estado === '') {
            $estado = 'moderacion';
        }

        if ($estado === 'publicado') {
            $mensaje = __('¡Gracias! Tu comentario se ha publicado correctamente.', 'gymfitness');
        } else {
            $mensaje = __('¡Gracias! Hemos recibido tu comentario. Se mostrará en cuanto lo revisemos.', 'gymfitness');
        }

        printf(
            '<div class="entrada-comentarios__aviso entrada-comentarios__aviso--fijo entrada-comentarios__aviso--%1$s" id="comentario-enviado" role="status" tabindex="-1">%2$s</div>',
            esc_attr($estado),
            esc_html($mensaje)
        );
    }
}

if (! function_exists('gymfitness_clase_listado_thumbnail_html')) {
    /**
     * HTML de imagen para listados de clases: destacada o primera imagen del contenido.
     */
    function gymfitness_clase_listado_thumbnail_html($post_id)
    {
        $size = 'gymfitness-card';
        $alt  = esc_attr(get_the_title($post_id));
        $img_attr = array(
            'class' => 'listado-clases__imagen',
            'alt'   => $alt,
        );
        $fallback_sizes = array('gymfitness-card', 'large', 'medium_large', 'full');
        $sizes_attr     = '(min-width: 64em) 25vw, (min-width: 48em) 50vw, 100vw';

        $thumbnail_id = (int) get_post_thumbnail_id($post_id);
        if ($thumbnail_id > 0) {
            $html = gymfitness_get_attachment_image_html(
                $thumbnail_id,
                $size,
                $img_attr,
                $fallback_sizes,
                $sizes_attr
            );
            if ($html !== '') {
                return $html;
            }
        }

        $content = get_post_field('post_content', $post_id);
        if (! is_string($content) || $content === '') {
            return '';
        }

        if (function_exists('parse_blocks')) {
            $stack = parse_blocks($content);
            while (! empty($stack)) {
                $block = array_shift($stack);
                if (! empty($block['innerBlocks'])) {
                    foreach ($block['innerBlocks'] as $inner) {
                        $stack[] = $inner;
                    }
                }
                $name  = isset($block['blockName']) ? $block['blockName'] : '';
                $attrs = isset($block['attrs']) ? $block['attrs'] : array();

                if ($name === 'core/image' && ! empty($attrs['id'])) {
                    $html = gymfitness_get_attachment_image_html(
                        (int) $attrs['id'],
                        $size,
                        $img_attr,
                        $fallback_sizes,
                        $sizes_attr
                    );
                    if ($html !== '') {
                        return $html;
                    }
                }
                if ($name === 'core/media-text' && ! empty($attrs['mediaId'])) {
                    $html = gymfitness_get_attachment_image_html(
                        (int) $attrs['mediaId'],
                        $size,
                        $img_attr,
                        $fallback_sizes,
                        $sizes_attr
                    );
                    if ($html !== '') {
                        return $html;
                    }
                }
                if ($name === 'core/cover' && ! empty($attrs['id'])) {
                    $html = gymfitness_get_attachment_image_html(
                        (int) $attrs['id'],
                        $size,
                        $img_attr,
                        $fallback_sizes,
                        $sizes_attr
                    );
                    if ($html !== '') {
                        return $html;
                    }
                }
            }
        }

        if (preg_match_all('/\bwp-image-(\d+)\b/', $content, $matches) && ! empty($matches[1][0])) {
            $id = (int) $matches[1][0];
            if ($id > 0) {
                $html = gymfitness_get_attachment_image_html(
                    $id,
                    $size,
                    $img_attr,
                    $fallback_sizes,
                    $sizes_attr
                );
                if ($html !== '') {
                    return $html;
                }
            }
        }

        return '';
    }
}

if ( ! function_exists( 'gymfitness_get_inicio_hero_fields' ) ) {
	/**
	 * Campos ACF del hero de la página de inicio.
	 *
	 * @return array<string, mixed>|null
	 */
	function gymfitness_get_inicio_hero_fields() {
		if ( ! is_front_page() || ! function_exists( 'get_field' ) ) {
			return null;
		}

		$page_id = (int) get_queried_object_id();
		if ( ! $page_id ) {
			$page_id = (int) get_option( 'page_on_front' );
		}
		if ( ! $page_id ) {
			return null;
		}

		$imagen = get_field( 'imagen_hero', $page_id );
		$url    = '';
		$alt    = '';

		if ( is_array( $imagen ) && ! empty( $imagen['url'] ) ) {
			$url = $imagen['url'];
			$alt = isset( $imagen['alt'] ) ? (string) $imagen['alt'] : '';
		} elseif ( is_numeric( $imagen ) ) {
			$id  = (int) $imagen;
			$url = wp_get_attachment_image_url( $id, 'full' ) ?: '';
			$alt = (string) get_post_meta( $id, '_wp_attachment_image_alt', true );
		}

		return array(
			'page_id'    => $page_id,
			'imagen_url' => $url,
			'imagen_alt' => $alt,
			'heading'    => get_field( 'heading_hero', $page_id ),
			'texto'      => get_field( 'texto_hero', $page_id ),
		);
	}
}

if ( ! function_exists( 'gymfitness_get_inicio_page_id' ) ) {
	/**
	 * ID de la página de inicio (ajuste Lectura o slug inicio).
	 */
	function gymfitness_get_inicio_page_id(): int {
		$page_id = (int) get_option( 'page_on_front' );

		if ( $page_id > 0 ) {
			return $page_id;
		}

		$page = get_page_by_path( 'inicio' );

		return $page instanceof WP_Post ? (int) $page->ID : 0;
	}
}

if ( ! function_exists( 'gymfitness_resolve_attachment_id_from_acf_image' ) ) {
	/**
	 * Obtiene el ID de adjunto desde cualquier formato habitual de ACF.
	 *
	 * @param mixed $imagen Valor del campo imagen.
	 */
	function gymfitness_resolve_attachment_id_from_acf_image( $imagen ): int {
		if ( is_array( $imagen ) ) {
			if ( ! empty( $imagen['ID'] ) ) {
				return (int) $imagen['ID'];
			}
			if ( ! empty( $imagen['id'] ) ) {
				return (int) $imagen['id'];
			}
		}

		if ( is_numeric( $imagen ) ) {
			return (int) $imagen;
		}

		if ( is_string( $imagen ) && ctype_digit( $imagen ) ) {
			return (int) $imagen;
		}

		return 0;
	}
}

if ( ! function_exists( 'gymfitness_get_attachment_image_html' ) ) {
	/**
	 * Imagen de adjunto con reserva si falta el tamaño "large" en disco.
	 *
	 * @param int               $attachment_id ID del adjunto.
	 * @param string|int[]      $size          Tamaño de imagen.
	 * @param array<string,string> $attr            Atributos del img.
	 * @param string[]|null        $fallback_sizes  Tamaños a probar (sin usar "medium" en cards).
	 * @param string               $sizes_attr      Atributo sizes para srcset responsive.
	 */
	function gymfitness_get_attachment_image_html( int $attachment_id, $size = 'large', array $attr = array(), ?array $fallback_sizes = null, string $sizes_attr = '' ): string {
		if ( $attachment_id <= 0 ) {
			return '';
		}

		if ( $sizes_attr !== '' ) {
			$attr['sizes'] = $sizes_attr;
		}

		$sizes = $fallback_sizes ?? array( $size, 'large', 'medium_large', 'full' );
		$sizes = array_values( array_unique( array_merge( array( $size ), $sizes ) ) );

		foreach ( $sizes as $try_size ) {
			$html = wp_get_attachment_image( $attachment_id, $try_size, false, $attr );
			if ( $html ) {
				return $html;
			}
		}

		$url = wp_get_attachment_url( $attachment_id );
		if ( ! $url ) {
			return '';
		}

		$class = isset( $attr['class'] ) ? $attr['class'] : '';
		$alt   = isset( $attr['alt'] ) ? $attr['alt'] : '';

		return sprintf(
			'<img src="%1$s" alt="%2$s" class="%3$s" loading="lazy" decoding="async" />',
			esc_url( $url ),
			esc_attr( $alt ),
			esc_attr( $class )
		);
	}
}

if ( ! function_exists( 'gymfitness_get_inicio_area_data' ) ) {
	/**
	 * Lee un área ACF de inicio (grupo + meta suelta por si el grupo no devuelve imagen).
	 *
	 * @param string $nombre_area area_1 … area_4.
	 * @param int    $page_id     ID de la página de inicio.
	 * @return array<string, mixed>
	 */
	function gymfitness_get_inicio_area_data( string $nombre_area, int $page_id ): array {
		$area = array();

		if ( function_exists( 'get_field' ) && $page_id > 0 ) {
			$field = get_field( $nombre_area, $page_id );
			if ( is_array( $field ) ) {
				$area = $field;
			}
		}

		if ( $page_id > 0 ) {
			if ( empty( $area['texto'] ) ) {
				$texto_meta = get_post_meta( $page_id, $nombre_area . '_texto', true );
				if ( is_string( $texto_meta ) && '' !== trim( $texto_meta ) ) {
					$area['texto'] = $texto_meta;
				}
			}

			if ( empty( $area['imagen'] ) ) {
				$imagen_meta = get_post_meta( $page_id, $nombre_area . '_imagen', true );
				if ( $imagen_meta !== '' && $imagen_meta !== false ) {
					$area['imagen'] = $imagen_meta;
				}
			}
		}

		return $area;
	}
}

if ( ! function_exists( 'gymfitness_get_inicio_areas' ) ) {
	/**
	 * Áreas del gimnasio configuradas en la página de inicio.
	 *
	 * @return array<int, array<string, mixed>>
	 */
	function gymfitness_get_inicio_areas(): array {
		$page_id = gymfitness_get_inicio_page_id();
		$areas   = array();

		if ( $page_id <= 0 ) {
			return $areas;
		}

		foreach ( array( 'area_1', 'area_2', 'area_3', 'area_4' ) as $nombre_area ) {
			$area = gymfitness_get_inicio_area_data( $nombre_area, $page_id );
			if ( gymfitness_inicio_area_tiene_contenido( $area ) ) {
				$areas[] = $area;
			}
		}

		return $areas;
	}
}

if ( ! function_exists( 'gymfitness_inicio_area_tiene_contenido' ) ) {
	/**
	 * Comprueba si un grupo ACF de área (imagen + texto) tiene datos.
	 *
	 * @param mixed $area Valor del campo group.
	 */
	function gymfitness_inicio_area_tiene_contenido( $area ) {
		if ( ! is_array( $area ) ) {
			return false;
		}

		$tiene_imagen = gymfitness_resolve_attachment_id_from_acf_image( $area['imagen'] ?? null ) > 0;
		if ( ! $tiene_imagen && ! empty( $area['imagen'] ) && is_array( $area['imagen'] ) && ! empty( $area['imagen']['url'] ) ) {
			$tiene_imagen = true;
		}
		if ( ! $tiene_imagen && is_string( $area['imagen'] ?? null ) && filter_var( $area['imagen'], FILTER_VALIDATE_URL ) ) {
			$tiene_imagen = true;
		}

		$tiene_texto = isset( $area['texto'] ) && '' !== trim( (string) $area['texto'] );

		return $tiene_imagen || $tiene_texto;
	}
}

if ( ! function_exists( 'gymfitness_inicio_area_imagen_html' ) ) {
	/**
	 * HTML de imagen para las áreas de la página de inicio (ID o array ACF).
	 *
	 * @param mixed  $imagen Valor del subcampo imagen.
	 * @param string $alt    Texto alternativo.
	 */
	function gymfitness_inicio_area_imagen_html( $imagen, $alt = '' ) {
		$id  = gymfitness_resolve_attachment_id_from_acf_image( $imagen );
		$url = '';

		if ( is_array( $imagen ) && ! empty( $imagen['url'] ) ) {
			$url = (string) $imagen['url'];
		} elseif ( is_string( $imagen ) && '' !== $imagen && ! ctype_digit( $imagen ) ) {
			$url = $imagen;
		}

		if ( $id > 0 ) {
			if ( '' === $alt ) {
				$alt = (string) get_post_meta( $id, '_wp_attachment_image_alt', true );
			}

			$html = gymfitness_get_attachment_image_html(
				$id,
				'large',
				array(
					'class' => 'inicio-areas__imagen',
					'alt'   => $alt,
				)
			);

			if ( $html ) {
				return $html;
			}
		}

		if ( '' !== $url ) {
			return sprintf(
				'<img src="%1$s" alt="%2$s" class="inicio-areas__imagen" loading="lazy" decoding="async" />',
				esc_url( $url ),
				esc_attr( $alt )
			);
		}

		return '';
	}
}

if ( ! function_exists( 'gymfitness_clase_campo_tiene_valor' ) ) {
	/**
	 * Comprueba si un valor de campo ACF no está vacío.
	 *
	 * @param mixed $value Valor del campo.
	 */
	function gymfitness_clase_campo_tiene_valor( $value ) {
		if ( is_array( $value ) ) {
			return ! empty( $value );
		}

		return null !== $value && '' !== $value && false !== $value;
	}
}

if ( ! function_exists( 'gymfitness_format_clase_dia_valor' ) ) {
	/**
	 * Formatea el valor del campo de día(s).
	 *
	 * @param mixed $value Valor del campo.
	 */
	function gymfitness_format_clase_dia_valor( $value ) {
		if ( is_array( $value ) ) {
			$items = array();

			foreach ( $value as $item ) {
				if ( is_scalar( $item ) && '' !== (string) $item ) {
					$items[] = (string) $item;
				}
			}

			return esc_html( implode( ', ', $items ) );
		}

		return esc_html( (string) $value );
	}
}

if ( ! function_exists( 'gymfitness_format_clase_hora' ) ) {
	/**
	 * Formatea hora ACF (time_picker) como "8:00 am".
	 *
	 * @param mixed $value Valor del campo.
	 */
	function gymfitness_format_clase_hora( $value ) {
		if ( ! gymfitness_clase_campo_tiene_valor( $value ) ) {
			return '';
		}

		$value = trim( (string) $value );
		$ts    = strtotime( $value );

		if ( $ts ) {
			return strtolower( wp_date( 'g:i a', $ts ) );
		}

		return esc_html( $value );
	}
}

if ( ! function_exists( 'gymfitness_clase_horario_line' ) ) {
	/**
	 * Línea de horario para el listado: "Todos los días - 8:00 am a 10:00 am".
	 *
	 * @param int $post_id ID de la clase.
	 */
	function gymfitness_clase_horario_line( $post_id ) {
		if ( ! function_exists( 'get_field' ) ) {
			return '';
		}

		$dia    = '';
		$inicio = '';
		$fin    = '';
		$usados = array();

		$nombres_dia = array( 'dias', 'dia', 'dia_clase', 'dias_clase' );
		$nombres_ini = array( 'hora_inicio', 'hora_de_inicio', 'horario_inicio', 'inicio' );
		$nombres_fin = array( 'hora_fin', 'hora_de_fin', 'horario_fin', 'fin' );

		foreach ( $nombres_dia as $nombre ) {
			if ( $dia ) {
				break;
			}
			$valor = get_field( $nombre, $post_id );
			if ( gymfitness_clase_campo_tiene_valor( $valor ) ) {
				$dia      = gymfitness_format_clase_dia_valor( $valor );
				$usados[] = $nombre;
			}
		}

		foreach ( $nombres_ini as $nombre ) {
			if ( $inicio ) {
				break;
			}
			$valor = get_field( $nombre, $post_id );
			if ( gymfitness_clase_campo_tiene_valor( $valor ) ) {
				$inicio   = gymfitness_format_clase_hora( $valor );
				$usados[] = $nombre;
			}
		}

		foreach ( $nombres_fin as $nombre ) {
			if ( $fin ) {
				break;
			}
			$valor = get_field( $nombre, $post_id );
			if ( gymfitness_clase_campo_tiene_valor( $valor ) ) {
				$fin      = gymfitness_format_clase_hora( $valor );
				$usados[] = $nombre;
			}
		}

		if ( function_exists( 'get_field_objects' ) ) {
			$fields       = get_field_objects( $post_id, false );
			$time_pickers = array();

			if ( $fields ) {
				foreach ( $fields as $name => $field ) {
					if ( in_array( $name, $usados, true ) ) {
						continue;
					}

					$label = isset( $field['label'] ) ? $field['label'] : '';
					$type  = isset( $field['type'] ) ? $field['type'] : '';
					$value = isset( $field['value'] ) ? $field['value'] : null;

					if ( ! gymfitness_clase_campo_tiene_valor( $value ) ) {
						continue;
					}

					$haystack = strtolower( $name . ' ' . $label );

					if ( ! $dia && preg_match( '/\b(dia|dias|día|días|day)\b/u', $haystack ) ) {
						$dia      = gymfitness_format_clase_dia_valor( $value );
						$usados[] = $name;
						continue;
					}

					if ( 'time_picker' === $type || preg_match( '/hora|time|horario/u', $haystack ) ) {
						if ( preg_match( '/inicio|desde|start|comienzo/u', $haystack ) && ! $inicio ) {
							$inicio   = gymfitness_format_clase_hora( $value );
							$usados[] = $name;
						} elseif ( preg_match( '/fin|hasta|end/u', $haystack ) && ! $fin ) {
							$fin      = gymfitness_format_clase_hora( $value );
							$usados[] = $name;
						} else {
							$time_pickers[] = array(
								'name'  => $name,
								'value' => $value,
							);
						}
					}
				}
			}

			if ( ! $inicio && ! empty( $time_pickers ) ) {
				$inicio   = gymfitness_format_clase_hora( $time_pickers[0]['value'] );
				$usados[] = $time_pickers[0]['name'];

				if ( ! $fin && isset( $time_pickers[1] ) ) {
					$fin      = gymfitness_format_clase_hora( $time_pickers[1]['value'] );
					$usados[] = $time_pickers[1]['name'];
				}
			}
		}

		$horas = '';

		if ( $inicio && $fin ) {
			$horas = sprintf(
				/* translators: 1: hora inicio, 2: hora fin */
				__( '%1$s a %2$s', 'gymfitness' ),
				$inicio,
				$fin
			);
		} elseif ( $inicio ) {
			$horas = $inicio;
		} elseif ( $fin ) {
			$horas = $fin;
		}

		if ( $dia && $horas ) {
			return $dia . ' - ' . $horas;
		}

		return $dia ? $dia : $horas;
	}
}

if ( ! function_exists( 'gymfitness_instructor_especialidades' ) ) {
	/**
	 * Etiquetas legibles de las especialidades ACF de un instructor.
	 *
	 * @param int $post_id ID del instructor.
	 * @return string[]
	 */
	function gymfitness_instructor_especialidades( $post_id ) {
		$values = get_field( 'especialidad', $post_id );
		if ( empty( $values ) ) {
			return array();
		}
		if ( ! is_array( $values ) ) {
			$values = array( $values );
		}

		$field   = get_field_object( 'especialidad', $post_id );
		$choices = ( is_array( $field ) && ! empty( $field['choices'] ) ) ? $field['choices'] : array();
		$labels  = array();

		foreach ( $values as $slug ) {
			if ( ! is_string( $slug ) || '' === $slug ) {
				continue;
			}
			$labels[] = isset( $choices[ $slug ] ) ? $choices[ $slug ] : $slug;
		}

		return $labels;
	}
}
