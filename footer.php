<footer class="site-footer">
    <div class="contenedor">
        <?php
        $args = array(
            'theme_location'       => 'menu-principal',
            'container'            => 'nav',
            'container_class'      => 'menu-principal',
            'container_aria_label' => __( 'Menu principal', 'gymfitness' ),
            'menu_class'           => 'menu menu-principal__lista',
            'fallback_cb'          => false,
        );
        wp_nav_menu( $args );
        ?>

        <p class="site-footer__legal">
            <?php
            printf(
                /* translators: 1: copyright year, 2: site name */
                esc_html__( '© %1$s %2$s. Todos los derechos reservados.', 'gymfitness' ),
                esc_html( date_i18n( 'Y' ) ),
                esc_html( get_bloginfo( 'name', 'display' ) )
            );
            ?>
        </p>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
