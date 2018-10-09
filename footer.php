<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package CARES
 */
?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
				<ul id="sidebar">
					<?php dynamic_sidebar( 'footer-1' ); ?>
				</ul>
			<?php endif; ?>
			<!-- <a href="<?php echo esc_url( __( 'http://wordpress.org/', 'cares' ) ); ?>"><?php printf( __( 'Proudly powered by %s', 'cares' ), 'WordPress' ); ?></a>
			<span class="sep"> | </span>
			<?php printf( __( 'Theme: %1$s by %2$s.', 'cares' ), 'CARES', '<a href="http://underscores.me/" rel="designer">Underscores.me</a>' ); ?> -->

		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>
<script src="<?php echo get_stylesheet_directory_uri() ?>/js/picturefill.min.js" async></script>
</body>
</html>
