<?php
// Get the custom fields
$name_download_link = get_post_meta( get_the_ID(), 'name_download_link', true );
$version = get_post_meta( get_the_ID(), 'version', true );
$buy_now = get_post_meta( get_the_ID(), 'buy_now', true );
?>

<div class="download-details">
    <?php if ( $name_download_link ) : ?>
        <p><strong>Name Download Link:</strong> <?php echo esc_html( $name_download_link ); ?></p>
    <?php endif; ?>

    <?php if ( $version ) : ?>
        <p><strong>Version:</strong> <?php echo esc_html( $version ); ?></p>
    <?php endif; ?>

    <?php if ( $buy_now ) : ?>
        <p><strong>Buy Now:</strong> <a href="<?php echo esc_url( $buy_now ); ?>" target="_blank">Buy Now</a></p>
    <?php endif; ?>
</div>
