<?php while (have_posts()) : ?>
    <?php the_post(); ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <h1 class="texto-centrado texto-primario"><?php the_title(); ?></h1>
        <?php if ( function_exists( 'get_field' ) && get_field( 'ubicacion' ) ) : ?>
            <div class="contacto-ubicacion">
                <?php the_field( 'ubicacion' ); ?>
            </div>
        <?php endif; ?>
        <?php the_post_thumbnail('full', array('class' => 'imagen-destacada')); ?>
        <?php the_content(); ?>
    </article>

<?php endwhile;
