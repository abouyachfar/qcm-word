<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
?>

<?php
        if (!empty($qcm_word_messages)) {
    ?>
            <div class="row">
    <?php
            foreach ($qcm_word_messages as $m) {
                if ($m["status"] == 1) {
    ?>
                    <div class="message-to-user et-<?= $m['type']?>"><?= $m['message'] ?></div>
    <?php
                }
            }
    ?>
            </div>
    <?php
        }
    ?>
        
    <?php
        if (!empty($qcm_word_banners)) {
    ?>
            <div class="row">
    <?php
            foreach ($qcm_word_banners as $b) {
                if ($b["status"] == 1) {
                    echo $b['html'];
                }
            }
    ?>
            </div>
    <?php
        }
    ?>

<div class='meta-box-item-title'>
    <label><?= __("Ordre", "qcm-word"); ?></label>
</div>

<div class='meta-box-itel-content'>
    <input type='number' id='qcmword-question-order' name='qcmword-question-order' value='<?= $order_value ?>' class='width-firth' />
</div>

<!-- 
<div class='meta-box-item-title'>
    <label><?= __("Question Directe ?", "qcm-word"); ?></label>
</div>


<div class='meta-box-itel-content'>
    <select id='qcmword-question-direct' name='qcmword-question-direct' class='width-firth'>
        <?php
            if ($direct_value == 'yes') {
        ?>
            <option value='no'><?= __("Non", "qcm-word"); ?></option>
            <option value='yes' selected ><?= __("Oui", "qcm-word"); ?></option>
        <?php
            } else {
        ?>
                <option value='no' selected ><?= __("Non", "qcm-word"); ?></option>
                <option value='yes'><?= __("Oui", "qcm-word"); ?></option>
        <?php
            }
        ?>
    </select>
</div>
-->

<div class='meta-box-item-title'>
    <label><?= __("Justificatif requis ?", "qcm-word"); ?></label>
</div>

<div class='meta-box-itel-content'>
    <select id='qcmword-question-justif-required' name='qcmword-question-justif-required' class='width-firth'>
        <?php
            if ($justificatif_value == 'yes') {
        ?>
            <option value='no'><?= __("Non", "qcm-word"); ?></option>
            <option value='yes' selected ><?= __("Oui", "qcm-word"); ?></option>
        <?php
            } else {
        ?>
                <option value='no' selected ><?= __("Non", "qcm-word"); ?></option>
                <option value='yes'><?= __("Oui", "qcm-word"); ?></option>
        <?php
            }
        ?>
    </select>
</div>

<input type='hidden' name='qcmword-response-nonce' value='<?= wp_create_nonce() ?>' />

<br/>