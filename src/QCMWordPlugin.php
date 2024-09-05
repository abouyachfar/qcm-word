<?php

namespace Alm\QcmWord;

use Alm\QcmWord\Controller\Admin\AdminController;
use Alm\QcmWord\Controller\Admin\QuestionController;
use Alm\QcmWord\Controller\Admin\ReportController;
use Alm\QcmWord\Controller\Admin\ResponseController;
use Alm\QcmWord\Controller\Api\DashboardApiController;
use Alm\QcmWord\Controller\Front\QCMController as FrontQCMController;
use Alm\QcmWord\Controller\Front\ResponseController as FrontResponseController;
use Alm\QcmWord\Controller\Front\QuestionController as FrontQuestionController;
use Alm\QcmWord\Controller\Api\QCMApiController as QCMApiQuestionController;
use WP_Http_Curl;

class QCMWordPlugin {
    public function __construct(string $file)
    {
        add_action('init', [$this, "plugin_activation"]);

        if (is_admin()) {
            add_action('wp', [$this, "after_load_wp"]);
            new AdminController();
            new QuestionController();
            new ResponseController();

            // Charger js
            add_action( 'admin_enqueue_scripts', [$this, 'qcmword_enqueue_scripts'] );
        } else {
            new FrontResponseController();
            new FrontQuestionController();
            new FrontQCMController();
            new QCMApiQuestionController();
            new DashboardApiController();

            // Charger js
            add_action('wp_enqueue_scripts', [$this, 'qcmword_front_enqueue_scripts']);
        }
    }

    public function after_load_wp() {
        $page = \wp_slash($_POST["page"]);

        if ($page == "qcm-word-report") {
            new ReportController();
        }
    }

    public function qcmword_enqueue_scripts() {
        wp_enqueue_style( 'style-qcm-admin', QCM_WORD_PLUGIN_PATH . 'assets/css/admin/style.css' );

        wp_enqueue_script( 'thickbox' );
        wp_enqueue_style( 'thickbox' );
    
        if ( is_network_admin() ) {
            add_action( 'admin_head', '_thickbox_path_admin_subfolder' );
        }
	}

    function qcmword_front_enqueue_scripts() {
        wp_enqueue_script( 'jquery-qcm-front', QCM_WORD_PLUGIN_PATH . 'assets/js/front/jquery-v3.7.1.js' );
        wp_enqueue_script( 'script-qcm-front', QCM_WORD_PLUGIN_PATH . 'assets/js/front/qcm-page.js' );
        wp_localize_script('script-qcm-front', 'translation', array(
            'nom_requis' => __('Le champ Nom est obligatoire!', 'qcm-word'),
            'prenom_requis' => __('Le champ Prénom est obligatoire!', 'qcm-word'),
            'tel_requis' => __('Le champ Tél est obligatoire!', 'qcm-word'),
            'email_requis' => __('Le champ Email est obligatoire!', 'qcm-word'),
            'email_invalid' => __('Le champ Email est invalide!', 'qcm-word'),
            'pays_requis' => __('Le champ Pays est obligatoire!', 'qcm-word'),
            'ville_requis' => __('Le champ Ville est obligatoire!', 'qcm-word'),
            'precedent' => __('Précédent', 'qcm-word'),
            'suivant' => __('Suivant', 'qcm-word'),
            'reponse_requise' => __('Merci de saisir ou choisir au moins une réponse!', 'qcm-word'),
            'Oui' => __('Oui', 'qcm-word'),
            'Non' => __('Non', 'qcm-word'),
            'justif_requise' => __('Merci de justifier votre réponse', 'qcm-word'),
            'message_sauvegarde_succes_1' => __('Vos réponses sont enregistrés!', 'qcm-word'),
            'message_sauvegarde_succes_2' => __('Nos équipes vous contacterons le plus tôt possible.', 'qcm-word'),
            'felicitation' => __('Félicitation', 'qcm-word'),
            'erreur' => __('Un erreur est survenue! Merci de ressayer plus tard', 'qcm-word'),
            'aucune_donnees' => __('Aucune question trouvées!', 'qcm-word'),
        ));
        wp_enqueue_style( 'bootstrap-qcm-front', QCM_WORD_PLUGIN_PATH . 'assets/css/front/bootstrap.min.css' );
        wp_enqueue_style( 'style-qcm-front', QCM_WORD_PLUGIN_PATH . 'assets/css/front/style.css' );
    }

    public function plugin_activation() {
        register_post_type('qcmword_question',
            array(
                'labels'      => array(
                    'name'          => __('Questions', 'textdomain'),
                    'singular_name' => __('Question', 'textdomain'),
                ),
                'public'      => true,
                'has_archive' => true,
                'rewrite'     => array( 'slug' => 'questions' ),
                'show_ui' => true,
                'show_in_menu' => 'qcm-word',
                'menu_position' => 1,
                'supports' => array( 'title', 'editor', 'author', 'thumbnail' )
            )
        );

        register_taxonomy('qcmword-theme', 'qcmword_question', [
            'labels' => [
                'name'          => __('Thèmes', 'textdomain'),
                'singular_name' => __('Thème', 'textdomain'),
            ],
            'public'      => true,
            'has_archive' => true,
            'rewrite'     => array( 'slug' => 'themes' ),
            'show_in_menu' => 'qcm-word',
            'menu_position' => 2,
            'show_in_rest' => true,
            'hierarchical' => true,
            'show_admin_column' => true
        ]);

        // Create tables
        global $wpdb;
        $sql = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "qcmword_reports` (
                    `id` int NOT NULL AUTO_INCREMENT,
                    `qcm_user_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                    `user_id` int NOT NULL,
                    `user_last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                    `user_first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                    `user_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                    `user_phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                    `created_at` datetime NOT NULL,
                    `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
                    `user_country` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                    `user_city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                    `user_ip_address` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                    `theme` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
                    `is_viewed` int NOT NULL,
                    PRIMARY KEY (`id`)
                ) ENGINE=MyISAM AUTO_INCREMENT=95 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
        $wpdb->query($sql);
    }

    public static function render_admin(string $name, array $args = []): void {
        $http = new WP_Http_Curl();
        $response = $http->request(QCM_WORD_API);
        $qcm_word_messages = json_decode($response["body"], true);

        $args["qcm_word_messages"] = $qcm_word_messages["messages"];
        $args["qcm_word_banners"] = $qcm_word_messages["banners"];
        $args["google_ads_status"] = $qcm_word_messages["google_ads_status"];
        
        extract($args);

        $file = QCM_WORD_PLUGIN_DIR . "views/admin/$name.php";

        ob_start();

        include_once($file);

        echo ob_get_clean();
    }

    public static function render_front(string $name, array $args = []): void {
        extract($args);

        $file = QCM_WORD_PLUGIN_DIR . "views/front/$name.php";

        ob_start();

        include_once($file);
        
        echo ob_get_clean();
    }
}