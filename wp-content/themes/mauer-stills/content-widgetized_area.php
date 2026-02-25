 <?php
/*
 * Widgetized area template
 */
?>

<?php if (is_active_sidebar('widgetized-area-1') || is_active_sidebar('widgetized-area-2') || is_active_sidebar('widgetized-area-3') || is_active_sidebar('widgetized-area-4')): ?>

	<div class="sub-section sub-section-pre-footer">
		<div id="bottom-widgets">
			<div class="container-fluid mauer-container-fluid-with-max-width">
				<div class="row">

					<div class="col-xs-12 col-sm-6">
						<div class="row">

							<div class="col-md-6 widgetized-area-column">
								<?php if (is_active_sidebar('widgetized-area-1')): ?>
									<div class="widgetized-area">
										<?php if (function_exists('dynamic_sidebar')) {dynamic_sidebar('widgetized-area-1');}?>
									</div>
								<?php endif ?>
							</div>

							<div class="col-md-6 widgetized-area-column">
								<?php if (is_active_sidebar('widgetized-area-2')): ?>
									<div class="widgetized-area">
										<?php if (function_exists('dynamic_sidebar')) {dynamic_sidebar('widgetized-area-2');}?>
									</div>
								<?php endif ?>
							</div>

						</div>
					</div>

					<div class="col-xs-12 col-sm-6">
						<div class="row">

							<div class="col-md-6 widgetized-area-column">
								<?php if (is_active_sidebar('widgetized-area-3')): ?>
									<div class="widgetized-area">
										<?php if (function_exists('dynamic_sidebar')) {dynamic_sidebar('widgetized-area-3');}?>
									</div>
								<?php endif ?>
							</div>

							<div class="col-md-6 widgetized-area-column">
								<?php if (is_active_sidebar('widgetized-area-4')): ?>
									<div class="widgetized-area">
										<?php if (function_exists('dynamic_sidebar')) {dynamic_sidebar('widgetized-area-4');}?>
									</div>
								<?php endif ?>
							</div>

						</div>
					</div>


				</div>
			</div>
		</div>
	</div>

<?php endif; ?>