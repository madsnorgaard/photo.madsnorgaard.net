<?php
/*
 * The Footer
 */
?>

	<?php if (is_singular('project') && function_exists('get_field') && get_field('show_other_projects', 'option')): ?>
		<div class="section section-other-projects">
			<?php get_template_part("content", "other_projects"); ?>
		</div>
	<?php endif ?>


	<div id="footer">
		<?php if (get_post_type() == 'post' || is_search()) {get_template_part("content", "widgetized_area");} ?>

		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-12">
					<div class="footer-content">
						<div class="footer-copyright"><?php echo wp_kses_post(mauer_stills_copyright_text()); ?></div>
					</div>
				</div>
			</div>
		</div>

	</div>

	<div class="search-popup">
		<div class="container-fluid mauer-container-fluid-with-max-width">
			<div class="row">
				<div class="col-sm-6 col-sm-offset-3">
					<?php get_search_form(); ?>
				</div>
			</div>
		</div>
		<a href="#" class="mauer-close search-popup-closer"></a>
	</div>

	<?php wp_footer(); ?>

</body>
</html>