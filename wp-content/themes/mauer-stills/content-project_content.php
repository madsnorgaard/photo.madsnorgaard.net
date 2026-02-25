<?php
/*
 * Project content
 */
?>

<?php if ( have_posts() ) : ?>

	<?php  while ( have_posts() ) : the_post(); ?>

		<div <?php post_class('entry-full no-post-thumbnail-output'); ?>>

			<div class="row">
				<?php
					$display_meta = true;
					if (function_exists('get_field') && !get_field('show_portfolio_categories_in_single_projects', 'option')) {$display_meta = false;}
				?>
				<div class="col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2">
					<h1 class="entry-title<?php if(!$display_meta) {echo " entry-title-without-meta";} ?>"><?php the_title(); ?></h1>
					<?php if ($display_meta): ?>
						<div class="entry-meta">
							<span class="entry-cats"><?php echo mauer_stills_get_the_term_list(get_the_ID(), 'project_cat', ', '); ?></span>
						</div>
					<?php endif ?>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3">

					<div class="entry-content">
						<?php the_content(); ?>
					</div>

					<?php comments_template(); ?>

				</div>

			</div>

		</div><!-- /.entry-full -->

	<?php endwhile; ?>

<?php else: ?>
	<div class="row">
		<div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
			<p class="text-center"><?php esc_html_e('Sorry, no posts matched your criteria', 'mauer-stills'); ?></p>
		</div>
	</div>
<?php endif; ?>