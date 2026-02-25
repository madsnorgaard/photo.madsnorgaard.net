<div class="container-fluid mauer-container-fluid-with-max-width">

	<div class="mauer-more-projects-wrapper">

		<h4 class="mauer-special-h4"><?php echo get_field('other_projects_title', 'option');?></h4>

		<?php
			global $wp_query;
			$paged = 1;
			if (isset($_GET['other_projects_paged'])) {$paged = $_GET['other_projects_paged'];}

			if (get_field('other_projects_posts_per_page', 'option')) {$posts_per_page = get_field('other_projects_posts_per_page', 'option');}
			else {$posts_per_page = 9;}

			$args = array(
				'post_type' => 'project',
				'post__not_in' => array($wp_query->post->ID),
				'posts_per_page' => $posts_per_page,
				'this_is_other_projects_query' => 1,
				'this_is_other_projects_query_requested_from' => $wp_query->post->ID,
				'this_is_other_projects_query_paged' => $paged,
				'paged' => $paged,
			);

			query_posts($args);

			if (get_field('other_projects_layout', 'option')) {$other_projects_layout = get_field('other_projects_layout', 'option');}
			else {$other_projects_layout = 'grid_3cols';}

			get_template_part("content", "projects_" . $other_projects_layout);

			wp_reset_query();
		?>

	</div>

</div>