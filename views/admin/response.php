<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
?>

<div id="responses">
    <input type="hidden" id="nbr-response-item" name="nbr-response-item" value="<?= sizeOf($question_responses) ?>" />

    <?php
        foreach($question_responses as $key => $response) {
    ?>
        <div class="response-item">
            <div class="section-title-block">
                <h2><?= __("Réponse", "qcm-word"); ?> <?= $key + 1 ?></h2>
            </div>

            <div class="section-body-block">
                <div class="qcmword-response-item">
                    <div class="width-full">
                        <div class='meta-box-item-title'>
                            <label class="width-full"><b><?= __("Type du champ", "qcm-word"); ?></b></label>
                        </div>

                        <div class='meta-box-itel-content'>
                            <select id='qcmword-response-type-<?= $key + 1 ?>' name='qcmword-response-type-<?= $key + 1 ?>' class="width-firth">
                                <?php
                                    if ($response['qcmword-response-type'] == 'checkbox') {
                                ?>
                                        <option value='checkbox' selected ><?= __("Case à cocher", "qcm-word"); ?></option>
                                        <option value='text'><?= __("Texte", "qcm-word"); ?></option>
                                        <option value='textarea'><?= __("Texte Multilignes", "qcm-word"); ?></option>
                                <?php
                                    } else if($response['qcmword-response-type'] == 'text') {
                                ?>
                                        <option value='checkbox'  ><?= __("Case à cocher", "qcm-word"); ?></option>
                                        <option value='text' selected ><?= __("Texte", "qcm-word"); ?></option>
                                        <option value='textarea'><?= __("Texte Multilignes", "qcm-word"); ?></option>
                                <?php
                                    } else if($response['qcmword-response-type'] == 'textarea') {
                                ?>
                                        <option value='checkbox'  ><?= __("Case à cocher", "qcm-word"); ?></option>
                                        <option value='text'><?= __("Texte", "qcm-word"); ?></option>
                                        <option value='textarea' selected ><?= __("Texte Multilignes", "qcm-word"); ?></option>
                                <?php
                                    } else {
                                ?>
                                        <option value='checkbox' ><?= __("Case à cocher", "qcm-word"); ?></option>
                                        <option value='text'><?= __("Texte", "qcm-word"); ?></option>
                                        <option value='textarea'><?= __("Texte Multilignes", "qcm-word"); ?></option>
                                <?php
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="width-full">          
                        <div class='meta-box-item-title width-full'>
                            <label class="width-firth"><b><?= __("Texte", "qcm-word"); ?></b></label>
                        </div>
                        <div class='meta-box-itel-content width-full'>
                            <textarea id='qcmword-question-response-<?= $key + 1 ?>' name='qcmword-question-response-<?= $key + 1 ?>' class="width-full" rows='5'><?= $response['qcmword-question-response'] ?></textarea>
                        </div>
                    </div>

                    <div class="width-full">
                        <div class='meta-box-item-title' class="width-full">
                            <label class="width-full"><b><?= __("InfoBulle", "qcm-word"); ?></b></label>
                        </div>
                        <div class='meta-box-itel-content' class="width-full">
                            <textarea id='qcmword-response-infobulle-<?= $key + 1 ?>' name='qcmword-response-infobulle-<?= $key + 1 ?>' class="width-full" rows='5'><?= $response['qcmword-response-infobulle'] ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
        }
    ?>
</div>

<div class="qcmword-add-item mt-15">
    <span id="btn-new-response" class="button button-primary button-large">+ <?= __("Ajouter une réponse", "qcm-word"); ?></span>
</div>

<br/>