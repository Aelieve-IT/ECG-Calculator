<?php
/**
 * Plugin Name:     Wolfiz Solar Estimator (simple)
 * Plugin URI:        
 * Description:     Calculate the cost of the soalr panels using this plugin.
 * Version:           1.0.0
 * Author:            
 * Developed By:      
 * Author URI:        
 * Support:           
 * License:           GPL-2.0+
 * License URI:       
 * Domain Path:       /languages
 * Text Domain:       wf_solar_est
 * WC requires at least: 3.0.9
 * WC tested up to: 
 *
 
 *
 * @package Wolfiz
 */

if ( ! defined( 'ABSPATH' ) ) {

    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Wf_Solar_Est_Main' ) ) {

    /**
     * Main class of Plugin
     */
    class Wf_Solar_Est_Main {


        /**
         * Constructor of class.
         *
         * @return void
         */
        public function __construct() {
            $this->wf_solar_global_constents_vars();

            if ( is_admin() ) {

                include_once WFSOLAREST_PLUGIN_DIR . '/includes/class-wf-soalr-est-admin.php';
                
            }else{
                include_once WFSOLAREST_PLUGIN_DIR . '/includes/class-wf-soalr-est-front.php';
            }
            
            add_action('admin_menu', array($this,'wf_add_submenu_solar_est'));
            add_action('admin_init', array($this,'wf_soalr_calculation_register_settings'));
            add_action( 'wp_ajax_wf_get_state_provider_data', array( $this, 'wf_get_state_provider_data' ) );
            add_action( 'wp_ajax_nopriv_wf_get_state_provider_data', array( $this, 'wf_get_state_provider_data' ) );
		

            //Cron job
            add_filter( 'cron_schedules', array( $this, 'wf_cronjob_time_interval_solar' ) );
            add_action( 'init', array( $this, 'wf_cronjob_time_callback_solar' ) );
            add_action( 'solar_cron_job_time_update', array( $this, 'wf_start_cronjob_solar' ) );
            
        }
        

        /**
         * Constructor of class.
         *
         * @return void
         */

        public function wf_get_state_provider_data(){
            ?>
            <style>
                /* HIDE RADIO */
                [type=radio] { 
                  position: absolute;
                  opacity: 0;
                  width: 0;
                  height: 0;
              }

              /* IMAGE STYLES */
              [type=radio] + img {
                  cursor: pointer;
              }

              /* CHECKED STYLES */
              [type=radio]:checked + img {
                  outline: 2px solid #409eff;
              }
              [type=radio] + img:hover {
                  outline: 2px solid #409eff;
              }
              
              .display_items{
                display:flex;
                justify-content:center;
            }
            .display_items_next{
                display:flex;
                justify-content:center;
            }
            .alig_items{
                text-align:center;
                padding:1%;
            }
        </style>
        <div class="display_items"> 
            <?php
            $state=$_POST['state'];
            if($state=='IL'){
                ?>
                <label class="alig_items save_radio_val">
                  <input type="radio" class="radio_close" name="test" value="Ameren">
                  <img width="170px !important" height="170px !important" src="<?php echo esc_url(plugins_url('/wolfiz-solar-estimator/includes/assets/images/6.jpg')); ?>">
              </label>
              <label class="alig_items save_radio_val">
                  <input type="radio" class="radio_close" name="test" value="Illinois Power">
                  <img width="170px !important" height="170px !important" src="<?php echo esc_url(plugins_url('/wolfiz-solar-estimator/includes/assets/images/5.jpg')); ?>">
              </label>
              <label class="alig_items save_radio_val">
                  <input type="radio" class="radio_close" name="test" value="Champion Energy">
                  <img width="170px !important" height="170px !important" src="<?php echo esc_url(plugins_url('/wolfiz-solar-estimator/includes/assets/images/4.jpg')); ?>">
              </label>
              <label class="alig_items save_radio_val">
                  <input type="radio" class="radio_close" name="test" value="City Water Light & Power">
                  <img width="170px !important" height="170px !important" src="<?php echo esc_url(plugins_url('/wolfiz-solar-estimator/includes/assets/images/2.jpg')); ?>">
              </label>
              <label class="alig_items save_radio_val">
                  <input type="radio" class="radio_close" name="test" value="ComEd">
                  <img width="170px !important" height="170px !important" src="<?php echo esc_url(plugins_url('/wolfiz-solar-estimator/includes/assets/images/8.jpg')); ?>">
              </label>
          </div>
          <div class="display_items_next">  
            <label class="alig_items save_radio_val">
              <input type="radio" class="radio_close" name="test" value="Direct Energy">
              <img width="170px !important" height="170px !important" src="<?php echo esc_url(plugins_url('/wolfiz-solar-estimator/includes/assets/images/1.jpg')); ?>">
          </label>
          <label class="alig_items save_radio_val">
              <input type="radio" class="radio_close" name="test" value="Other">
              <img width="170px !important" height="170px !important" src="<?php echo esc_url(plugins_url('/wolfiz-solar-estimator/includes/assets/images/other.png')); ?>">
          </label>
      </div>
      <?php
  }elseif($state=='IA'){
    ?>
    <div class="display_items"> 
        <label class="alig_items save_radio_val">
          <input type="radio" class="radio_close" name="test" value="Interstate Power and Light Company">
          <img width="170px !important" height="170px !important" src="<?php echo esc_url(plugins_url('/wolfiz-solar-estimator/includes/assets/images/10.jpg')); ?>">
      </label>
      <label class="alig_items save_radio_val">
          <input type="radio" class="radio_close" name="test" value="MidAmerican Energy">
          <img width="170px !important" height="170px !important" src="<?php echo esc_url(plugins_url('/wolfiz-solar-estimator/includes/assets/images/7.jpg')); ?>">
      </label>
      <label class="alig_items save_radio_val">
          <input type="radio" class="radio_close" name="test" value="Other">
          <img width="170px !important" height="170px !important" src="<?php echo esc_url(plugins_url('/wolfiz-solar-estimator/includes/assets/images/other.png')); ?>">
      </label>
  </div>
  <div class="display_items"> 
      <?php
  }elseif($state=='WI'){
    ?>
        <label class="alig_items save_radio_val">
                  <input type="radio" class="radio_close" name="test" value="Ameren">
                  <img width="170px !important" height="170px !important" src="<?php echo esc_url(plugins_url('/wolfiz-solar-estimator/includes/assets/images/6.jpg')); ?>">
              </label>
              <label class="alig_items save_radio_val">
                  <input type="radio" class="radio_close" name="test" value="Illinois Power">
                  <img width="170px !important" height="170px !important" src="<?php echo esc_url(plugins_url('/wolfiz-solar-estimator/includes/assets/images/5.jpg')); ?>">
              </label>
              <label class="alig_items save_radio_val">
                  <input type="radio" class="radio_close" name="test" value="Champion Energy">
                  <img width="170px !important" height="170px !important" src="<?php echo esc_url(plugins_url('/wolfiz-solar-estimator/includes/assets/images/4.jpg')); ?>">
              </label>
              <label class="alig_items save_radio_val">
                  <input type="radio" class="radio_close" name="test" value="City Water Light & Power">
                  <img width="170px !important" height="170px !important" src="<?php echo esc_url(plugins_url('/wolfiz-solar-estimator/includes/assets/images/2.jpg')); ?>">
              </label>
              <label class="alig_items save_radio_val">
                  <input type="radio" class="radio_close" name="test" value="ComEd">
                  <img width="170px !important" height="170px !important" src="<?php echo esc_url(plugins_url('/wolfiz-solar-estimator/includes/assets/images/8.jpg')); ?>">
              </label>
          </div>
          <div class="display_items_next">  
            <label class="alig_items save_radio_val">
              <input type="radio" class="radio_close" name="test" value="Direct Energy">
              <img width="170px !important" height="170px !important" src="<?php echo esc_url(plugins_url('/wolfiz-solar-estimator/includes/assets/images/1.jpg')); ?>">
          </label>
          <label class="alig_items save_radio_val">
              <input type="radio" class="radio_close" name="test" value="Other">
              <img width="170px !important" height="170px !important" src="<?php echo esc_url(plugins_url('/wolfiz-solar-estimator/includes/assets/images/other.png')); ?>">
          </label>
      </div>
      <div class="display_items"> 
      <?php
  }elseif($state=='MN'){
    ?>
        <label class="alig_items save_radio_val">
                  <input type="radio" class="radio_close" name="test" value="Ameren">
                  <img width="170px !important" height="170px !important" src="<?php echo esc_url(plugins_url('/wolfiz-solar-estimator/includes/assets/images/6.jpg')); ?>">
              </label>
              <label class="alig_items save_radio_val">
                  <input type="radio" class="radio_close" name="test" value="Illinois Power">
                  <img width="170px !important" height="170px !important" src="<?php echo esc_url(plugins_url('/wolfiz-solar-estimator/includes/assets/images/5.jpg')); ?>">
              </label>
              <label class="alig_items save_radio_val">
                  <input type="radio" class="radio_close" name="test" value="Champion Energy">
                  <img width="170px !important" height="170px !important" src="<?php echo esc_url(plugins_url('/wolfiz-solar-estimator/includes/assets/images/4.jpg')); ?>">
              </label>
              <label class="alig_items save_radio_val">
                  <input type="radio" class="radio_close" name="test" value="City Water Light & Power">
                  <img width="170px !important" height="170px !important" src="<?php echo esc_url(plugins_url('/wolfiz-solar-estimator/includes/assets/images/2.jpg')); ?>">
              </label>
              <label class="alig_items save_radio_val">
                  <input type="radio" class="radio_close" name="test" value="ComEd">
                  <img width="170px !important" height="170px !important" src="<?php echo esc_url(plugins_url('/wolfiz-solar-estimator/includes/assets/images/8.jpg')); ?>">
              </label>
          </div>
          <div class="display_items_next">  
            <label class="alig_items save_radio_val">
              <input type="radio" class="radio_close" name="test" value="Direct Energy">
              <img width="170px !important" height="170px !important" src="<?php echo esc_url(plugins_url('/wolfiz-solar-estimator/includes/assets/images/1.jpg')); ?>">
          </label>
          <label class="alig_items save_radio_val">
              <input type="radio" class="radio_close" name="test" value="Other">
              <img width="170px !important" height="170px !important" src="<?php echo esc_url(plugins_url('/wolfiz-solar-estimator/includes/assets/images/other.png')); ?>">
          </label>
      </div>
      <div class="display_items"> 
      <?php
  }else{
    ?>
        <label class="alig_items save_radio_val">
                  <input type="radio" class="radio_close" name="test" value="Ameren">
                  <img width="170px !important" height="170px !important" src="<?php echo esc_url(plugins_url('/wolfiz-solar-estimator/includes/assets/images/6.jpg')); ?>">
              </label>
              <label class="alig_items save_radio_val">
                  <input type="radio" class="radio_close" name="test" value="Illinois Power">
                  <img width="170px !important" height="170px !important" src="<?php echo esc_url(plugins_url('/wolfiz-solar-estimator/includes/assets/images/5.jpg')); ?>">
              </label>
              <label class="alig_items save_radio_val">
                  <input type="radio" class="radio_close" name="test" value="Champion Energy">
                  <img width="170px !important" height="170px !important" src="<?php echo esc_url(plugins_url('/wolfiz-solar-estimator/includes/assets/images/4.jpg')); ?>">
              </label>
              <label class="alig_items save_radio_val">
                  <input type="radio" class="radio_close" name="test" value="City Water Light & Power">
                  <img width="170px !important" height="170px !important" src="<?php echo esc_url(plugins_url('/wolfiz-solar-estimator/includes/assets/images/2.jpg')); ?>">
              </label>
              <label class="alig_items save_radio_val">
                  <input type="radio" class="radio_close" name="test" value="ComEd">
                  <img width="170px !important" height="170px !important" src="<?php echo esc_url(plugins_url('/wolfiz-solar-estimator/includes/assets/images/8.jpg')); ?>">
              </label>
          </div>
          <div class="display_items_next">  
            <label class="alig_items save_radio_val">
              <input type="radio" class="radio_close" name="test" value="Direct Energy">
              <img width="170px !important" height="170px !important" src="<?php echo esc_url(plugins_url('/wolfiz-solar-estimator/includes/assets/images/1.jpg')); ?>">
          </label>
          <label class="alig_items save_radio_val">
              <input type="radio" class="radio_close" name="test" value="Other">
              <img width="170px !important" height="170px !important" src="<?php echo esc_url(plugins_url('/wolfiz-solar-estimator/includes/assets/images/other.png')); ?>">
          </label>
      </div>
      <div class="display_items"> 
      <?php
  }
  ?>
</div>
<?php
die;
}
public function wf_solar_global_constents_vars() {

    if ( ! defined( 'WFSOLAREST_URL' ) ) {
        define( 'WFSOLAREST_URL', plugin_dir_url( __FILE__ ) );
    }

    if ( ! defined( 'WFSOLAREST_BASENAME' ) ) {
        define( 'WFSOLAREST_BASENAME', plugin_basename( __FILE__ ) );
    }

    if ( ! defined( 'WFSOLAREST_PLUGIN_DIR' ) ) {
        define( 'WFSOLAREST_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
    }
}

public function wf_cronjob_time_interval_solar( $schedules )
{
    $wf_solar_cron_time_sec = 86400;
    $schedules['wf_solar_cron_time'] = array(
        'interval' => $wf_solar_cron_time_sec
    );
    return $schedules;
}

public function wf_cronjob_time_callback_solar()
{
    if ( ! wp_next_scheduled( 'solar_cron_job_time_update' ) ) {
        wp_schedule_event( time() , 'wf_solar_cron_time', 'solar_cron_job_time_update' );
    }
}

public function wf_start_cronjob_solar()
{
    if(empty(get_option('wf_get_real_time_Co2'))){
        $real_time_Co2 = 6541640;
        update_option('wf_get_real_time_Co2',$real_time_Co2);
    }else{
        $real_time_Co2=get_option('wf_get_real_time_Co2')+3.7;
        update_option('wf_get_real_time_Co2',$real_time_Co2);
    }

    if(empty(get_option('wf_get_real_time_mwh'))){
        $real_time_mwh = 9.02;
        update_option('wf_get_real_time_mwh',$real_time_mwh);
    }else{
        $real_time_mwh=get_option('wf_get_real_time_mwh')+0.00743;
        update_option('wf_get_real_time_mwh',$real_time_mwh);
    }

    if(empty(get_option('wf_get_real_time_auto_miles'))){
       $real_time_auto_miles = 16237695183;
       update_option('wf_get_real_time_auto_miles',$real_time_auto_miles);
   }else{
    $real_time_auto_miles=get_option('wf_get_real_time_auto_miles')+9184;
    update_option('wf_get_real_time_auto_miles',$real_time_auto_miles);
}
}

public function wf_add_submenu_solar_est() {
    
    add_menu_page(
            __('Solar Estimator'), // the page title
            __('Solar Estimator'), //menu title
            'manage_options', //capability 
            'wf_solar_calc', //menu slug/handle this is what you need!!!
            array($this,'wf_solar_ecg_calculations_callback'), //callback function
            plugins_url( 'assets/', __FILE__ ), //icon_url,
            '2'//position
        );
    
    add_submenu_page(
        'wf_solar_calc',
                'ECG Leads', //page title
                'ECG Leads', //menu title
                'manage_options', //capability,
                'wf_solar_ent',//menu slug
                array($this, 'wf_solar_ecg_entries_callback'), //callback function
            );
    
}

public function wf_solar_ecg_calculations_callback(){
 if ( isset( $_POST['akpd-ajax-nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['akpd-ajax-nonce'], 'akpd-ajax-nonce_nonce_action' ) ) ) ) {
   print 'Sorry, your nonce did not verify.';
   exit;
}
if (isset($_GET['tab'])) {
   $active_tab = sanitize_text_field( wp_unslash( $_GET['tab'] ) );
} else {
    $active_tab ='wf_solar_calculations_tab';
}
?>
<div class="wrap woocommerce">
    <h2><?php echo esc_html__( 'Settings', 'wf_image_api' ); ?>
</h2>
<?php settings_errors(); ?> 
<h2 class="nav-tab-wrapper">                                        
    <a href="?page=wf_solar_calc&tab=wf_solar_calculations_tab" class="nav-tab <?php echo esc_attr( $active_tab ) === 'wf_solar_calculations_tab' ? 'nav-tab-active' : ''; ?>">
       <?php
       echo esc_html__( 'Setting', 'wf_image_api' );
       ?>
   </a>                                     
</h2>
</div>
<form method="post" action="options.php">  
 <?php
 if ( 'wf_solar_calculations_tab' === $active_tab ) {

    settings_fields( 'wf_solar_calculations_page' );
    do_settings_sections( 'wf_calculations_register_settings_section' );
    ?>
    <input type="submit" name="save_setting" class="button-primary" value="Save Setting">
    <?php

}
?>
</form>
<?php       
}

public function wf_soalr_calculation_register_settings(){
 add_settings_section(
                'wf_solar_calc', //page_name
                '',
                'wf_solar_calculations_general_callback',
                'wf_calculations_register_settings_section' //do_settings_sections
            );
 function wf_solar_calculations_general_callback() {

 }

 add_settings_field(
            'wf_solar_iowa_rate', //get_option name
            __( 'Iowa/Others Rate', 'wf_image_api' ), //Field Label
            'wf_iowa_rate_callback', //Field Callback
            'wf_calculations_register_settings_section', //do_settings_sections
            'wf_solar_calc' //page_name
     );
 register_setting(
            'wf_solar_calculations_page', //settings_fields
            'wf_solar_iowa_rate'  //get_option name
     );

 function wf_iowa_rate_callback() {
    ?>
    <input type="number" step="any" class="iowa_rate" name="wf_solar_iowa_rate" value="<?php echo esc_attr(get_option('wf_solar_iowa_rate') ); ?>" ><span><b> $ </b></span>
    <!-- <p><?php echo esc_html__('Add electicity rate of Iowa/others states.', 'wf_image_api'); ?></p> -->
    <?php

}

add_settings_field(
            'wf_solar_illinious_rate', //get_option name
            __( 'Illinious Rate', 'wf_image_api' ), //Field Label
            'wf_illinious_rate_callback', //Field Callback
            'wf_calculations_register_settings_section', //do_settings_sections
            'wf_solar_calc' //page_name
        );
register_setting(
            'wf_solar_calculations_page', //settings_fields
            'wf_solar_illinious_rate'  //get_option name
        );

function wf_illinious_rate_callback() {
    ?>
    <input type="number" step="any"class="illinious_rate" name="wf_solar_illinious_rate" value="<?php echo esc_attr(get_option('wf_solar_illinious_rate') ); ?>" ><span><b> $ </b></span>
    <!-- <p><?php echo esc_html__('Add Illinious electicity rate.', 'wf_image_api'); ?></p> -->
    <?php

}

add_settings_field(
            'wf_solar_wisconsin_rate', //get_option name
            __( 'Wisconsin Rate', 'wf_image_api' ), //Field Label
            'wf_wisconsin_rate_callback', //Field Callback
            'wf_calculations_register_settings_section', //do_settings_sections
            'wf_solar_calc' //page_name
        );
register_setting(
            'wf_solar_calculations_page', //settings_fields
            'wf_solar_wisconsin_rate'  //get_option name
        );

function wf_wisconsin_rate_callback() {
    ?>
    <input type="number" step="any"class="wisconsin_rate" name="wf_solar_wisconsin_rate" value="<?php echo esc_attr(get_option('wf_solar_wisconsin_rate') ); ?>" ><span><b> $ </b></span>
    <!-- <p><?php echo esc_html__('Add Wisconsin electicity rate.', 'wf_image_api'); ?></p> -->
    <?php

}

add_settings_field(
            'wf_solar_minnesota_rate', //get_option name
            __( 'Minnesota Rate', 'wf_image_api' ), //Field Label
            'wf_minnesota_rate_callback', //Field Callback
            'wf_calculations_register_settings_section', //do_settings_sections
            'wf_solar_calc' //page_name
        );
register_setting(
            'wf_solar_calculations_page', //settings_fields
            'wf_solar_minnesota_rate'  //get_option name
        );

function wf_minnesota_rate_callback() {
    ?>
    <input type="number" step="any"class="minnesota_rate" name="wf_solar_minnesota_rate" value="<?php echo esc_attr(get_option('wf_solar_minnesota_rate') ); ?>" ><span><b> $ </b></span>
    <!-- <p><?php echo esc_html__('Add Minnesota electicity rate.', 'wf_image_api'); ?></p> -->
    <?php

}

add_settings_field(
            'wf_solar_watts_per_panel', //get_option name
            __( 'Watts per Panel', 'wf_image_api' ), //Field Label
            'wf_watts_per_panel_callback', //Field Callback
            'wf_calculations_register_settings_section', //do_settings_sections
            'wf_solar_calc' //page_name
        );
register_setting(
            'wf_solar_calculations_page', //settings_fields
            'wf_solar_watts_per_panel'  //get_option name
        );

function wf_watts_per_panel_callback() {
    ?>
    <input type="number" step="any" class="watts_per_panel" name="wf_solar_watts_per_panel" value="<?php echo esc_attr(get_option('wf_solar_watts_per_panel') ); ?>" ><span><b> Watts/Panel </b></span>
    <!-- <p><?php echo esc_html__('Add watts per panel.', 'wf_image_api'); ?></p> -->
    <?php

}

// add_settings_field(
//             'wf_solar_annual_kwh', //get_option name
//             __( 'Annual KWH Value', 'wf_image_api' ), //Field Label
//             'wf_annual_kwh_callback', //Field Callback
//             'wf_calculations_register_settings_section', //do_settings_sections
//             'wf_solar_calc' //page_name
//         );
// register_setting(
//             'wf_solar_calculations_page', //settings_fields
//             'wf_solar_annual_kwh'  //get_option name
//         );

function wf_annual_kwh_callback() {
    ?>
    <input type="number" step="any" class="annual_kwh" name="wf_solar_annual_kwh" value="<?php echo esc_attr(get_option('wf_solar_annual_kwh') ); ?>"><span><b> KWH </b></span>
    <p><?php echo esc_html__('Add value to calculate annual KWH.', 'wf_image_api'); ?></p>
    <?php

}

add_settings_field(
            'wf_solar_coefficient_system_cost', //get_option name
            __( 'KWH Entry Method', 'wf_image_api' ), //Field Label
            'wf_coefficient_system_cost_callback', //Field Callback
            'wf_calculations_register_settings_section', //do_settings_sections
            'wf_solar_calc' //page_name
        );
register_setting(
            'wf_solar_calculations_page', //settings_fields
            'wf_solar_coefficient_system_cost'  //get_option name
        );

function wf_coefficient_system_cost_callback() {
    ?>
    <input type="number" step="any" class="coefficient_system_cost" name="wf_solar_coefficient_system_cost" value="<?php echo esc_attr(get_option('wf_solar_coefficient_system_cost') ); ?>">
    <p><?php echo esc_html__('Add coefficient value of the System Cost Formula', 'wf_image_api'); ?></p>
    <?php

}
add_settings_field(
            'wf_solar_d_rate_factor', //get_option name
            __( 'KW Powerplant Size D Rate Factor (Bill Entry Method)', 'wf_image_api' ), //Field Label
            'wf_d_rate_factor_callback', //Field Callback
            'wf_calculations_register_settings_section', //do_settings_sections
            'wf_solar_calc' //page_name
        );
register_setting(
            'wf_solar_calculations_page', //settings_fields
            'wf_solar_d_rate_factor'  //get_option name
        );

add_settings_field(
            'wf_solar_d_rate_factor', //get_option name
            __( 'KW Powerplant Size D Rate Factor (KW Entry Method)', 'wf_image_api' ), //Field Label //TODO Change to reflect KW entry method in code
            'wf_d_rate_factor_callback', //Field Callback
            'wf_calculations_register_settings_section', //do_settings_sections
            'wf_solar_calc' //page_name
        );
register_setting(
            'wf_solar_calculations_page', //settings_fields
            'wf_solar_d_rate_factor'  //get_option name
        );

function wf_d_rate_factor_callback() {
    ?>
    <input type="number" step="any" class="d_rate_factor" name="wf_solar_d_rate_factor" value="<?php echo esc_attr(get_option('wf_solar_d_rate_factor') ); ?>" >
    <p><?php echo esc_html__('Add the value of the KW Powerplant Size D Rate Factor', 'wf_image_api'); ?></p>
    <?php

}

add_settings_field(
            'wf_solar_bill_tf_year', //get_option name
            __( '25 Year Savings Value (Bill Entry Method)', 'wf_image_api' ), //Field Label 
            'wf_bill_tf_year_callback', //Field Callback
            'wf_calculations_register_settings_section', //do_settings_sections
            'wf_solar_calc' //page_name
        );
register_setting(
            'wf_solar_calculations_page', //settings_fields
            'wf_solar_bill_tf_year'  //get_option name
        );

add_settings_field(
            'wf_solar_bill_tf_year', //get_option name
            __( '25 Year Savings Value (KW Entry Method)', 'wf_image_api' ), //Field Label //TODO Change to reflect KW entry method in code
            'wf_bill_tf_year_callback', //Field Callback
            'wf_calculations_register_settings_section', //do_settings_sections
            'wf_solar_calc' //page_name
        );
register_setting(
            'wf_solar_calculations_page', //settings_fields
            'wf_solar_bill_tf_year'  //get_option name
        );

function wf_bill_tf_year_callback() {
    ?>
    <input type="number" step="any" class="bill_tf_year" name="wf_solar_bill_tf_year" value="<?php echo esc_attr(get_option('wf_solar_bill_tf_year') ); ?>">
    <p><?php echo esc_html__('Add value of the 25 year saving for Bill entry method. Eg (25 years x 0.15)', 'wf_image_api'); ?></p>
    <?php

}

add_settings_field(
            'wf_solar_kwh_tf_year', //get_option name
            __( '25 Year Savings Value (KWH Entry Method)', 'wf_image_api' ), //Field Label
            'wf_kwh_tf_year_callback', //Field Callback
            'wf_calculations_register_settings_section', //do_settings_sections
            'wf_solar_calc' //page_name
        );
register_setting(
            'wf_solar_calculations_page', //settings_fields
            'wf_solar_kwh_tf_year'  //get_option name
        );

function wf_kwh_tf_year_callback() {
    ?>
    <input type="number" step="any" class="kwh_tf_year" name="wf_solar_kwh_tf_year" value="<?php echo esc_attr(get_option('wf_solar_kwh_tf_year') ); ?>">
    <p><?php echo esc_html__('Add value of the 25 year saving for KWH entry method. Eg (25 years x 0.15)', 'wf_image_api'); ?></p>
    <?php

}


add_settings_field(
            'wf_solar_tf_year_saving_percentage', //get_option name
            __( '25 Year Saving Percentage', 'wf_image_api' ), //Field Label
            'wf_tf_year_saving_percentage_callback', //Field Callback
            'wf_calculations_register_settings_section', //do_settings_sections
            'wf_solar_calc' //page_name
        );
register_setting(
            'wf_solar_calculations_page', //settings_fields
            'wf_solar_tf_year_saving_percentage'  //get_option name
        );

function wf_tf_year_saving_percentage_callback() {
    ?>
    <input type="number" step="any" class="tf_year_saving_percentage" name="wf_solar_tf_year_saving_percentage" value="<?php echo esc_attr(get_option('wf_solar_tf_year_saving_percentage') ); ?>" ><span><b> % </b></span>
    <p><?php echo esc_html__('Add percentage value of the 25 year saving for both entry methods', 'wf_image_api'); ?></p>
    <?php

}

add_settings_field(
            'wf_solar_cost_per_watt', //get_option name
            __( 'Cost Per Watt', 'wf_image_api' ), //Field Label
            'wf_cost_per_watt_callback', //Field Callback
            'wf_calculations_register_settings_section', //do_settings_sections
            'wf_solar_calc' //page_name
        );
register_setting(
            'wf_solar_calculations_page', //settings_fields
            'wf_solar_cost_per_watt'  //get_option name
        );

function wf_cost_per_watt_callback() {
    ?>
    <input type="number" step="any" class="cost_per_watt" name="wf_solar_cost_per_watt" value="<?php echo esc_attr(get_option('wf_solar_cost_per_watt') ); ?>" ><span><b> $ </b></span>
    
    <?php

}

add_settings_field(
            'wf_solar_federal_tax_credit', //get_option name
            __( 'Federal Tax Credit', 'wf_image_api' ), //Field Label
            'wf_federal_tax_credit_callback', //Field Callback
            'wf_calculations_register_settings_section', //do_settings_sections
            'wf_solar_calc' //page_name
        );
register_setting(
            'wf_solar_calculations_page', //settings_fields
            'wf_solar_federal_tax_credit'  //get_option name
        );

function wf_federal_tax_credit_callback() {
    ?>
    <input type="number" step="any" class="cost_per_watt" name="wf_solar_federal_tax_credit" value="<?php echo esc_attr(get_option('wf_solar_federal_tax_credit') ); ?>">
    
    <?php

}

add_settings_field(
            'wf_solar_bill_after_solar', //get_option name
            __( 'Bill After Solar Amount', 'wf_image_api' ), //Field Label
            'wf_bill_after_solar_callback', //Field Callback
            'wf_calculations_register_settings_section', //do_settings_sections
            'wf_solar_calc' //page_name
        );
register_setting(
            'wf_solar_calculations_page', //settings_fields
            'wf_solar_bill_after_solar'  //get_option name
        );

function wf_bill_after_solar_callback() {
    ?>
    <input type="number" step="any" class="bill_after_solar" name="wf_solar_bill_after_solar" value="<?php echo esc_attr(get_option('wf_solar_bill_after_solar') ); ?>" ><span><b> $ </b></span>
    <p><?php echo esc_html__('Add estimated bill after solar amount. This amount will be shown if person is selecting 100% offset.', 'wf_image_api'); ?></p>
    <?php

}

add_settings_field(
            'wf_solar_escalation_rate', //get_option name
            __( 'Escalation Rate', 'wf_image_api' ), //Field Label
            'wf_escalation_rate_callback', //Field Callback
            'wf_calculations_register_settings_section', //do_settings_sections
            'wf_solar_calc' //page_name
        );
register_setting(
            'wf_solar_calculations_page', //settings_fields
            'wf_solar_escalation_rate'  //get_option name
        );

function wf_escalation_rate_callback() {
    ?>
    <input type="number" step="any" class="escalation_rate" name="wf_solar_escalation_rate" value="<?php echo esc_attr(get_option('wf_solar_escalation_rate') ); ?>" ><span><b> % </b></span>
    <p><?php echo esc_html__('Used in Payback Period for both entry methods (Payback Period = (Total Cost After Incentives / First Year Savings) + [Escalation Rate % * (Total Cost After Incentives / First Year Savings)])', 'wf_image_api'); ?></p>
    <?php

}

add_settings_field(
            'wf_solar_payment_factor', //get_option name
            __( 'Payment Factor', 'wf_image_api' ), //Field Label
            'wf_payment_factor_callback', //Field Callback
            'wf_calculations_register_settings_section', //do_settings_sections
            'wf_solar_calc' //page_name
        );
register_setting(
            'wf_solar_calculations_page', //settings_fields
            'wf_solar_payment_factor'  //get_option name
        );

function wf_payment_factor_callback() {
    ?>
    <input type="number" step="any" class="payment_factor" name="wf_solar_payment_factor" value="<?php echo esc_attr(get_option('wf_solar_payment_factor') ); ?>" >
    <p><?php echo esc_html__('Used in Monthly Payment for both entry methods (Monthly Payment = Total Cost Of System / Payment Factor)', 'wf_image_api'); ?></p>
    <?php

}

add_settings_field(
            'wf_solar_roof_shade_south', //get_option name
            __( 'South Azimuth Derate', 'wf_image_api' ), //Field Label
            'wf_roof_shade_south_callback', //Field Callback
            'wf_calculations_register_settings_section', //do_settings_sections
            'wf_solar_calc' //page_name
        );
register_setting(
            'wf_solar_calculations_page', //settings_fields
            'wf_solar_roof_shade_south'  //get_option name
        );

function wf_roof_shade_south_callback() {
    ?>
    <input type="number" step="any" class="roof_shade_south" name="wf_solar_roof_shade_south" value="<?php echo esc_attr(get_option('wf_solar_roof_shade_south') ); ?>" ><span><b> % </b></span>
    <p><?php echo esc_html__('Used in system Size Formula For both entry methods', 'wf_image_api'); ?></p>
    <?php

}

add_settings_field(
            'wf_solar_roof_shade_south_east', //get_option name
            __( 'South East Azimuth Derate', 'wf_image_api' ), //Field Label
            'wf_roof_shade_south_east_callback', //Field Callback
            'wf_calculations_register_settings_section', //do_settings_sections
            'wf_solar_calc' //page_name
        );
register_setting(
            'wf_solar_calculations_page', //settings_fields
            'wf_solar_roof_shade_south_east'  //get_option name
        );

function wf_roof_shade_south_east_callback() {
    ?>
    <input type="number" step="any" class="roof_shade_south_east" name="wf_solar_roof_shade_south_east" value="<?php echo esc_attr(get_option('wf_solar_roof_shade_south_east') ); ?>" ><span><b> % </b></span>
    <p><?php echo esc_html__('Used in system Size Formula For both entry methods', 'wf_image_api'); ?></p>
    <?php

}

add_settings_field(
            'wf_solar_roof_shade_east', //get_option name
            __( 'East Azimuth Derate', 'wf_image_api' ), //Field Label
            'wf_roof_shade_east_callback', //Field Callback
            'wf_calculations_register_settings_section', //do_settings_sections
            'wf_solar_calc' //page_name
        );
register_setting(
            'wf_solar_calculations_page', //settings_fields
            'wf_solar_roof_shade_east'  //get_option name
        );

function wf_roof_shade_east_callback() {
    ?>
    <input type="number" step="any" class="roof_shade_east" name="wf_solar_roof_shade_east" value="<?php echo esc_attr(get_option('wf_solar_roof_shade_east') ); ?>" ><span><b> % </b></span>
    <p><?php echo esc_html__('Used in system Size Formula For both entry methods', 'wf_image_api'); ?></p>
    <?php

}

add_settings_field(
            'wf_solar_roof_shade_south_west', //get_option name
            __( 'South West Azimuth Derate', 'wf_image_api' ), //Field Label
            'wf_roof_shade_south_west_callback', //Field Callback
            'wf_calculations_register_settings_section', //do_settings_sections
            'wf_solar_calc' //page_name
        );
register_setting(
            'wf_solar_calculations_page', //settings_fields
            'wf_solar_roof_shade_south_west'  //get_option name
        );

function wf_roof_shade_south_west_callback() {
    ?>
    <input type="number" step="any" class="roof_shade_south_west" name="wf_solar_roof_shade_south_west" value="<?php echo esc_attr(get_option('wf_solar_roof_shade_south_west') ); ?>" ><span><b> % </b></span>
    <p><?php echo esc_html__('Used in system Size Formula For both entry methods', 'wf_image_api'); ?></p>
    <?php

}

add_settings_field(
            'wf_solar_roof_shade_west', //get_option name
            __( 'West Azimuth Derate', 'wf_image_api' ), //Field Label
            'wf_roof_shade_west_callback', //Field Callback
            'wf_calculations_register_settings_section', //do_settings_sections
            'wf_solar_calc' //page_name
        );
register_setting(
            'wf_solar_calculations_page', //settings_fields
            'wf_solar_roof_shade_west'  //get_option name
        );

function wf_roof_shade_west_callback() {
    ?>
    <input type="number" step="any" class="roof_shade_west" name="wf_solar_roof_shade_west" value="<?php echo esc_attr(get_option('wf_solar_roof_shade_west') ); ?>" ><span><b> % </b></span>
    <p><?php echo esc_html__('Used in system Size Formula For both entry methods', 'wf_image_api'); ?></p>
    <?php

}
//////////////////////////

add_settings_field(
            'wf_solar_roof_shade_south_production_ratio', //get_option name
            __( 'South Production Ratio (Bill entry Method)', 'wf_image_api' ), //Field Label
            'wf_roof_shade_south_production_ratio_callback', //Field Callback
            'wf_calculations_register_settings_section', //do_settings_sections
            'wf_solar_calc' //page_name
        );
register_setting(
            'wf_solar_calculations_page', //settings_fields
            'wf_solar_roof_shade_south_production_ratio'  //get_option name
        );

add_settings_field(
            'wf_solar_roof_shade_south_production_ratio', //get_option name
            __( 'South Production Ratio (KW entry Method)', 'wf_image_api' ), //Field Label //TODO Change to reflect KW entry method in code
            'wf_roof_shade_south_production_ratio_callback', //Field Callback
            'wf_calculations_register_settings_section', //do_settings_sections
            'wf_solar_calc' //page_name
        );
register_setting(
            'wf_solar_calculations_page', //settings_fields
            'wf_solar_roof_shade_south_production_ratio'  //get_option name
        );

function wf_roof_shade_south_production_ratio_callback() {
    ?>
    <input type="number" step="any" class="roof_shade_south_production_ratio" name="wf_solar_roof_shade_south_production_ratio" value="<?php echo esc_attr(get_option('wf_solar_roof_shade_south_production_ratio') ); ?>" ><span><b> % </b></span>
    <p><?php echo esc_html__('Used in Annual Production Calculation for Bill Entry Method (Annual Production = System Size x 1000 x Production Ratio)', 'wf_image_api'); ?></p>
    <?php

}

add_settings_field(
            'wf_solar_roof_shade_south_east_production_ratio', //get_option name
            __( 'South East Production Ratio (Bill entry Method)', 'wf_image_api' ), //Field Label 
            'wf_roof_shade_south_east_production_ratio_callback', //Field Callback
            'wf_calculations_register_settings_section', //do_settings_sections
            'wf_solar_calc' //page_name
        );
register_setting(
            'wf_solar_calculations_page', //settings_fields
            'wf_solar_roof_shade_south_east_production_ratio'  //get_option name
        );

add_settings_field(
            'wf_solar_roof_shade_south_east_production_ratio', //get_option name
            __( 'South East Production Ratio (KW entry Method)', 'wf_image_api' ), //Field Label //TODO Change to reflect KW entry method in code
            'wf_roof_shade_south_east_production_ratio_callback', //Field Callback
            'wf_calculations_register_settings_section', //do_settings_sections
            'wf_solar_calc' //page_name
        );
register_setting(
            'wf_solar_calculations_page', //settings_fields
            'wf_solar_roof_shade_south_east_production_ratio'  //get_option name
        );

function wf_roof_shade_south_east_production_ratio_callback() {
    ?>
    <input type="number" step="any" class="roof_shade_south_east_production_ratio" name="wf_solar_roof_shade_south_east_production_ratio" value="<?php echo esc_attr(get_option('wf_solar_roof_shade_south_east_production_ratio') ); ?>" ><span><b> % </b></span>
    <p><?php echo esc_html__('Used in Annual Production Calculation for Bill Entry Method (Annual Production = System Size x 1000 x Production Ratio)', 'wf_image_api'); ?></p>
    <?php

}

add_settings_field(
            'wf_solar_roof_shade_east_production_ratio', //get_option name
            __( 'East Production Ratio (Bill entry Method)', 'wf_image_api' ), //Field Label 
            'wf_roof_shade_east_production_ratio_callback', //Field Callback
            'wf_calculations_register_settings_section', //do_settings_sections
            'wf_solar_calc' //page_name
        );
register_setting(
            'wf_solar_calculations_page', //settings_fields
            'wf_solar_roof_shade_east_production_ratio'  //get_option name
        );

add_settings_field(
            'wf_solar_roof_shade_east_production_ratio', //get_option name
            __( 'East Production Ratio (KW entry Method)', 'wf_image_api' ), //Field Label //TODO Change to reflect KW entry method in code
            'wf_roof_shade_east_production_ratio_callback', //Field Callback
            'wf_calculations_register_settings_section', //do_settings_sections
            'wf_solar_calc' //page_name
        );
register_setting(
            'wf_solar_calculations_page', //settings_fields
            'wf_solar_roof_shade_east_production_ratio'  //get_option name
        );

function wf_roof_shade_east_production_ratio_callback() {
    ?>
    <input type="number" step="any" class="roof_shade_east_production_ratio" name="wf_solar_roof_shade_east_production_ratio" value="<?php echo esc_attr(get_option('wf_solar_roof_shade_east_production_ratio') ); ?>" ><span><b> % </b></span>
    <p><?php echo esc_html__('Used in Annual Production Calculation for Bill Entry Method (Annual Production = System Size x 1000 x Production Ratio)', 'wf_image_api'); ?></p>
    <?php

}

add_settings_field(
            'wf_solar_roof_shade_south_west_production_ratio', //get_option name
            __( 'South West Production Ratio (Bill entry Method)', 'wf_image_api' ), //Field Label 
            'wf_roof_shade_south_west_production_ratio_callback', //Field Callback
            'wf_calculations_register_settings_section', //do_settings_sections
            'wf_solar_calc' //page_name
        );
register_setting(
            'wf_solar_calculations_page', //settings_fields
            'wf_solar_roof_shade_south_west_production_ratio'  //get_option name
        );

add_settings_field(
            'wf_solar_roof_shade_south_west_production_ratio', //get_option name
            __( 'South West Production Ratio (KW entry Method)', 'wf_image_api' ), //Field Label //TODO Change to reflect KW entry method in code
            'wf_roof_shade_south_west_production_ratio_callback', //Field Callback
            'wf_calculations_register_settings_section', //do_settings_sections
            'wf_solar_calc' //page_name
        );
register_setting(
            'wf_solar_calculations_page', //settings_fields
            'wf_solar_roof_shade_south_west_production_ratio'  //get_option name
        );

function wf_roof_shade_south_west_production_ratio_callback() {
    ?>
    <input type="number" step="any" class="roof_shade_south_west_production_ratio" name="wf_solar_roof_shade_south_west_production_ratio" value="<?php echo esc_attr(get_option('wf_solar_roof_shade_south_west_production_ratio') ); ?>" ><span><b> % </b></span>
    <p><?php echo esc_html__('Used in Annual Production Calculation for Bill Entry Method (Annual Production = System Size x 1000 x Production Ratio)', 'wf_image_api'); ?></p>
    <?php

}

add_settings_field(
            'wf_solar_roof_shade_west_production_ratio', //get_option name
            __( 'West Production Ratio (Bill entry Method)', 'wf_image_api' ), //Field Label
            'wf_roof_shade_west_production_ratio_callback', //Field Callback
            'wf_calculations_register_settings_section', //do_settings_sections
            'wf_solar_calc' //page_name
        );
register_setting(
            'wf_solar_calculations_page', //settings_fields
            'wf_solar_roof_shade_west_production_ratio'  //get_option name
        );

add_settings_field(
            'wf_solar_roof_shade_west_production_ratio', //get_option name
            __( 'West Production Ratio (KW entry Method)', 'wf_image_api' ), //Field Label //TODO Change to reflect KW entry method in code
            'wf_roof_shade_west_production_ratio_callback', //Field Callback
            'wf_calculations_register_settings_section', //do_settings_sections
            'wf_solar_calc' //page_name
        );
register_setting(
            'wf_solar_calculations_page', //settings_fields
            'wf_solar_roof_shade_west_production_ratio'  //get_option name
        );

function wf_roof_shade_west_production_ratio_callback() {
    ?>
    <input type="number" step="any" class="roof_shade_west_production_ratio" name="wf_solar_roof_shade_west_production_ratio" value="<?php echo esc_attr(get_option('wf_solar_roof_shade_west_production_ratio') ); ?>" ><span><b> % </b></span>
    <p><?php echo esc_html__('Used in Annual Production Calculation for Bill Entry Method (Annual Production = System Size x 1000 x Production Ratio)', 'wf_image_api'); ?></p>
    <?php

}
}

public  function wf_solar_ecg_entries_callback() {
 global $wpdb;
 $sql2 = "SELECT * FROM `wp_fluentform_submissions` WHERE `form_id` = 10 ORDER BY `id` DESC";
 $result = $wpdb->get_results ( $sql2 );
 ?>
 <style>
   .first_row_table{
       background-color: black;
       color: white;
       height: 30px;
   }
   .center_div{
      margin-top:1%;
      padding:1%;
      width:98% !important;
  }
  .af_sm_table{
   background-color: white;
   font-family: arial, sans-serif;
   border-collapse: collapse;
   width: 80%;
   margin: 0 auto;
}
.af_sm_table th,.af_sm_table td{
    text-align: left !important;
    padding: 10px !important;
}
.af_sm_table tr{
    border-bottom: 1px solid #80808052;
}

.af_sm_table tr:last-child{
    border: 0;
}
select[name='solar_table_length']{
  width:50px !important;
}
</style>
<div class="center_div">
    <table class="af_sm_table" id="solar_table">
       <thead>
           <tr class="first_row_table">
              <th>Id</th>
              <th>Status</th>
              <th>Date</th>
              <th>Contact/Don't Contact</th>
          </tr>
      </thead>
      <tbody>
       <?php
       foreach($result as $s_entry){
        
        ?>
        <tr>
          <td> <a target="_blank" href="<?php echo esc_url(get_admin_url().'admin.php?page=fluent_forms&route=entries&form_id=10#/entries/'.$s_entry->id); ?>"><?php echo $s_entry->id; ?></a></td>
          <td><?php echo $s_entry->status; ?></td>
          <td><?php echo $s_entry->updated_at; ?></td>
          <td><?php $resp=$s_entry->response;
          $resp_explode=explode(',',$resp);
          $data_call=explode(':',$resp_explode[20]);
          $data_fn=str_replace('"', "", $data_call[1]);
		   
          if($data_fn=="Don't contact me"){
           echo "<span style='color:red;font-weight:bold;'>".$data_fn."</span>";
       }else{
           echo "<span style='color:green;font-weight:bold;'>".$data_fn."</span>";
       }
   ?></td>
</tr>
<?php
}
?>
</tbody>
</table>
</div>
<?php
}


}

new Wf_Solar_Est_Main();
}