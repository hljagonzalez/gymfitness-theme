<?php
/**
 * Sidebar reutilizable para áreas de widgets registradas en functions.php.
 *
 * Uso:
 * get_sidebar(
 *     null,
 *     array(
 *         'id'         => 'aside-clase',
 *         'class'      => 'clase-aside',
 *         'aria_label' => __( 'Texto accesible', 'gymfitness' ),
 *     )
 * );
 *
 * @package gymfitness
 *
 * @var array $args Argumentos pasados desde get_sidebar().
 */

$sidebar_id = ! empty( $args['id'] ) ? $args['id'] : '';

if ( ! $sidebar_id || ! is_active_sidebar( $sidebar_id ) ) {
	return;
}

$sidebar_class = ! empty( $args['class'] ) ? $args['class'] : 'sidebar';
$aria_label    = ! empty( $args['aria_label'] )
	? $args['aria_label']
	: __( 'Barra lateral', 'gymfitness' );
?>

<aside class="<?php echo esc_attr( $sidebar_class ); ?>" role="complementary" aria-label="<?php echo esc_attr( $aria_label ); ?>">
	<?php dynamic_sidebar( $sidebar_id ); ?>
</aside>
