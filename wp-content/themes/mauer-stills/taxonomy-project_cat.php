<?php
/*
* The template for displaying Project category archive
*/
get_header(); ?>

<div class="section-main-content">

	<div class="container-fluid mauer-container-fluid-with-max-width mauer-container-fluid-with-max-width-wider">
		<div class="row">
			<div class="col-xs-12">

				<div class="row">
					<div class="col-xs-12 col-md-9 col-lg-7">
						<?php if (function_exists('get_field') && get_field('portfolio_welcome_phrase', 'option')): ?>
							<h1 class="portfolio-welcome-phrase no-auto-hyphenation"><?php echo get_field('portfolio_welcome_phrase', 'option'); ?></h1>
						<?php endif ?>
					</div>
				</div>

				<?php mauer_stills_display_portfolio_categories_menu(); ?>

				<?php get_template_part("content", "projects_" . mauer_stills_get_portfolio_layout_setting()); ?>

			</div>
		</div>
	</div>

</div><!-- /.section-main-content -->



<?php get_footer();?>