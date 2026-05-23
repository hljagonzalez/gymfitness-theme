<?php
/**
 * Template Name: Contenido centrado (sin sidebar)
 * Template Post Type: page
 */
get_header();
?>

<main id="primary" class="site-main site-main--contenido-centrado">
    <?php if ( have_posts() ) : ?>
        <?php get_template_part('template-parts/pagina'); ?>
    <?php else : ?>

        <p><?php esc_html_e( 'No hay contenido para mostrar.', 'gymfitness' ); ?></p>

    <?php endif; ?>
</main>

<?php get_footer(); ?>
