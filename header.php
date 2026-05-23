<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php
$hero_inicio = function_exists( 'gymfitness_get_inicio_hero_fields' ) ? gymfitness_get_inicio_hero_fields() : null;
?>

<?php if ( is_front_page() && is_array( $hero_inicio ) ) : ?>
	<?php
	$hero_imagen_url = $hero_inicio['imagen_url'];
	$hero_imagen_alt = $hero_inicio['imagen_alt'];
	$hero_heading    = $hero_inicio['heading'];
	$hero_texto      = $hero_inicio['texto'];
	?>
	<header class="site-header site-header--inicio">
		<?php if ( $hero_imagen_url ) : ?>
			<div
				class="site-header__hero-fondo"
				role="img"
				aria-label="<?php echo esc_attr( $hero_imagen_alt ? $hero_imagen_alt : __( 'Imagen principal del gimnasio', 'gymfitness' ) ); ?>"
				style="background-image: url(<?php echo esc_url( $hero_imagen_url ); ?>);"
			></div>
		<?php endif; ?>
		<div class="site-header__hero-overlay" aria-hidden="true"></div>

		<div class="site-header__inicio-inner contenedor">
			<?php
			get_template_part(
				'template-parts/navegacion-barra',
				null,
				array( 'barra_class' => 'barra-navegacion site-header__barra' )
			);
			?>
			<div class="site-header__barra-spacer" aria-hidden="true"></div>

			<?php if ( $hero_heading || $hero_texto ) : ?>
				<div class="site-header__hero-contenido">
					<?php if ( $hero_heading ) : ?>
						<h1 class="site-header__hero-titulo ml9">
							<span class="text-wrapper">
								<span class="letters"><?php echo esc_html( $hero_heading ); ?></span>
							</span>
						</h1>
					<?php endif; ?>
					<?php if ( $hero_texto ) : ?>
						<div class="site-header__hero-texto">
							<?php echo wp_kses_post( wpautop( $hero_texto ) ); ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</header>
<?php else : ?>
	<header class="site-header">
		<div class="contenedor">
			<?php
			get_template_part(
				'template-parts/navegacion-barra',
				null,
				array( 'barra_class' => 'barra-navegacion' )
			);
			?>
		</div>
	</header>
<?php endif; ?>
