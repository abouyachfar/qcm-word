<?php

namespace Alm\QcmWord\Controller\Admin;

use Alm\QcmWord\QCMWordPlugin;

class QuestionController {

    private $response;

    public function __construct()
    {
        $this->response = new ResponseController();

        add_action("admin_init", [$this, "qcmword_init_meta"]);
        add_action("save_post", [$this, "save"]);
    }

    public function qcmword_init_meta() {
        add_meta_box("qcmword_question", __('Informations', 'qcm-word'), [$this, "render"], "qcmword_question");
        add_meta_box("qcmword_response", __('Réponses', 'qcm-word'), [$this->response, "render"], "qcmword_question");
    }

    public function render() {
        wp_enqueue_script( 'script-qcm-admin', QCM_WORD_PLUGIN_PATH . 'assets/js/admin/response.js' );
        wp_localize_script('script-qcm-admin', 'translation', array(
            'reponse' => __('Réponse', 'qcm-word'),
            'type_du_champ' => __('Type du champ', 'qcm-word'),
            'case_a_cocher' => __('Case à cocher', 'qcm-word'),
            'texte' => __('Texte', 'qcm-word'),
            'text_multilignes' => __('Texte Multilignes', 'qcm-word'),
            'InfoBulle' => __('InfoBulle', 'qcm-word'),
        ));

        global $post;
        $order_value = get_post_meta($post->ID, "qcmword-question-order", true);
        $direct_value = get_post_meta($post->ID, "qcmword-question-direct", true);
        $justificatif_value = get_post_meta($post->ID, "qcmword-question-justif-required", true);

        QCMWordPlugin::render_admin("question", ["post" => $post, "order_value" => $order_value, "direct_value" => $direct_value, "justificatif_value" => $justificatif_value]);
    }

    public function save() {
        global $post;

        if (current_user_can("edit_post", $post->ID) && \wp_verify_nonce($_POST["qcmword-response-nonce"])) {
            $nbr_response_item = intval($_POST["nbr-response-item"]);

            $meta_value = intval($_POST["qcmword-question-order"]);
            if ($meta_value == "" && $meta_value != 0) {
                delete_post_meta($post->ID, "qcmword-question-order");
            } else if (get_post_meta($post->ID, "qcmword-question-order")){
                update_post_meta($post->ID, "qcmword-question-order", $meta_value);
            } else {
                add_post_meta($post->ID, "qcmword-question-order", $meta_value, true);
            }

            $meta_value = wp_slash($_POST["qcmword-question-direct"]);
            if ($meta_value == "" && $meta_value != 0) {
                delete_post_meta($post->ID, "qcmword-question-direct");
            } else if (get_post_meta($post->ID, "qcmword-question-direct")){
                update_post_meta($post->ID, "qcmword-question-direct", wp_slash($meta_value));
            } else {
                add_post_meta($post->ID, "qcmword-question-direct", wp_slash($meta_value), true);
            }

            $meta_value = wp_slash($_POST["qcmword-question-justif-required"]);
            if (get_post_meta($post->ID, "qcmword-question-justif-required")) {
                update_post_meta($post->ID, "qcmword-question-justif-required", wp_slash($meta_value));
            } else {
                add_post_meta($post->ID, "qcmword-question-justif-required", wp_slash($meta_value));
            }

            $this->response->save($nbr_response_item);
        }
    }
}