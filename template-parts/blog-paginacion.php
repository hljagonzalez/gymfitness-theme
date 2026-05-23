<?php
/**
 * Paginación del listado de entradas del blog.
 *
 * @package gymfitness
 */

global $wp_query;

if (! $wp_query instanceof WP_Query || (int) $wp_query->max_num_pages <= 1) {
    return;
}
?>

<nav class="contenedor archivo-blog__paginacion" aria-label="<?php esc_attr_e( 'Paginación de entradas', 'gymfitness' ); ?>">
	<?php
	the_posts_pagination(
		array(
			'mid_size'  => 2,
			'prev_text' => __( 'Anterior', 'gymfitness' ),
			'next_text' => __( 'Siguiente', 'gymfitness' ),
		)
	);
	?>
</nav>
