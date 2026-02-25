<?php
/*
* Single project template
*/
get_header(); ?>

<div class="section-main-content">
	<div class="container-fluid mauer-container-fluid-with-max-width">
		<?php get_template_part("content", "project_content"); ?>
	</div>
</div><!-- /.section-main-content -->

<?php get_footer();?>