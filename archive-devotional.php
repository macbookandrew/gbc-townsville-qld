<?php
/**
 * The template for displaying Archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each specific one. For example, Twenty Thirteen
 * already has tag.php for Tag archives, category.php for Category archives,
 * and author.php for Author archives.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

		<?php if ( have_posts() ) : ?>
			<header class="archive-header">
				<h1 class="archive-title">Devotionals</h1>
			</header><!-- .archive-header -->

            <?php // calendar of posts
            $args = array (
                'post_type'              => array( 'devotional' ),
                'nopaging'               => true,
                'posts_per_page'         => '30',
                'order'                  => 'ASC',
                'orderby'                => 'date',
            );

            // The Query
            $devotionals_calendar = new WP_Query( $args );

            // The Loop
            if ( $devotionals_calendar->have_posts() ) {
                echo '<ol class="devotionals-calendar entry-header">';
                while ( $devotionals_calendar->have_posts() ) {
                    $devotionals_calendar->the_post();
                    echo '<li><a href="' . esc_url( get_permalink() ) . '">' . get_post_time( 'M j' ) . '</li>';
                }
                echo '</ol>';
            } else {
                // no posts found
            }

            // Restore original Post Data
            wp_reset_postdata();
            ?>

			<?php /* The loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content', get_post_format() ); ?>
			<?php endwhile; ?>

			<?php twentythirteen_paging_nav(); ?>

		<?php else : ?>
			<?php get_template_part( 'content', 'none' ); ?>
		<?php endif; ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
