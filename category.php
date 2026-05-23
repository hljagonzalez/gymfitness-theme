<?php
/**
 * Plantilla de archivo de categorías del blog.
 *
 * @package gymfitness
 */

get_header();
?>

<main id="primary" class="site-main site-main--archivo-blog">
	<div class="contenedor seccion archivo-blog__cabecera">
		<header>
			<h1 class="texto-centrado texto-primario"><?php single_cat_title(); ?></h1>
			<?php if ( get_the_archive_description() ) : ?>
				<div class="archivo-blog__descripcion">
					<?php the_archive_description(); ?>
				</div>
			<?php endif; ?>
		</header>
	</div>

	<?php if ( have_posts() ) : ?>
		<?php get_template_part( 'template-parts/blog' ); ?>

		<?php get_template_part( 'template-parts/blog-paginacion' ); ?>
	<?php else : ?>
		<p class="contenedor archivo-blog__vacio"><?php esc_html_e( 'No hay entradas en esta categoría.', 'gymfitness' ); ?></p>
	<?php endif; ?>
</main>

<?php
get_footer();
