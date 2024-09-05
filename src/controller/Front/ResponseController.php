<?php

namespace Alm\QcmWord\Controller\Front;

use Alm\QcmWord\QCMWordPlugin;

class ResponseController {

    public function __construct()
    {
        add_filter('template_include', [$this, 'qcmword_single_template']);
    }

    public function qcmword_single_template(string $template) {
        global $post;

        if ( 'qcmword_response' === $post->post_type ) {
            return QCM_WORD_PLUGIN_DIR . 'views/front/response-single.php';
        }

        return $template;
    }
}