<?php
/**
 * The template used for displaying page content
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php the_title(); ?>
    <?php the_content(); ?>
</article><!-- #post-## -->
