
<div class="sidebar-section">
    <h3>Best Downloads</h3>
    <ul class="list-unstyled">
        <?php
        $best_downloads = adm_get_most_viewed_downloads( 5 );

        while ( $best_downloads->have_posts() ) {
            $best_downloads->the_post();
            ?>
            <li>
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </li>
            <?php
        }

        wp_reset_postdata();
        ?>
    </ul>
</div>
