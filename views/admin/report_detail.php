<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
?>

<div class="wrap">
    <h1><?= __("Détails Rapport", "qcm-word") ?></h1>

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
    
    <div class="row">
        <div class="width-half put-left">
            <div class="card max-width-full">
                <div class="card-header">
                    <?= __("Données utilisateur", "qcm-word"); ?>
                </div>
                <div class="card-body">
                    <div>
                        <label><b><?= __("Nom", "qcm-word") ?>:</b> </label> <label><?= $raport["user_last_name"] ?></label>
                    </div>
                    <div>
                        <label><b><?= __("Prénom", "qcm-word") ?>:</b> </label> <label><?= $raport["user_first_name"]; ?></label>
                    </div>
                    <div>
                        <label><b><?= __("Email", "qcm-word") ?>:</b> </label> <label><?= $raport["user__mail"]; ?></label>
                        </div>
                    <div>
                        <label><b><?= __("Tél", "qcm-word") ?>:</b> </label> <label><?= $raport["user_phone"]; ?></label>
                    </div>
                    <div>
                        <label><b><?= __("Pays", "qcm-word") ?>:</b> </label> <label><?= $raport["user_country"]; ?></label>
                        </div>
                    <div>
                        <label><b><?= __("Ville", "qcm-word") ?>:</b> </label> <label><?= $raport["user_city"]; ?></label>
                    </div>
                    <div>
                        <label><b><?= __("Adresse IP", "qcm-word") ?>:</b> </label> <label><?= $raport["user_ip_address"]; ?></label>
                    </div>
                </div>
            </div>
        </div>

        <div class="width-half put-left">
            <div class="card ml-30 max-width-full">
                <div class="card-header">
                <?= __("Données QCM", "qcm-word"); ?>
                </div>
                <div class="card-body">
                    <div class="put-left width-full h-35">
                        <label><b><?= __("Thème", "qcm-word") ?>:</b> </label> <label class="et-primary"><?= $raport["theme"] ?></label>
                    </div>
                    <div class="put-left width-full h-35">
                        <label><b><?= __("Date rapport", "qcm-word") ?>:</b> </label> <label class="et-primary"><?= date("d/m/Y à h:i:s", strtotime($raport["created_at"])); ?></label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="width-full put-left mt-15">
            <h1><?= __("Question/Réponse", "qcm-word") ?></h1>

            <?php
                $data = json_decode($raport["data"], true);
                if (!empty($data) && is_array($data)) {
                    for($i=0; $i<sizeOf($data); $i++) {
                ?>
                
                    <div class="qcm-response-item card">
                        <div class="card-header">
                            <b><?= __("Question", "qcm-word"); ?>: </b> <?= $data[$i]["question-text"]; ?>
                        </div>

                        <div class="qcm-responses-card card-body">
                            <b><?= __("Réponses", "qcm-word"); ?>: </b>
                            <ul>
                                <?php for($j=0; $j<sizeOf($data[$i]["responses"]); $j++) { ?>
                                    <?php if ($data[$i]["responses"][$j]["response-type"] == "checkbox" && !empty($data[$i]["responses"][$j]["response-text"])) { ?>
                                        <li class="list-elem-outside"><?= ($data[$i]["responses"][$j]["response-value"] == "yes") ? "<input type='checkbox' checked disabled />": "<input type='checkbox' disabled />" ?> <?= $data[$i]["responses"][$j]["response-text"] ?></li>
                                    <?php } else { ?>
                                        <li class="list-elem-outside"><?= $data[$i]["responses"][$j]["response-value"] ?></li>
                                    <?php
                                        }
                                    ?>
                                <?php } ?>
                            </ul>
                        </div>

                        <?php if ($data[$i]["justif"] != "") { ?>
                            <div class="card-footer text-muted et-info">
                                <b><?= __("Justification", "qcm-word"); ?>:</b> <?= $data[$i]["justif"] ?>
                            </div>
                        <?php } ?>
                    </div>
                <?php
                    }
                } else {
                ?>
                    <p class="alert alert-danger"><?= __("Aucune réponse donnée par l'utilisateur!", "qcm-word") ?></p>
                <?php
                }
                ?>
        </div>
    </div>
</div>

<?php
    global $wpdb;
    $sql = "UPDATE " . $wpdb->prefix . REPORT_TABLE_NAME . " SET is_viewed = 1 WHERE id = " . $raport["id"];
    $wpdb->query($sql);
?>

<?php
        if (isset($google_ads_status) && $google_ads_status == 1) {
    ?>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2898908378075062"
        crossorigin="anonymous"></script>
    <!-- qcm-word-annonce -->
    <ins class="adsbygoogle"
    style="display:block"
    data-ad-client="ca-pub-2898908378075062"
    data-ad-slot="6391588579"
    data-ad-format="auto"
    data-full-width-responsive="true"></ins>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
    </script>
<?php
    }
?>
