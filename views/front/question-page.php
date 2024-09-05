<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
?>

<div id="primary" class="content-area primary">
	<main id="main" class="site-main">
		<article class="post-1 post type-post status-publish format-standard hentry category-non-classe ast-article-single" id="post-<?= $post->ID ?>">
			<div class="ast-post-format- ast-no-thumb single-layout-1">
				<header class="entry-header ">
					<h1 class="entry-title" itemprop="headline"><?= __("Question", "qcm-word"); ?>: <?= $post->post_title ?></h1>
				</header>
				<div class="entry-content clear" ast-blocks-layout="true" itemprop="text">
					<p><?= $post->post_content ?></p>
					<hr/>
					<br/>
				</div>
				<div>
					<?php
						for($i=0; $i<=10; $i++) {
							$response_value = get_post_meta($post->ID, "qcmword-question-response-" . $i, true);
							if (!empty($response_value)) {
								?>
									<input type="checkbox put-left" id="response-<?= $i ?>" name="response-<?= $i ?>"/>
									<label for="response-<?= $i ?>" class="qcmword-form-label"><?= $response_value ?></label>
									<br/>
								<?php
							}
						}
					?>
				</div>
			</div>	
		</article>
	</main>
</div>