<?php
// Ensure that this file is only accessed via WordPress.
defined( 'ABSPATH' ) || exit;

get_header();

// Start the loop
if ( have_posts() ) {
    while ( have_posts() ) {
        the_post();

        // Get the custom fields
        $name_download_link = get_post_meta( get_the_ID(), 'name_download_link', true );
        $version = get_post_meta( get_the_ID(), 'version', true );
        $buy_now = get_post_meta( get_the_ID(), 'buy_now', true );
        $hit_count = intval( get_post_meta( get_the_ID(), 'hit_count', true ) );

        // Increase hit count on each page load
        $hit_count++;
        update_post_meta( get_the_ID(), 'hit_count', $hit_count );

        ?>
        <div class="container mt-4">
            <div class="row">
                <div class="col-md-8">
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="mb-4">
                                <?php the_post_thumbnail( 'large', array( 'class' => 'img-fluid' ) ); ?>
                            </div>
                        <?php endif; ?>

                        <div class="entry-meta mb-3">
                            <?php the_category( ', ' ); ?>
                            <span class="meta-separator">|</span>
                            <?php the_tags(); ?>
                        </div>

                        <h1 class="entry-title"><?php the_title(); ?></h1>

                        <div class="entry-content">
                            <?php the_content(); ?>

                            <?php if ( $name_download_link ) : ?>
                                <p>
                                    <a href="<?php echo esc_url( $name_download_link ); ?>" class="btn btn-primary" target="_blank">
                                        Download <?php echo esc_html( $hit_count ); ?> Times
                                    </a>
                                </p>
                            <?php endif; ?>
                        </div>

                        <?php comments_template(); ?>
                    </article>
                </div>

                <div class="col-md-4">
                    <aside class="sidebar">
                        <div class="sidebar-section">
                            <h3>Recent Posts</h3>
                            <ul class="list-unstyled">
                                <?php
                                $recent_downloads_args = array(
                                    'post_type'      => 'download',
                                    'posts_per_page' => 5,
                                    'orderby'        => 'date',
                                    'order'          => 'desc',
                                );

                                $recent_downloads = new WP_Query( $recent_downloads_args );

                                while ( $recent_downloads->have_posts() ) {
                                    $recent_downloads->the_post();
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

                        <div class="sidebar-section">
                            <h3>Recent Comments</h3>
                            <ul class="list-unstyled">
                                <?php
                                $comments_args = array(
                                    'post_type' => 'download',
                                    'number'    => 5,
                                    'status'    => 'approve',
                                    'order'     => 'desc',
                                );

                                $comments = get_comments( $comments_args );

                                foreach ( $comments as $comment ) {
                                    ?>
                                    <li>
                                        <a href="<?php echo esc_url( get_comment_link( $comment ) ); ?>"><?php echo get_the_title( $comment->comment_post_ID ); ?></a>
                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
        <?php
    }
}



get_footer();
