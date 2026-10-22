<?php
/**
 * Title: Child feature card
 * Slug: shadcn-child/feature-card
 * Categories: shadcn
 * Description: A shadcn-inspired feature card using native WordPress blocks.
 * Viewport width: 600
 */
?>
<!-- wp:group {"className":"shadcn-child-card","layout":{"type":"constrained"}} -->
<div class="wp-block-group shadcn-child-card">
	<!-- wp:heading {"level":3,"fontSize":"lg"} -->
	<h3 class="wp-block-heading has-lg-font-size">Feature title</h3>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"textColor":"muted-foreground","fontSize":"sm"} -->
	<p class="has-muted-foreground-color has-text-color has-sm-font-size">A concise description that can be edited in the Site Editor.</p>
	<!-- /wp:paragraph -->

	<!-- wp:buttons -->
	<div class="wp-block-buttons">
		<!-- wp:button {"className":"shadcn-child-button"} -->
		<div class="wp-block-button shadcn-child-button"><a class="wp-block-button__link wp-element-button">Learn more</a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
