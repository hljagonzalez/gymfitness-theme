<?php
/**
 * Plantilla para el listado de entradas del blog.
 *
 * @package gymfitness
 */

get_header();
?>

<main id="primary" class="site-main site-main--archivo-blog">
	<?php if ( have_posts() ) : ?>
		<?php get_template_part( 'template-parts/blog' ); ?>
		<?php get_template_part( 'template-parts/blog-paginacion' ); ?>
	<?php else : ?>
		<p class="contenedor archivo-blog__vacio"><?php esc_html_e( 'No hay entradas para mostrar.', 'gymfitness' ); ?></p>
	<?php endif; ?>
</main>

<?php
get_footer();
