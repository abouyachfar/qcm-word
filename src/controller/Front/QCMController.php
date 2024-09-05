<?php

namespace Alm\QcmWord\Controller\Front;

use Alm\QcmWord\QCMWordPlugin;
use DateTime;
use WP_Query;
use WP_REST_Request;

class QCMController {

    public function __construct()
    {
        add_shortcode("qcmword_listing", [$this, "qcmword_listing"]);
    }
    
    public function qcmword_listing($attrs) {
        $qcm_word_user_id = get_query_var("qcm_word_user_id");
        $data = [];
        $qcmword_question_page_nbr = QUESTION_PAGE_NBR;
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

        $tax = 'qcmword-theme';
        $oterm = $attrs["theme"];
        
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
                'orderby' => 'ID',
                'order' => 'ASC'
            )
        );

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
                "justif_required" => get_post_meta($post->ID, "qcmword-question-justif-required", true)
            ];
        }

        return QCMWordPlugin::render_front("qcm-page-js", ["data" => $data, "qcm_word_user_id" => $qcm_word_user_id, "oterm" => $oterm, "paged" => $paged]);
    }
}