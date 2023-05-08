<?php
/**
 * 
 */
class Wf_Solar_Est_Admin
{
	
	function __construct()
	{
			add_action( 'admin_enqueue_scripts', array( $this, 'wf_solar_front_script_admin' ), 10 );
	}
	
				public function wf_solar_front_script_admin() {
wp_enqueue_style( 'date_table_css', 'https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css', false, '1.0', false );
		wp_enqueue_style( 'date_table_min_css', 'https://cdn.datatables.net/datetime/1.1.2/css/dataTables.dateTime.min.css', false, '1.0', false );
		wp_enqueue_script( 'moment_js', 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.2/moment.min.js', array( 'jquery' ), '', true );
		wp_enqueue_script( 'date_table_js', 'https://cdn.datatables.net/datetime/1.1.2/js/dataTables.dateTime.min.js', array( 'jquery' ), '', true );
		wp_enqueue_style('rea_table', 'https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css');
        wp_enqueue_script( 'rea_table_js', 'https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js', array( 'jquery' ), '', true );
			// Enqueue Scripts.
		wp_enqueue_script( 'wf-solar-est-admin', plugins_url( 'assets/js/wf_solar_est_admin_js.js', __FILE__ ), array( 'jquery' ), '1.0.9', false );
	}
}
new Wf_Solar_Est_Admin();