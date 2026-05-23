<?php
/**
 * Barra de navegación (logo + menú hamburguesa / menú principal).
 *
 * @package gymfitness
 *
 * @var array $args Argumentos de get_template_part().
 */

$barra_class = ! empty( $args['barra_class'] ) ? $args['barra_class'] : 'barra-navegacion';

$menu_args = array(
	'theme_location'       => 'menu-principal',
	'container'            => 'nav',
	'container_class'      => 'menu-principal',
	'container_id'         => 'menu-principal-panel',
	'container_aria_label' => __( 'Menu principal', 'gymfitness' ),
	'menu_class'           => 'menu menu-principal__lista',
	'fallback_cb'          => false,
);
?>

<div class="<?php echo esc_attr( $barra_class ); ?>" data-barra-navegacion>
	<a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
		<img src="<?php echo esc_url( get_template_directory_uri() . '/img/logo.svg' ); ?>" alt="<?php bloginfo( 'name' ); ?>">
	</a>

	<button
		type="button"
		class="menu-hamburguesa"
		aria-expanded="false"
		aria-controls="menu-principal-panel"
		aria-label="<?php esc_attr_e( 'Abrir menú', 'gymfitness' ); ?>"
	>
		<span class="menu-hamburguesa__caja" aria-hidden="true">
			<span class="menu-hamburguesa__linea"></span>
			<span class="menu-hamburguesa__linea"></span>
			<span class="menu-hamburguesa__linea"></span>
		</span>
		<span class="screen-reader-text"><?php esc_html_e( 'Abrir menú', 'gymfitness' ); ?></span>
	</button>

	<div class="menu-principal-overlay" aria-hidden="true" hidden></div>

	<?php wp_nav_menu( $menu_args ); ?>
</div>
