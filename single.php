<?php
/**
 * Plantilla individual de entradas del blog.
 *
 * @package gymfitness
 */

get_header();
?>

<main id="primary" class="site-main site-main--contenido-centrado site-main--entrada">
	<div class="contenedor seccion">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'entrada-single' ); ?>>
				<header class="entrada-single__cabecera">
					<h1 class="texto-centrado texto-primario"><?php the_title(); ?></h1>

					<?php
					$categories = get_the_category();
					if ( ! empty( $categories ) ) :
						?>
						<ul class="entrada-single__categorias">
							<?php foreach ( $categories as $category ) : ?>
								<li>
									<a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>">
										<?php echo esc_html( $category->name ); ?>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>

					<div class="entrada-single__meta">
						<p class="entrada-single__autor">
							<span class="entrada-single__etiqueta"><?php esc_html_e( 'Por:', 'gymfitness' ); ?></span>
							<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
								<?php the_author(); ?>
							</a>
						</p>
						<p class="entrada-single__fecha">
							<span class="entrada-single__etiqueta"><?php esc_html_e( 'Fecha:', 'gymfitness' ); ?></span>
							<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>">
								<?php echo esc_html( get_the_date() ); ?>
							</time>
						</p>
					</div>
				</header>

				<?php if ( has_post_thumbnail() ) : ?>
					<?php the_post_thumbnail( 'full', array( 'class' => 'imagen-destacada' ) ); ?>
				<?php endif; ?>

				<?php if ( get_the_content() ) : ?>
					<div class="entrada-single__contenido">
						<?php the_content(); ?>
					</div>
				<?php endif; ?>

				<?php
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
				?>
			</article>
		<?php endwhile; ?>
	</div>
</main>

<?php
get_footer();
