<?php

namespace Alm\QcmWord\Controller\Api;

use Alm\QcmWord\QCMWordPlugin;
use DateTime;
use WP_Query;
use WP_REST_Request;

class DashboardApiController {
    CONST TABLE_QCM_REPORT = "qcmword_reports";

    public function __construct()
    {
        add_action( 'rest_api_init', [$this, 'register_routes']);
    }

    public function register_routes() {
        register_rest_route('qcm-word/api/v1', 'qcm-word-report-stats-by-country',[
            'methods' => 'GET',
            'callback' => [$this, 'report_stats_by_country'], 
        ]);
        
        register_rest_route('qcm-word/api/v1', 'qcm-word-report-stats-by-term',[
            'methods' => 'GET',
            'callback' => [$this, 'report_stats_by_term'], 
        ]);
    }

    public function report_stats_by_country() {
        global $wpdb;

        $sql = "SELECT count(*) as y, user_country as x FROM " . $wpdb->prefix . REPORT_TABLE_NAME . "
                GROUP BY x 
                ORDER BY x
                LIMIT 5";
        $country_stats = $wpdb->get_results($sql, "ARRAY_A");

        /*
        foreach($country_stats as $key => $c_stats) {
            $sql = "SELECT count(*) as total, user_city FROM " . $wpdb->prefix . REPORT_TABLE_NAME . "
                WHERE LOWER(user_country) = '" . strtolower($c_stats["user_country"]) . "'
                GROUP BY user_city 
                ORDER BY total DESC
                LIMIT 5";
            $city_stats = $wpdb->get_results($sql, "ARRAY_A");

            $country_stats[$key]["cities"] = $city_stats;
        }
        $report_stats["country_stats"] = $country_stats;
        */

        return json_encode($country_stats, true);
    }

    public function report_stats_by_term() {
        $labels = [];
        $values = [];

        $terms = get_terms(
            array(
                'taxonomy'   => 'qcmword-theme',
                'hide_empty' => false,
                'orderby' => 'count', 
                'order' => 'DESC'
            )
        );

        foreach($terms as $term) {
            $labels[] = $term->name.
            $values[] = $term->count;
        }

        return json_encode(["labels" => $labels, "values" => $values], true);
    }
}