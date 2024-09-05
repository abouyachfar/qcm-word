<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
?>

<div class="wrap">
    <h1 class="wp-heading-inline"><?= __("Tableau de bord", "qcm-word"); ?></h1>
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
    <div class="put-left card width-half qcm-response-item" style="height: 400px">
        <div class="card-header">
            <b><?= __("Stats des Rapports par thème", "qcm-word"); ?></b>
        </div>

        <div class="qcm-responses-card card-body" style="height: auto">
            <canvas id="report-term-chart" class="mrg-auto"></canvas>
        </div>
    </div>

    <div class="put-left card width-half qcm-response-item" style="height: 400px">
        <div class="card-header">
            <b><?= __("Stats des Rapports Par Pays", "qcm-word"); ?></b>
        </div>

        <div class="qcm-responses-card card-body" style="height: auto">
            <canvas id="report-country-chart" class="mrg-auto width-full"></canvas>
        </div>
    </div>
</div>

<?php
    if (isset($google_ads_status) && $google_ads_status == 1) {
?>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2898908378075062" crossorigin="anonymous"></script>
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
    
<div class="row">
    <div class="put-left card width-full qcm-response-item h-400">
        <div class="card-header">
            <b><?= __("Les derniers utilisateurs", "qcm-word"); ?></b>
            <a href="<?= admin_url( 'admin.php' ); ?>?page=qcm-word-report" class="page-title-action put-right"><?= _e('Voir plus', 'qcm-word') ?></a>
        </div>

        <div class="qcm-responses-card card-body">
            <table class="wp-list-table widefat fixed striped table-view-list">
                <thead>
                    <tr>
                        <th width="15%"><?= __('Nom', 'qcm-word'); ?></th>
                        <th width="15%"><?= __('Prénom', 'qcm-word'); ?></th>
                        <th width="10%"><?= __('Tél', 'qcm-word'); ?></th>
                        <th width="20%"><?= __('Adresse mail', 'qcm-word'); ?></th>
                        <th width="13%"><?= __('Pays', 'qcm-word'); ?></th>
                        <th width="13%"><?= __('Ville', 'qcm-word'); ?></th>
                        <th width="5%"><?= __('Vu', 'qcm-word'); ?></th>
                        <th width="5%"><?= __('Actions', 'qcm-word'); ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                    foreach($report_stats["report_users"] as $report_user) {
                ?>
                        <tr>
                            <td><?= $report_user["user_first_name"]; ?></td>
                            <td><?= $report_user["user_last_name"]; ?></td>
                            <td><?= $report_user["user_phone"]; ?></td>
                            <td><label title='<?= $report_user["user__mail"]; ?>'><?= substr($report_user["user__mail"], 0, 35); ?></label></td>
                            <td><?= $report_user["user_country"]; ?></td>
                            <td><?= $report_user["user_city"]; ?></td>
                            <td><?= $report_user["is_viewed"] == 1 ? "<label class='et-success'>" . __('Oui', 'qcm-word') . "</label>" : "<label class='et-danger'>" . __('Non', 'qcm-word') . "</label>"; ?></td>
                            <td><a href="<?= admin_url( 'admin.php' ); ?>?page=qcm-word-report&action=report_detail&record=<?= $report_user['id']; ?>">Détails</a></td>
                        </tr>
                <?php
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="put-left card width-half qcm-response-item">
        <div class="card-header">
            <b><?= __("Nombre de rapports", "qcm-word"); ?></b>
            <a href="<?= admin_url( 'admin.php' ); ?>?page=qcm-word-report" class="page-title-action put-right"><?= _e('Voir plus', 'qcm-word') ?></a>
        </div>

        <div class="qcm-responses-card card-body">
            <ul>
                <li class="list-elem-outside"><?= __('Nombre total de rapports', 'qcm-word') ?>: <label class="et-info"><?= $report_stats["report_count"]; ?></label></li>
                <li class="list-elem-outside"><?= __('Rapports non lus', 'qcm-word') ?>: <label class="et-danger"><?= $report_stats["new_report_count"]; ?></label></li>
            </ul>
        </div>
    </div>

    <div class="put-left card width-half qcm-response-item">
        <div class="card-header">
            <b><?= __("Liste des thèmes", "qcm-word"); ?></b>
            <a href="<?= admin_url(); ?>/edit-tags.php?taxonomy=qcmword-theme" class="page-title-action put-right"><?= _e('Voir plus', 'qcm-word') ?></a>
        </div>

        <div class="qcm-responses-card card-body">
            <ul>
                <?php 
                    foreach($report_stats["terms_questions"] as $term_questions) {
                ?>
                    <li>
                        <?= $term_questions["term"]?>
                        <ul>
                            <li><label class="et-info"><?= __('Nombre de questions', 'qcm-word') ?>: <?= $term_questions["nbr_question"]?></label></li>
                        </ul>
                    </li>
                <?php
                    }
                ?>
            </ul>
        </div>
    </div>
</div>
</div>