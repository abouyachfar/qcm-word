<?php

namespace Alm\QcmWord\Controller\Api;

use Alm\QcmWord\QCMWordPlugin;
use DateTime;
use WP_Query;
use WP_REST_Request;

class QCMApiController {
    CONST TABLE_QCM_REPORT = "qcmword_reports";

    public function __construct()
    {
        add_action( 'rest_api_init', [$this, 'register_routes']);
    }

    public function register_routes() {
        register_rest_route('qcm-word/api/v1', 'qcm-word-listing',[
            'methods' => 'GET',
            'callback' => [$this, 'qcmword_listing'], 
        ]);
        
        register_rest_route('qcm-word/api/v1', 'post-user-response',[
            'methods' => 'POST',
            'callback' => [$this, 'post_user_response'], 
        ]);
        
        register_rest_route('qcm-word/api/v1', 'qcm-word-save-user-qcm',[
            'methods' => 'POST',
            'callback' => [$this, 'save_user_qcm'], 
        ]);
    }

    public function qcmword_listing(WP_REST_Request $request) {
        $data = [];
        $paged = ($request["paged"]) ? intval($request['paged']) : 1;
        $tax = 'qcmword-theme';
        $oterm = esc_attr($request["theme"]);
        
        $the_query = new WP_Query(
            array(
                'paged' => $paged,
                'posts_per_page' => QUESTION_PAGE_NBR,
                'tax_query' => array(
                    array(
                        'taxonomy' => $tax,
                        'field' => 'slug',
                        'terms' => $oterm
                    )
                ),
                'orderby'        => 'meta_value',
                'meta_key'       => 'qcmword-question-order',
                'order'          => 'ASC', 
            )
        );

        if ( ! isset( $the_query ) ) {
            return -1;
        }

        while ( $the_query->have_posts() ) {
            $the_query->the_post();
            global $post;

            $responses = [];
            $result = get_post_meta($post->ID, "qcmword-response", false);

            if (!empty($result)) {
                $responses = json_decode($result[0], true);
            }

            $data[] = [
                "post" => $post, 
                "responses" => $responses, 
                "justif_required" => get_post_meta($post->ID, "qcmword-question-justif-required", true),
            ];
        }

        return json_encode(["data" => $data, "oterm" => $oterm, "paged" => $paged], true);
    }

    public function post_user_response(WP_REST_Request $request) {
        $return = 1;

        // Prepare data
        $json = $request->get_params();
        $data = $json["data"];
        $tax = 'qcmword-theme';
        $oterm = $json["theme"];
        $paged = $json["paged"];
        $qcm_word_user_id = $data[0]["qcm_word_user_id"];
    
        // Check if there are questions
        $the_query = new WP_Query(
            array(
                'paged' => 1,
                'posts_per_page' => 1,
                'tax_query' => array(
                    array(
                        'taxonomy' => $tax,
                        'field' => 'slug',
                        'terms' => $oterm
                    )
                ),
                'orderby' => 'ID',
                'order' => 'ASC'
            )
        );

        if ( ! isset( $the_query ) ) {
            $return = -1;
        }

        // Select of qcm_report
        global $wpdb;
        $sql = "select * from " . $wpdb->prefix . self::TABLE_QCM_REPORT . " WHERE qcm_user_id = " . $qcm_word_user_id . " ORDER BY ID DESC LIMIT 1";
        $qcm_word_user_obj = $wpdb->get_results($sql, 'ARRAY_A')[0];
        $qcm_data = ($qcm_word_user_obj["data"] != null) ? json_decode($qcm_word_user_obj["data"], true) : [];

        // merge new data
        for($i=0; $i<sizeOf($data); $i++) {
            $qcm_data[] = ["question-id" => $data[$i]["question-id"], "question-text" => $data[$i]["question-text"], "justif" => $data[$i]["justif"], "responses" => $data[$i]["responses"]];
        }

        // Update report in database
        global $wpdb;
        $sql = "UPDATE " . $wpdb->prefix . self::TABLE_QCM_REPORT . " SET data = '" . wp_slash(json_encode($qcm_data, true)) . "'";
        $sql .= " WHERE qcm_user_id = '" . $qcm_word_user_id . "'";
        $wpdb->query($sql);

        return json_encode(1, true);
    }

    public function save_user_qcm(WP_REST_Request $request) {
        $json = $request->get_params();
        $data = $json["data"];
        $user_ip_address = "";

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $user_ip_address = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $user_ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $user_ip_address = $_SERVER['REMOTE_ADDR'];
        }

        global $wpdb;
        $qcm_word_user_id = date_timestamp_get(new DateTime());
        $sql = "INSERT INTO " . $wpdb->prefix . self::TABLE_QCM_REPORT;
        $sql .= "(user_id, qcm_user_id, theme, user_last_name, user_first_name, user_email, user_phone, created_at, user_country, user_city, user_ip_address)";
        $sql .= " values(0,'" . $qcm_word_user_id . "','" . $data["theme"] . "','" . $data["lastName"]. "','" . $data["firstName"] . "','" . $data["email"] . "','" . $data["phone"] . "', '" . date("Y-m-d h:i:s") . "','" . $data["country"] . "','" . $data["city"] . "','" . $user_ip_address . "') ";
        $wpdb->query($sql);

        return json_encode($qcm_word_user_id,true);
    }
}