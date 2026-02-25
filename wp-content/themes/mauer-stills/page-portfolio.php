<?php
/*
* Template Name: Portfolio
* Description: Template for displaying a page with the projects archive.
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

				<?php
					$paged = 1;
					if (get_query_var('paged')) {$paged = get_query_var('paged');}
					if (get_query_var('page')) {$paged = get_query_var('page');}
					$args = array(
						'post_type' => 'project',
						'paged' => $paged,
					);
					$args['posts_per_page'] = mauer_stills_get_project_posts_per_page();

					global $wp_query;
					query_posts($args);

					get_template_part("content", "projects_" . mauer_stills_get_portfolio_layout_setting());
					wp_reset_query();
				?>

			</div>
		</div>
	</div>

</div><!-- /.section-main-content -->

<?php get_footer();?>