<?php
/**
 * Plantilla de archivo de autor del blog.
 *
 * @package gymfitness
 */

get_header();

$author_id = get_queried_object_id();
?>

<main id="primary" class="site-main site-main--archivo-blog">
	<div class="contenedor seccion archivo-blog__cabecera">
		<header>
			<h1 class="texto-centrado texto-primario">
				<?php echo esc_html( get_the_author_meta( 'display_name', $author_id ) ); ?>
			</h1>
			<?php
			$author_description = get_the_author_meta( 'description', $author_id );
			if ( $author_description ) :
				?>
				<div class="archivo-blog__descripcion">
					<?php echo wp_kses_post( wpautop( $author_description ) ); ?>
				</div>
			<?php endif; ?>
		</header>
	</div>

	<?php if ( have_posts() ) : ?>
		<?php get_template_part( 'template-parts/blog' ); ?>

		<?php get_template_part( 'template-parts/blog-paginacion' ); ?>
	<?php else : ?>
		<p class="contenedor archivo-blog__vacio"><?php esc_html_e( 'No hay entradas de este autor.', 'gymfitness' ); ?></p>
	<?php endif; ?>
</main>

<?php
get_footer();
