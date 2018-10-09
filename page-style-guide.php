<?php
/**
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package CARES
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>

				<?php
					// If comments are open or we have at least one comment, load up the comment template
					// if ( comments_open() || '0' != get_comments_number() ) :
					// 	comments_template();
					// endif;
				?>

			<?php endwhile; // end of the loop. ?>
			<div class="content-container">

				<h2>Blocks</h2>
				<h3>Half-width</h3>
				<div class="content-row">
					<div class="half-block">
						<p>One or two meetings of this kind had taken place, without affording Elinor any chance of engaging Lucy in private, when Sir John called at the cottage one morning, to beg, in the name of charity, that they would all dine with Lady Middleton that day, as he was obliged to attend the club at Exeter, and she would otherwise be quite alone, except her mother and the two Miss Steeles. Elinor, who foresaw a fairer opening for the point she had in view, in such a party as this was likely to be, more at liberty among themselves under the tranquil and well-bred direction of Lady Middleton than when her husband united them together in one noisy purpose, immediately accepted the invitation; Margaret, with her mother’s permission, was equally compliant, and Marianne, though always unwilling to join any of their parties, was persuaded by her mother, who could not bear to have her seclude herself from any chance of amusement, to go likewise.</p>
					</div>
					<div class="half-block">
						<p>One or two meetings of this kind had taken place, without affording Elinor any chance of engaging Lucy in private, when Sir John called at the cottage one morning, to beg, in the name of charity, that they would all dine with Lady Middleton that day, as he was obliged to attend the club at Exeter, and she would otherwise be quite alone, except her mother and the two Miss Steeles. Elinor, who foresaw a fairer opening for the point she had in view, in such a party as this was likely to be, more at liberty among themselves under the tranquil and well-bred direction of Lady Middleton than when her husband united them together in one noisy purpose, immediately accepted the invitation; Margaret, with her mother’s permission, was equally compliant, and Marianne, though always unwilling to join any of their parties, was persuaded by her mother, who could not bear to have her seclude herself from any chance of amusement, to go likewise.</p>
					</div>
				</div>

				<h3>Third-width</h3>
				<div class="content-row">
					<div class="third-block">
						<p>One or two meetings of this kind had taken place, without affording Elinor any chance of engaging Lucy in private, when Sir John called at the cottage one morning, to beg, in the name of charity, that they would all dine with Lady Middleton that day, as he was obliged to attend the club at Exeter, and she would otherwise be quite alone, except her mother and the two Miss Steeles. Elinor, who foresaw a fairer opening for the point she had in view, in such a party as this was likely to be, more at liberty among themselves under the tranquil and well-bred direction of Lady Middleton than when her husband united them together in one noisy purpose, immediately accepted the invitation.</p>
					</div>
					<div class="third-block">
						<p>One or two meetings of this kind had taken place, without affording Elinor any chance of engaging Lucy in private, when Sir John called at the cottage one morning, to beg, in the name of charity, that they would all dine with Lady Middleton that day, as he was obliged to attend the club at Exeter, and she would otherwise be quite alone, except her mother and the two Miss Steeles. Elinor, who foresaw a fairer opening for the point she had in view, in such a party as this was likely to be, more at liberty among themselves under the tranquil and well-bred direction of Lady Middleton than when her husband united them together in one noisy purpose, immediately accepted the invitation.</p>
					</div>
					<div class="third-block">
						<p>One or two meetings of this kind had taken place, without affording Elinor any chance of engaging Lucy in private, when Sir John called at the cottage one morning, to beg, in the name of charity, that they would all dine with Lady Middleton that day, as he was obliged to attend the club at Exeter, and she would otherwise be quite alone, except her mother and the two Miss Steeles. Elinor, who foresaw a fairer opening for the point she had in view, in such a party as this was likely to be, more at liberty among themselves under the tranquil and well-bred direction of Lady Middleton than when her husband united them together in one noisy purpose, immediately accepted the invitation.</p>
					</div>
				</div>

				<h3>Quarter-width</h3>
				<div class="content-row">
					<div class="quarter-block">
						<p>One or two meetings of this kind had taken place, without affording Elinor any chance of engaging Lucy in private, when Sir John called at the cottage one morning, to beg, in the name of charity, that they would all dine with Lady Middleton that day, as he was obliged to attend the club at Exeter, and she would otherwise be quite alone, except her mother and the two Miss Steeles.</p>
					</div>
					<div class="quarter-block">
						<p>One or two meetings of this kind had taken place, without affording Elinor any chance of engaging Lucy in private, when Sir John called at the cottage one morning, to beg, in the name of charity, that they would all dine with Lady Middleton that day, as he was obliged to attend the club at Exeter, and she would otherwise be quite alone, except her mother and the two Miss Steeles.</p>
					</div>
					<div class="quarter-block">
						<p>One or two meetings of this kind had taken place, without affording Elinor any chance of engaging Lucy in private, when Sir John called at the cottage one morning, to beg, in the name of charity, that they would all dine with Lady Middleton that day, as he was obliged to attend the club at Exeter, and she would otherwise be quite alone, except her mother and the two Miss Steeles.</p>
					</div>
					<div class="quarter-block">
						<p>One or two meetings of this kind had taken place, without affording Elinor any chance of engaging Lucy in private, when Sir John called at the cottage one morning, to beg, in the name of charity, that they would all dine with Lady Middleton that day, as he was obliged to attend the club at Exeter, and she would otherwise be quite alone, except her mother and the two Miss Steeles.</p>
					</div>
				</div>

			</div><!-- .content-container -->


		</main><!-- #main -->
	</div><!-- #primary -->

<?php //get_sidebar(); ?>
<?php get_footer(); ?>
