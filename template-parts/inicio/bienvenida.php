<?php
/**
 * Partial: cabecera de bienvenida (página de inicio).
 *
 * @package gymfitness
 */

$encabezado = function_exists( 'get_field' ) ? get_field( 'encabezado_bienvenida' ) : '';
$texto      = function_exists( 'get_field' ) ? get_field( 'texto_bienvenida' ) : '';
?>

<header class="inicio__cabecera">
	<?php if ( $encabezado ) : ?>
		<h1 class="inicio__encabezado texto-centrado texto-primario"><?php echo esc_html( $encabezado ); ?></h1>
	<?php else : ?>
		<h1 class="texto-centrado texto-primario"><?php the_title(); ?></h1>
	<?php endif; ?>

	<?php if ( $texto ) : ?>
		<p class="inicio__texto"><?php echo esc_html( $texto ); ?></p>
	<?php endif; ?>
</header>
