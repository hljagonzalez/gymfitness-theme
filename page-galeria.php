<?php
/**
 * Template Name: Galería de Imágenes
 * Description: Muestra la galería del editor (bloque Galería) y, más adelante, filtros dinámicos.
 *
 * @package gymfitness
 */

get_header();
?>

<main id="primary" class="site-main site-main--contenido-centrado site-main--galeria">
	<div class="contenedor seccion">
		<?php while ( have_posts() ) : ?>
			<?php the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<h1 class="texto-centrado texto-primario"><?php the_title(); ?></h1>

				<?php if ( get_the_content() ) : ?>
					<div class="galeria-contenido">
						<?php the_content(); ?>
					</div>
				<?php endif; ?>

				<?php
				/*
				 * Más adelante en el curso:
				 * - Filtros por categoría
				 * - Listado dinámico en .galeria-imagenes
				 * - Paginación
				 */
				?>
			</article>
		<?php endwhile; ?>
	</div>
</main>

<?php
get_footer();
