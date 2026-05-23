<?php
/**
 * Partial: página de inicio (orquesta las secciones).
 *
 * @package gymfitness
 */

while ( have_posts() ) :
	the_post();
	?>

	<article id="post-<?php the_ID(); ?>" <?php post_class( 'inicio' ); ?>>
		<?php get_template_part( 'template-parts/inicio/bienvenida' ); ?>
	</article>

	<?php get_template_part( 'template-parts/inicio/areas' ); ?>
	<?php get_template_part( 'template-parts/inicio/clases' ); ?>
	<?php get_template_part( 'template-parts/inicio/instructores' ); ?>
	<?php get_template_part( 'template-parts/inicio/testimonios' ); ?>

	<?php
endwhile;
