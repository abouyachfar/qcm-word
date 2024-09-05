<?php

namespace Alm\QcmWord\Controller\Admin;

use Alm\QcmWord\QCMWordPlugin;
use WP_List_Table;

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class ReportController extends WP_List_Table {
    public function __construct()
	{
		parent::__construct(array(
			'singular' => __('Rapport', 'qcm-word'),
			'plural' => __('Rapports', 'qcm-word'),
			'ajax' => false
		));
	}

    // Retrieve Data Records From Database
    public static function get_records($per_page = 20, $page_number = 1)
    {
        global $wpdb;
        $sql = "select * from " . $wpdb->prefix . REPORT_TABLE_NAME;
        if (isset($_REQUEST['s'])) {
            $sql.= ' where user_email LIKE "%' . $_REQUEST['s'] . '%" or user_last_name LIKE "%' . $_REQUEST['s'] . '%" or user_first_name LIKE "%' . $_REQUEST['s'] . '%"';
        }
        if (!empty($_REQUEST['orderby'])) {
            $sql.= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
            $sql.= !empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
        }
        $sql.= " LIMIT $per_page";
        $sql.= ' OFFSET ' . ($page_number - 1) * $per_page;
        $result = $wpdb->get_results($sql, 'ARRAY_A');
        return $result;
    }

    // Define Column Name for Dashboard Table
    function get_columns()
    {
        $columns = [
            'cb' => '<input type="checkbox" />', 
            'user_last_name' => __('Nom', 'qcm-word') , 
            'user_first_name' => __('Prénom', 'qcm-word') , 
            'user_email' => __('Email', 'qcm-word') , 
            'user_phone' => __('Téléphone', 'qcm-word'),
            'created_at' => __('Créé le', 'qcm-word')
        ];
        return $columns;
    }

    // Define Hidden Columns
    public function get_hidden_columns()
    {
        array([
            // 'created_at' => __('created_at','qcm-word')
        ]);
        return array();
    }

    // Define Sortable Columns
    public function get_sortable_columns()
    {
        $sortable_columns = array(
            'user_name' => array('user_last_name',true),
            'user_name' => array('user_first_name',true),
            'user_email' => array('user_email',true),
            'user_phone' => array('user_phone',true),
            'created_at' => array('created_at',true)
        );
        return $sortable_columns;
    }

    // Define Default Columns
    public function column_default( $item, $column_name ) 
    {
        switch ( $column_name ) {
            case 'user_last_name':
            case 'user_first_name':
            case 'user_email':
            case 'user_phone':
            case 'created_at':
            return $item[ $column_name ];
            default:
            return print_r( $item, true );
        }
    }

    // Add Checkboxes to Data Rows
    function column_cb($item)
    {
        return sprintf('<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['id']);
    }

    // Define Column Options
    function column_user_last_name( $item ) 
    {
        $actions = array(
            'detail'      => sprintf('<a href="' . admin_url( 'admin.php' ) . '?page=%s&action=%s&record=%s">' . __("Détails", "qcm-word") . '</a>',$_REQUEST['page'],'report_detail',$item['id']),
        );
        return sprintf('%1$s %2$s', $item['user_last_name'], $this->row_actions($actions) );
    }

    // Define Bulk Action
    public function get_bulk_actions()
    {
        $actions = ['bulk-delete' => __('Supprimer', 'qcm-word')];
        return $actions;
    }

    // Delete function
    public static function delete_records($id)
    {
        global $wpdb;
        $wpdb->delete($wpdb->prefix . REPORT_TABLE_NAME, ['id' => $id], ['%d']);
    }

    // Count Records
    public static function record_count()
    {
        global $wpdb;
        $sql = "SELECT COUNT(*) FROM " . $wpdb->prefix . REPORT_TABLE_NAME;
        return $wpdb->get_var($sql);
    }

    // No Records
    public function no_items()
    {
        __('Aucun enregitrement trouvé!', 'qcm-word');
    }

    // Process Bulk Action
    public function process_bulk_action() 
    {
        if ( 'delete' === $this->current_action() ) {	    
            self::delete_records( absint( $_GET['record'] ) );
        }

        if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' ) || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )) {
            $delete_ids = esc_sql( $_POST['bulk-delete'] );
            foreach ( $delete_ids as $id ) {
                self::delete_records( $id );
            }
        }
    }

    // Prepare Items
    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array( $columns, $hidden, $sortable );
        $this->process_bulk_action();
        $per_page = $this->get_items_per_page('records_per_page', 20);
        $current_page = $this->get_pagenum();
        $total_items = self::record_count();
        $data = self::get_records($per_page, $current_page);
        $this->set_pagination_args( [
            'total_items' => $total_items,
            'per_page' => $per_page,
        ]);
        $this->items = $data;
    }

    public function report_callback() {
        $action = \wp_slash($_GET["action"]);
        $page = \wp_slash($_GET["page"]);

        if ($page == "qcm-word-report" && empty($action)) {
            QCMWordPlugin::render_admin("report");
        } else if ($page == "qcm-word-report" && $action == "report_detail") {
            $raport_id = intval($_GET["record"]);

            global $wpdb;
            $sql = "select * from " . $wpdb->prefix . REPORT_TABLE_NAME . " WHERE id = " . $raport_id;
            $raport = $wpdb->get_row($sql, 'ARRAY_A');

            QCMWordPlugin::render_admin("report_detail", ["raport" => $raport]);
        }
    }
}