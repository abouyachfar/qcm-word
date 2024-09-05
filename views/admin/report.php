<?php
    use Alm\QcmWord\Controller\Admin\ReportController;
    $reportController = new ReportController();
?>

<div class="wrap">
    <h1 class="wp-heading-inline"><?= __("Rapports", "qcm-word"); ?></h1>

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
    
    <form method="post">
        <?php
            $reportController->prepare_items();
            $reportController->search_box('Rechercher','search_record');
            $reportController->display();
        ?>
    </form>
</div>