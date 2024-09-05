<?php
/**
 * Plugin Name: QCM WORD
 * Description: Créez facilement votre QCM !
 * Version: 0.1
 * Author: Abdelmalik Bouyachfar
 * Author E-MAIL: abdelmalik.bouyachfar@gmail.com
 * Text Domain: qcm-word
 * Domain Path: languages
 */

use Alm\QcmWord\QCMWordPlugin;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define ('QCM_WORD_PLUGIN_DIR', plugin_dir_path(__FILE__));
define ( 'QCM_WORD_PLUGIN_PATH', plugin_dir_url(__FILE__) );
define ('PLUGIN_PREF', 'qcmword');
define('QUESTION_PAGE_NBR', 1);
define('REPORT_TABLE_NAME', 'qcmword_reports');
define('QCM_WORD_API', 'http://62.171.181.165:8585');

require plugin_dir_path(__FILE__) . 'vendor/autoload.php';

$plugin = new QCMWordPlugin(__FILE__);

add_action('init', "plugin_activation");

function plugin_activation() {
    load_plugin_textdomain('qcm-word', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');
}
