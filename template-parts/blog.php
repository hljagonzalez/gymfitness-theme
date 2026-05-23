<?php
/**
 * Partial: listado de entradas del blog.
 *
 * @package gymfitness
 */
?>

<section class="listado-blog contenedor" aria-label="<?php esc_attr_e( 'Entradas del blog', 'gymfitness' ); ?>">
	<ul class="listado-blog__grid">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<li class="listado-blog__item">
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'listado-blog__card' ); ?>>
					<?php
					$categories = get_the_category();
					if ( ! empty( $categories ) ) :
						?>
						<ul class="listado-blog__categorias">
							<?php foreach ( $categories as $category ) : ?>
								<li>
									<a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>">
										<?php echo esc_html( $category->name ); ?>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>

					<?php if ( has_post_thumbnail() ) : ?>
						<a class="listado-blog__media" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
							<?php
							the_post_thumbnail(
								'large',
								array(
									'class' => 'listado-blog__imagen',
									'alt'   => esc_attr( get_the_title() ),
								)
							);
							?>
						</a>
					<?php else : ?>
						<div class="listado-blog__media listado-blog__media--placeholder" aria-hidden="true"></div>
					<?php endif; ?>

					<div class="listado-blog__body">
						<h2 class="listado-blog__titulo">
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h2>
						<div class="listado-blog__meta">
							<p class="listado-blog__autor">
								<span class="listado-blog__etiqueta"><?php esc_html_e( 'Por:', 'gymfitness' ); ?></span>
								<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
									<?php the_author(); ?>
								</a>
							</p>
							<p class="listado-blog__fecha">
								<span class="listado-blog__etiqueta"><?php esc_html_e( 'Fecha:', 'gymfitness' ); ?></span>
								<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>">
									<?php echo esc_html( get_the_date() ); ?>
								</time>
							</p>
						</div>
					</div>
				</article>
			</li>
			<?php
		endwhile;
		?>
	</ul>
</section>
