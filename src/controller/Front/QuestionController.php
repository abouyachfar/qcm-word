<?php

namespace Alm\QcmWord\Controller\Front;

use Alm\QcmWord\QCMWordPlugin;

class QuestionController {

    public function __construct()
    {
        add_filter('single_template', [$this, 'qcmword_single_template']);
        add_filter('page_template', [$this, 'qcmword_page_template']);
    }

    public function qcmword_single_template(string $template) {
        global $post;

        if ( 'qcmword_question' === $post->post_type ) {
            return QCM_WORD_PLUGIN_DIR . 'views/front/question-single.php';
        }

        return $template;
    }

    public function qcmword_page_template(string $template) {
        global $post;

        if ( 'qcmword_question' === $post->post_type ) {
            return QCM_WORD_PLUGIN_DIR . 'views/front/question-page.php';
        }

        return $template;
    }
}