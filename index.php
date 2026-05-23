<?php
get_header();
?>

<main id="primary" class="site-main">
    <?php if ( have_posts() ) : ?>
        <?php while ( have_posts() ) : ?>
            <?php the_post(); ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <h1><?php the_title(); ?></h1>
                <?php the_content(); ?>
            </article>

        <?php endwhile; ?>
    <?php else : ?>

        <p><?php esc_html_e( 'No hay contenido para mostrar.', 'gymfitness' ); ?></p>

    <?php endif; ?>
</main>

<?php
get_footer();
