<?php
/**
 * Widgets personalizados del tema Gym Fitness.
 *
 * @package gymfitness
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Widget: listado de clases en el aside (imagen, título, día y horario).
 */
class GymFitness_Clases_Aside_Widget extends WP_Widget {

	/**
	 * Registra el widget en WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'gymfitness_clases_aside',
			__( 'Gym Fitness - Clases en aside', 'gymfitness' ),
			array(
				'description' => __( 'Muestra clases con imagen, título y horario. Elige cuántas mostrar.', 'gymfitness' ),
			)
		);
	}

	/**
	 * Salida en el front.
	 *
	 * @param array $args     Argumentos del área de widgets.
	 * @param array $instance Valores guardados.
	 */
	public function widget( $args, $instance ) {
		$titulo_widget = ! empty( $instance['titulo'] ) ? $instance['titulo'] : '';
		$cantidad      = ! empty( $instance['cantidad'] ) ? (int) $instance['cantidad'] : 3;
		$cantidad = max( 1, min( 12, $cantidad ) );

		$query_args = array(
			'post_type'      => 'clases',
			'posts_per_page' => $cantidad,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'post_status'    => 'publish',
			'no_found_rows'  => true,
		);

		// En la ficha de una clase, no repetir la que se está viendo.
		if ( is_singular( 'clases' ) ) {
			$clase_actual = get_queried_object_id();
			if ( $clase_actual ) {
				$query_args['post__not_in'] = array( (int) $clase_actual );
			}
		}

		$clases = new WP_Query( $query_args );

		if ( ! $clases->have_posts() ) {
			return;
		}

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( $titulo_widget ) {
			echo $args['before_title'] . esc_html( $titulo_widget ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		echo '<ul class="gymfitness-widget-clases">';

		while ( $clases->have_posts() ) {
			$clases->the_post();
			$clase_id      = get_the_ID();
			$horario_clase = function_exists( 'gymfitness_clase_horario_line' )
				? gymfitness_clase_horario_line( $clase_id )
				: '';
			?>
			<li class="gymfitness-widget-clases__item">
				<a class="gymfitness-widget-clases__enlace" href="<?php the_permalink(); ?>">
					<?php if ( has_post_thumbnail( $clase_id ) ) : ?>
						<?php
						echo get_the_post_thumbnail(
							$clase_id,
							'thumbnail',
							array(
								'class' => 'gymfitness-widget-clases__imagen',
								'alt'   => esc_attr( get_the_title( $clase_id ) ),
							)
						);
						?>
					<?php else : ?>
						<span class="gymfitness-widget-clases__imagen gymfitness-widget-clases__imagen--placeholder" aria-hidden="true"></span>
					<?php endif; ?>

					<div class="gymfitness-widget-clases__info">
						<span class="gymfitness-widget-clases__titulo"><?php the_title(); ?></span>
						<?php if ( $horario_clase ) : ?>
							<span class="gymfitness-widget-clases__horario"><?php echo esc_html( $horario_clase ); ?></span>
						<?php endif; ?>
					</div>
				</a>
			</li>
			<?php
		}

		echo '</ul>';

		wp_reset_postdata();

		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Formulario en el admin.
	 *
	 * @param array $instance Valores actuales.
	 */
	public function form( $instance ) {
		$titulo   = ! empty( $instance['titulo'] ) ? $instance['titulo'] : __( 'Otras clases', 'gymfitness' );
		$cantidad = ! empty( $instance['cantidad'] ) ? (int) $instance['cantidad'] : 3;
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'titulo' ) ); ?>">
				<?php esc_html_e( 'Título del widget:', 'gymfitness' ); ?>
			</label>
			<input
				class="widefat"
				id="<?php echo esc_attr( $this->get_field_id( 'titulo' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'titulo' ) ); ?>"
				type="text"
				value="<?php echo esc_attr( $titulo ); ?>"
			>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'cantidad' ) ); ?>">
				<?php esc_html_e( 'Número de clases a mostrar:', 'gymfitness' ); ?>
			</label>
			<input
				class="tiny-text"
				id="<?php echo esc_attr( $this->get_field_id( 'cantidad' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'cantidad' ) ); ?>"
				type="number"
				min="1"
				max="12"
				step="1"
				value="<?php echo esc_attr( $cantidad ); ?>"
			>
		</p>
		<?php
	}

	/**
	 * Guarda la configuración del widget.
	 *
	 * @param array $new_instance Valores nuevos.
	 * @param array $old_instance Valores anteriores.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();

		$instance['titulo']   = sanitize_text_field( $new_instance['titulo'] ?? '' );
		$instance['cantidad'] = absint( $new_instance['cantidad'] ?? 3 );

		if ( $instance['cantidad'] < 1 ) {
			$instance['cantidad'] = 1;
		}
		if ( $instance['cantidad'] > 12 ) {
			$instance['cantidad'] = 12;
		}

		return $instance;
	}
}
