<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
?>

<div id="primary" class="content-area qcmword-content-area primary">
    <main id="main" class="site-main qcmword-site-main site-main">
        <input type="hidden" id="theme" name="theme" value="<?= $oterm ?>"/>
        <input type="hidden" id="qcm_word_user_id" name="qcm_word_user_id" value="<?= $qcm_word_user_id ?>" />

        <div id="qcm-front-questions">
            <h1 class="qcmword-page-title"><?= $oterm ?></h1>
            
            <?php
                if (!empty($data)) {
            ?>
                <article class="qcmword-question post-1 post type-post status-publish format-standard hentry category-non-classe ast-article-single" 
                    id="post-<?= $d["post"]->ID ?>" 
                    data-question-id="<?= $d["post"]->ID ?>" 
                    data-question-text="<?= $d["post"]->post_title ?>"
                >
                    <div class="ast-post-format- ast-no-thumb single-layout-1">
                        <header class="qcmword-entry-header entry-header ">
                            <h3 class="qcmword-entry-title entry-title" itemprop="headline"><?= __("Inscription", "qcm-word"); ?></h3>
                        </header>

                        <div class="qcmword-form-inscription">
                            <div class="qcmword-form-row">
                                <label for="lastName" class="qcmword-form-label"><?= __("Nom", "qcm-word"); ?> <label class="requiered-label">*</label></label>
                                <input type="text" id="lastName" name="lastName" class="qcm-word-user-response qcmword-form-input" /> 
                            </div>

                            <div class="qcmword-form-row">
                                <label for="firstName" class="qcmword-form-label"><?= __("Prénom", "qcm-word"); ?> <label class="requiered-label">*</label></label>
                                <input type="text" id="firstName" name="firstName" class="qcm-word-user-response qcmword-form-input" /> 
                            </div>

                            <div class="qcmword-form-row">
                                <label for="phone" class="qcmword-form-label"><?= __("Tél", "qcm-word"); ?> <label class="requiered-label">*</label></label>
                                <input type="text" id="phone" name="phone" class="qcm-word-user-response qcmword-form-input" /> 
                            </div>

                            <div class="qcmword-form-row">
                                <label for="email" class="qcmword-form-label"><?= __("Adresse email", "qcm-word"); ?> <label class="requiered-label">*</label></label>
                                <input type="text" id="email" name="email" class="qcm-word-user-response qcmword-form-input" /> 
                            </div>

                            <div class="qcmword-form-row">
                                <label for="country" class="qcmword-form-label"><?= __("Pays", "qcm-word"); ?> <label class="requiered-label">*</label></label>
                                <input type="text" id="country" name="country" class="qcm-word-user-response qcmword-form-input" /> 
                            </div>

                            <div class="qcmword-form-row">
                                <label for="city" class="qcmword-form-label"><?= __("Ville", "qcm-word"); ?> <label class="requiered-label">*</label></label>
                                <input type="text" id="city" name="city" class="qcm-word-user-response qcmword-form-input" /> 
                            </div>
                        </div>
                    </div>	
                </article>

                <div class="col-xs-1 center-block qcmword-page-btns">
                    <div id="error-message" class="error-message hidden"></div>
                    <span class="btn btn-primary" id="qcmword-user-qcm-save"><?= __("Enregistrer", "qcm-word"); ?></span>
                </div>
            <?php 
                } else {
            ?>
                Empty!
            <?php } ?>
        </div>
    </main>
</div>