<?php

namespace Alm\QcmWord\Controller\Admin;

use Alm\QcmWord\QCMWordPlugin;

class AdminController {

    public function __construct()
    {
        $this->init_hooks();
    }

    public function init_hooks() {
        add_action( 'admin_menu', [$this, 'admin_menu']);
        add_action( 'parent_file', [$this, 'menu_highlight'] );
        add_action( 'admin_action_save_general_params', [$this, 'save_general_params'] );
    }

    public function admin_menu() {
        add_menu_page( 'Tableau de bord', 'QCM WORD', 'manage_options', 'qcm-word', null, 'dashicons-feedback', 2);
        add_submenu_page( 'qcm-word', __('Thèmes', 'qcm-word'), __('Thèmes', 'qcm-word'), 'manage_options', 'edit-tags.php?taxonomy=qcmword-theme', null, 2);
        add_submenu_page( 'qcm-word', __('Rapports', 'qcm-word'), __('Rapports', 'qcm-word'), 'manage_options', 'qcm-word-report', [new ReportController(), "report_callback"], 3);
        add_submenu_page( 'qcm-word', __('Tableau de bord', 'qcm-word'), __('Tableau de bord', 'qcm-word'), 'manage_options', 'qcm-word-dashboard', [$this, "dashboard_page"], 4);
    }

    public function dashboard_page() {
        wp_enqueue_script( 'chart-qcm-admin', QCM_WORD_PLUGIN_PATH . 'assets/js/admin/chart.js' );
        wp_enqueue_script( 'dashboard-qcm-admin', QCM_WORD_PLUGIN_PATH . 'assets/js/admin/dashboard.js' );
        wp_localize_script('dashboard-qcm-admin', 'translation', array(
            'nbr_de_rapport' => __('Nbr de rapport', 'qcm-word')
        ));
        
        QCMWordPlugin::render_admin('dashboard', [
            'qcmword_question_page_nbr' => QUESTION_PAGE_NBR,
            'report_stats' =>  $this->get_report_stats()
        ]);
    }

    public function menu_highlight($parent_file) {
        global $current_screen;

        $taxonomy = $current_screen->taxonomy;
        if ( $taxonomy == 'qcmword-theme' ) {
            $parent_file = 'qcm-word';
        }

        return $parent_file;
    }

    public function get_report_stats() {
        $report_stats = ['report_count' => '0', 'new_report_count' => '0'];

        global $wpdb;
        $sql = 'SELECT count(*) FROM ' . $wpdb->prefix . REPORT_TABLE_NAME;
        $report_stats['report_count'] = $wpdb->get_var($sql);

        $sql = 'SELECT count(*) from ' . $wpdb->prefix . REPORT_TABLE_NAME . ' WHERE is_viewed is null or is_viewed != 1';
        $report_stats['new_report_count'] = $wpdb->get_var($sql);

        $sql = 'SELECT count(*) as total, theme from ' . $wpdb->prefix . REPORT_TABLE_NAME . ' group by theme order by total desc limit 5';
        $report_stats['best_five'] = $wpdb->get_results($sql, ARRAY_A);
		
		$terms = get_terms(
            array(
                'taxonomy'   => 'qcmword-theme',
                'hide_empty' => false,
                'orderby' => 'count', 
                'order' => 'DESC'
            )
        );

        $terms_array = [];
        foreach($terms as $term) {
            $terms_array[] = ['term' => $term->name, 'nbr_question' => $term->count];
        }
        $report_stats['terms_questions'] = $terms_array;

        $sql = 'SELECT id,
                user_first_name, 
                user_last_name, 
                user_phone,
                user_email,
                user_country,
                user_city,
                is_viewed
                FROM ' . $wpdb->prefix . REPORT_TABLE_NAME . ' 
                ORDER BY created_at DESC
                LIMIT 5';
        $report_stats['report_users'] = $wpdb->get_results($sql, ARRAY_A);

        return $report_stats;
    }
}