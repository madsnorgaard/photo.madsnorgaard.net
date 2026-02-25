<?php
/*
 * Template Name: Page (alternative)
 * Description: Alternative page template
*/
get_header(); ?>

<div class="section-main-content">
	<div class="container-fluid mauer-container-fluid-with-max-width">

	<?php if ( have_posts() ) : ?>

		<?php  while ( have_posts() ) : the_post(); ?>

			<div <?php post_class('entry-full'); ?>>

				<div class="row">
					<div class="col-sm-8 col-sm-offset-2 hidden-md hidden-lg">
						<h1 class="entry-title entry-title-without-meta alt-page-entry-title-for-smaller-resolutions"><?php the_title(); ?></h1><!-- mobile title -->
					</div>
					<div class="col-md-5 col-lg-5">
						<?php if (has_post_thumbnail()) : ?>
							<div class="entry-thumb">
								<div class="entry-thumb-inner">
									<?php the_post_thumbnail('mauer_stills_thumb_3', array('class' => 'mauer-stills-img-adaptable-height-and-width')); ?>
									<?php if (trim(str_replace("#half", "", get_post(get_post_thumbnail_id())->post_excerpt))) : ?>
										<div class="entry-thumb-caption"><?php echo trim(str_replace("#half", "", get_post(get_post_thumbnail_id())->post_excerpt)); ?></div>
									<?php endif; ?>
								</div>
							</div>
						<?php endif; ?>
					</div>
					<div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-1 col-lg-6">
						<h1 class="entry-title hidden-xs hidden-sm alt-page-entry-title-for-larger-resolutions"><?php the_title(); ?></h1> <!-- desktop title -->
						<?php $sharing_of_this_post_disabled = get_post_meta(get_the_ID(), 'sharing_disabled', true); ?>
						<?php if ( class_exists( 'Jetpack' ) && in_array( 'sharedaddy', Jetpack::get_active_modules()) && !$sharing_of_this_post_disabled ) {$sharedaddy_active = true;} else {$sharedaddy_active = false;} ?>
						<?php if ( $sharedaddy_active ) { $class_to_add = " special";} else {$class_to_add = "";} ?>
						<div class="entry-content<?php echo esc_html($class_to_add); ?>">
							<?php the_content(); ?>
						</div>
						<?php comments_template(); ?>
					</div>
				</div>

			</div><!-- /.entry-full -->

		<?php endwhile; ?>

	<?php else: ?>
		<div class="row">
			<div class="col-xs-12">
				<p class="text-center"><?php esc_html_e('Sorry, no posts matched your criteria', 'mauer-stills'); ?></p>
			</div>
		</div>
	<?php endif; ?>

	</div>
</div><!-- /.section-main-content -->

<?php get_footer();?>