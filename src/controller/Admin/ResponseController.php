<?php

namespace Alm\QcmWord\Controller\Admin;

use Alm\QcmWord\QCMWordPlugin;

class ResponseController {

    public function __construct()
    {
        add_action("admin_init", [$this, "qcmword_init_meta"]);
        add_action("save_post", [$this, "save"]);
        add_filter('template_include', [$this, 'qcmword_single_template']);
    }

    public function qcmword_single_template(string $template) {
        global $post;

        if ( 'qcmword_response' === $post->post_type ) {
            return QCM_WORD_PLUGIN_DIR . 'views/front/response-single.php';
        }

        return $template;
    }

    public function qcmword_init_meta() {
        add_meta_box("qcmword_response", "Informations", [$this, "render"], "qcmword_response");
    }

    public function render() {
        global $post;
        $result = [];

        $question_responses = get_post_meta($post->ID, "qcmword-response", false);

        if (!empty($question_responses)) {
            $result = json_decode($question_responses[0], true);
        }

        QCMWordPlugin::render_admin("response", ["post" => $post, "question_responses" => $result]);
    }

    public function save() {
        global $post;
        $responses = [];

        if (!empty($_POST["nbr-response-item"])) {
            for($i=1; $i<=$_POST["nbr-response-item"]; $i++) {
                $responses[] = [
                    "qcm-word-question-id" => $post->ID,
                    "qcm-word-response-id" => $i,
                    "qcmword-response-type" => wp_slash($_POST["qcmword-response-type-" . $i]),
                    "qcmword-question-response" => wp_slash($_POST["qcmword-question-response-" . $i]),
                    "qcmword-response-infobulle" => wp_slash($_POST["qcmword-response-infobulle-" . $i])
                ];
            }
        }

        if (!empty($responses)) {
            update_post_meta($post->ID, "qcmword-response", wp_slash(json_encode($responses)));
        }
    }
}