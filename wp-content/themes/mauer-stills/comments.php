<?php
/*
 * Comments and Comment form template
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
?>

<?php if ( post_password_required() ) {return;} ?>

<?php if (have_comments() || comments_open(get_the_ID())): ?>

	<div id="comments" class="comments-area">

		<?php if ( have_comments() ) : ?>
			<h2 class="comments-title">
				<?php echo esc_html__('Comments', 'mauer-stills') . " (<span>" . number_format_i18n( get_comments_number() ) . "</span>):"; ?>
			</h2>

			<ol class="commentlist">
				<?php wp_list_comments('callback=mauer_stills_comments'); ?>
			</ol><!-- .commentlist -->

			<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
			<nav id="comment-nav-below" class="navigation" role="navigation">
				<div class="nav-previous"><?php previous_comments_link( '&larr; ' .  esc_html__('Older Comments', 'mauer-stills' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments', 'mauer-stills' )  . ' &rarr;' ); ?></div>
			</nav>
			<?php endif; // check for comment navigation ?>

			<?php
			/* If there are no comments and comments are closed, let's leave a note.
			 * But we only want the note on posts and pages that had comments in the first place.
			 */
			if ( ! comments_open() && get_comments_number() ) : ?>
				<p class="nocomments"><?php esc_html_e( 'Comments are closed' , 'mauer-stills' ); ?>.</p>
			<?php endif; ?>

		<?php endif; // have_comments() ?>

		<?php comment_form(); ?>

	</div><!-- #comments .comments-area -->

<?php endif ?>