<?php
/**
 *
 */
class Front_Class
{

    function __construct()
    {
        // $url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        // Parse the URL to extract the GET parameters
        // $parts = parse_url($url);
        // $url_path = $parts['path'];
        // if($url_path == "/solar-calculator-final-page/"){
            // if(array_key_exists("query", $parts)){
            // parse_str($parts['query'], $query);
            add_shortcode('solar_install_map' , array($this , "wf_solar_install_map"));
        if(isset($_GET["state"])){
            //this is a comment
            add_shortcode('solar_panels' , array($this , "wf_solar_panels"));
            add_shortcode('solar_system_size' , array($this , "wf_solar_system_size"));
            add_shortcode('solar_annual_production' , array($this , "wf_solar_annual_production"));
            add_shortcode('solar_yr_savings' , array($this , "wf_solar_yr_savings"));
            add_shortcode('solar_system_prices' , array($this , "wf_solar_system_prices"));
            add_shortcode('solar_monthly_saving' , array($this , "wf_solar_montly_bill_savings"));
            add_shortcode('solar_bill_saving_twentyfive_year' , array($this , "wf_solar_bill_saving_twentyfive_year"));
            add_shortcode('solar_saving_and_return' , array($this , "wf_solar_saving_and_return"));
            add_shortcode('solar_cash_flow_twentyfive_year' , array($this , "wf_solar_cash_flow_twentyfive_year"));
            add_shortcode('solar_system_cost' , array($this , "wf_solar_system_cost"));
            //add_shortcode('solar_system_cost' , array($this , "wf_solar_system_cost_next"));
            add_shortcode('solar_total_saving_return' , array($this , "wf_solar_total_saving_return"));
        }
        // }
        // }

        add_shortcode('solar_real_time_mwh' , array($this , "wf_solar_real_time_mwh"));
        add_shortcode('solar_real_time_Co2_offset' , array($this , "wf_solar_real_time_Co2_offset"));
        add_shortcode('solar_carbon_equivalet_auto_miles' , array($this , "wf_carbon_equivalet_auto_miles"));
        add_action( 'wp_enqueue_scripts', array( $this, 'wf_solar_front_script' ), 10 );

    }


    public function wf_solar_front_script() {
            // Enqueue Scripts.
        wp_enqueue_script( 'wf-solar-est-front', plugins_url( 'assets/js/wf_solar_est_front_js.js', __FILE__ ), array( 'jquery' ), '1.0.8', false );
        wp_enqueue_script( 'googlegraph', 'https://www.gstatic.com/charts/loader.js', '0.5', true);
        wp_enqueue_script( 'googlegraph', 'https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js', '0.5', true);
        wp_enqueue_script( 'jquery' );
          //  wp_enqueue_style( 'wf-solar-est-front', plugins_url( 'assets/css/wf_solar_est_admin_css.css', _FILE_ ), false, '1.0' );

            // Localize the variables.
        $base_url=get_bloginfo('wpurl');
        $wf_solar_est_data = array(
            'admin_url' => admin_url( 'admin-ajax.php' ),
            'nonce'     => wp_create_nonce( 'solar-est-ajax-nonce' ),
            'base_url'  =>$base_url,
        );
        wp_localize_script( 'wf-solar-est-front', 'solar_est_php_vars', $wf_solar_est_data );
    }


    public function wf_solar_install_map(){
        $auto_address = $_GET["autocomplete"];
        ?>
      <!--   <style>
            #wf_est_googleMap_last{
                width:550px;
                height:550px;
            }
            @media only screen and (max-width: 768px) {

                #wf_est_googleMap_last{
                    width:482px;
                    height:550px;
                }
            }
        </style> -->
        <input type="hidden" id="get_address" name="get_address" value="<?php echo $auto_address; ?>">
        <div id="wf_est_googleMap_last"></div>
        <?php
    }
    public function wf_solar_total_saving_return(){
        global $wpdb;
        $bill_cost =trim($_GET["billcost"]);
        $state = trim($_GET["state"]);
        if($state == 'IL'){
            $ind_rate = esc_attr(get_option('wf_solar_illinious_rate') );
            if (empty($ind_rate)) {
                $ind_rate = "0.1295";
            }
        }elseif($state == 'WI'){
            $ind_rate = esc_attr(get_option('wf_solar_wisconsin_rate') );
            if (empty($ind_rate)) {
                $ind_rate = "0.163";
            }
        }elseif($state == 'MN'){
            $ind_rate = esc_attr(get_option('wf_solar_minnesota_rate') );
            if (empty($ind_rate)) {
                $ind_rate = "0.163";
            }
        }else{
            $ind_rate = esc_attr(get_option('wf_solar_iowa_rate') );
            if (empty($ind_rate)) {
                $ind_rate = "0.1274";
            }
        }

        $roof_shaded =trim($_GET["roof_shaded"]);
        if (empty($roof_shaded)) {
            $roof_shaded= 0.5;
        }
        $kwh_offset =trim($_GET["kwh_offset"]);
        if (empty($kwh_offset)) {
            $kwh_offset= 0.1;
        }
        //$shaded_value = $roof_shaded / 100; /*TODO: try this at some point */
        $shaded_value = $roof_shaded/100;
        // if("10" == $roof_shaded){
        //     $shaded_value = 1.1;
        // }elseif("20" == $roof_shaded){
        //     $shaded_value = 1.2;
        // }elseif("30" == $roof_shaded){
        //     $shaded_value = 1.3;
        // }elseif("40" == $roof_shaded){
        //     $shaded_value = 1.4;
        // }elseif("50" == $roof_shaded){
        //     $shaded_value = 1.5;
        // }elseif("60" == $roof_shaded){
        //     $shaded_value = 1.6;
        // }elseif("70" == $roof_shaded){
        //     $shaded_value = 1.7;
        // }else{
        //     $shaded_value = 0;
        // }
        $offset_value = $kwh_offset / 100; /*TODO: try this at some point */

        // if("10" == $kwh_offset){
        //     $offset_value = 0.1;
        // }elseif("20" == $kwh_offset){
        //     $offset_value = 0.2;
        // }elseif("30" == $kwh_offset){
        //     $offset_value = 0.3;
        // }elseif("40" == $kwh_offset){
        //     $offset_value = 0.4;
        // }elseif("50" == $kwh_offset){
        //     $offset_value = 0.5;
        // }elseif("60" == $kwh_offset){
        //     $offset_value = 0.6;
        // }elseif("70" == $kwh_offset){
        //     $offset_value = 0.7;
        // }elseif("80" == $kwh_offset){
        //     $offset_value = 0.8;
        // }elseif("90" == $kwh_offset){
        //     $offset_value = 0.9;
        // }elseif("100" == $kwh_offset){
        //     $offset_value = 1.0;
        // }

        $roof_direction = trim($_GET["roof_direction"]);

        if("South" == $roof_direction){
            $roof_shade_south = esc_attr(get_option('wf_solar_roof_shade_south') );
            if ($roof_shade_south == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_south/100;
            }
        }elseif("South_East" == $roof_direction){
            $roof_shade_south_east = esc_attr(get_option('wf_solar_roof_shade_south_east') );
            if ($roof_shade_south_east == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_south_east/100;
            }
        }elseif("East" == $roof_direction){
            $roof_shade_east = esc_attr(get_option('wf_solar_roof_shade_east') );
            if ($roof_shade_east == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_east/100;
            }
        }elseif("South_West" == $roof_direction){
            $roof_shade_south_west = esc_attr(get_option('wf_solar_roof_shade_south_west') );
            if ($roof_shade_south_west == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_south_west/100;
            }
        }elseif("West" == $roof_direction){
            $roof_shade_west = esc_attr(get_option('wf_solar_roof_shade_west') );
            if ($roof_shade_west == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_west/100;
            }
        }
        $federal_tax_credit_value = esc_attr(get_option('wf_solar_federal_tax_credit') );
        $cost_per_watt = esc_attr(get_option('wf_solar_cost_per_watt') );
        $bill_tf_year_saving_value = esc_attr(get_option('wf_solar_bill_tf_year') );
        $kwh_tf_year_saving_value = esc_attr(get_option('wf_solar_kwh_tf_year') );
        //start mp insert
        if("South" == $roof_direction){
            $roof_shade_south = esc_attr(get_option('wf_solar_roof_shade_south') );
            if ($roof_shade_south == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_south/100;
            }
            $roof_shade_south_production_rate = esc_attr(get_option('wf_solar_roof_shade_south_production_ratio') );
            if ($roof_shade_south_production_rate == " ") {
                $roof_direction_ratio = 0;
            }else{
                $roof_direction_ratio = $roof_shade_south_production_rate/100;
            }
        }elseif("South_East" == $roof_direction){
            $roof_shade_south_east = esc_attr(get_option('wf_solar_roof_shade_south_east') );
            if ($roof_shade_south_east == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_south_east/100;
            }
            $roof_shade_south_east_production_ratio = esc_attr(get_option('wf_solar_roof_shade_south_east_production_ratio') );
            if ($roof_shade_south_east_production_ratio == " ") {
                $roof_direction_ratio = 0;
            }else{
                $roof_direction_ratio = $roof_shade_south_east_production_ratio/100;
            }
        }elseif("East" == $roof_direction){
            $roof_shade_east = esc_attr(get_option('wf_solar_roof_shade_east') );
            if ($roof_shade_east == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_east/100;
            }
            $roof_shade_east_production_ratio = esc_attr(get_option('wf_solar_roof_shade_east_production_ratio') );
            if ($roof_shade_east_production_ratio == " ") {
                $roof_direction_ratio = 0;
            }else{
                $roof_direction_ratio = $roof_shade_east_production_ratio/100;
            }
        }elseif("South_West" == $roof_direction){
            $roof_shade_south_west = esc_attr(get_option('wf_solar_roof_shade_south_west') );
            if ($roof_shade_south_west == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_south_west/100;
            }
            $roof_shade_south_west_production_ratio = esc_attr(get_option('wf_solar_roof_shade_south_west_production_ratio') );
            if ($roof_shade_south_west_production_ratio == " ") {
                $roof_direction_ratio = 0;
            }else{
                $roof_direction_ratio = $roof_shade_south_west_production_ratio/100;
            }
        }elseif("West" == $roof_direction){
            $roof_shade_west = esc_attr(get_option('wf_solar_roof_shade_west') );
            if ($roof_shade_west == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_west/100;
            }
            $roof_shade_west_production_ratio = esc_attr(get_option('wf_solar_roof_shade_west_production_ratio') );
            if ($roof_shade_west_production_ratio == " ") {
                $roof_direction_ratio = 0;
            }else{
                $roof_direction_ratio = $roof_shade_west_production_ratio/100;
            }
        }
        //end MP insert
        $tf_year_saving_percent = esc_attr(get_option('wf_solar_tf_year_saving_percentage') );
        $coefficient_system_cost = esc_attr(get_option('wf_solar_coefficient_system_cost') );
        $d_rate_factor = esc_attr(get_option('wf_solar_d_rate_factor') );

        $check_btn_click =trim($_GET["monthly_kwh"]);
        if($check_btn_click == "NULL"){
            $bill_cost =trim($_GET["billcost"]);
            if (empty($bill_cost)) {
                $bill_cost = "500";
            }
            $tf_year_saving_percent_calc = $tf_year_saving_percent/100;
            $kw_per_month = ($bill_cost-15)/$ind_rate;
            $annual_kwh = $kw_per_month*12;
            $ak_x_sv = $annual_kwh*$shaded_value;
            $ak_x_rdv = $annual_kwh*$roof_direction_value;
            $ak_sv_rdv = $annual_kwh + $ak_x_sv + $ak_x_rdv;
            $ak_sv_rdv_x_ov = $ak_sv_rdv*$offset_value;
            $system_size_kwh = $ak_sv_rdv_x_ov/365/4.5/$d_rate_factor;
            $average_system_cost = ceil($cost_per_watt*($system_size_kwh*1000));
            $federal_tax_credit = $average_system_cost*$federal_tax_credit_value;
            $cost_after_incentives = ceil($average_system_cost-$federal_tax_credit);

            //$annual_production = ceil($annual_kwh);  //original
            //start MP changes
            $annual_production_calc = ($system_size_kwh*1000)*$roof_direction_ratio;
            $annual_production = ceil($annual_production_calc);
            //end MP changes
            $annual_production_update = $annual_production;
            $zero_point_one_five = $annual_production_update*$bill_tf_year_saving_value;
            $three_percent = $zero_point_one_five*$tf_year_saving_percent_calc;
            $annual_production_sum = $zero_point_one_five+$three_percent;
            $annual_production_update = $annual_production_sum;
            $total=0;
            for($i=1; $i<26; $i++){
                $three_percent_loop = $annual_production_update*$tf_year_saving_percent_calc;
                $annual_production_update = $annual_production_update+$three_percent_loop;
                $total+=$annual_production_update;
            }

            $total_minus_incentives = $total-$cost_after_incentives;
            $total_minus_by_incentives = $total_minus_incentives/$cost_after_incentives;
            $return_on_invest = $total_minus_by_incentives*100;
            $return_on_invest_round = round($return_on_invest , 2);
        }else{
            $monthly_kwh =trim($_GET["monthly_kwh"]);
            $tf_year_saving_percent_calc = $tf_year_saving_percent/100;
            $annual_kwh = $monthly_kwh*12;
            $ak_x_sv = $annual_kwh*$shaded_value;
            $ak_x_rdv = $annual_kwh*$roof_direction_value;
            $ak_sv_rdv = $annual_kwh + $ak_x_sv + $ak_x_rdv;
            $ak_sv_rdv_x_ov = $ak_sv_rdv*$offset_value;
            $system_size_kwh = $ak_sv_rdv_x_ov/365/4.5/$coefficient_system_cost;
            $average_system_cost = ceil($cost_per_watt*($system_size_kwh*1000));
            $federal_tax_credit = $average_system_cost*$federal_tax_credit_value;
            $cost_after_incentives = ceil($average_system_cost-$federal_tax_credit);
            $annual_production = ceil($annual_kwh);
            $annual_production_update = $annual_production;
            $zero_point_one_five = $annual_production_update*$kwh_tf_year_saving_value;
            $three_percent = $zero_point_one_five*$tf_year_saving_percent_calc;
            $annual_production_sum = $zero_point_one_five+$three_percent;
            $annual_production_update = $annual_production_sum;
            $total=0;
            for($i=1; $i<26; $i++){
                $three_percent_loop = $annual_production_update*$tf_year_saving_percent_calc;
                $annual_production_update = $annual_production_update+$three_percent_loop;
                $total+=$annual_production_update;
            }

            $total_minus_incentives = $total-$cost_after_incentives;
            $total_minus_by_incentives = $total_minus_incentives/$cost_after_incentives;
            $return_on_invest = $total_minus_by_incentives*100;
            $return_on_invest_round = round($return_on_invest , 2);
        }
        ?>
        <style>
            .padding_text{
                padding:1%;
            }
            .flex_div{
                display:flex;
                margin-bottom:2%;
            }
            .title{
                width:20%;
            }
            .title_2{
                width:100%;
            }
        </style>
        <div class="flex_div">
            <div class="title">
                <?php
                echo "Electric Bill Saving over 25 years:";
                ?>
            </div>
            <div style="background-color: #2eaae1; width:100%" class="padding_text">
                <p style="margin-left: 10px; color: white;"><?php echo " <b> $".number_format(ceil($total)). "</b>"; ?></p> 
            </div>
        </div>
        <div class="flex_div">
            <div class="title">
                <?php
                echo "Total Return on Investment:";
                ?>
            </div>
            <div style=" width:100%" >
                <p class="simple_title" style="margin-left: 10px !important; margin: 0px;"><?php echo "<b> ".$return_on_invest_round ."% </b>"; ?></p> 
            </div>
        </div>
        <?php
    }

    public function wf_solar_real_time_mwh(){
        $real_time_mwh=get_option('wf_get_real_time_mwh');
        ?>
        <div class="real_time_label">
            <div class="real_time_value">
                <p class="real_p_value"><?php echo $real_time_mwh; ?> Gwh</p>
                <p class="real_p_text">Produced</p>

            </div>
            <div class="real_time_title">
                <?php
                echo "ECG Solar Real Time Mwh";
                ?>
            </div>

        </div>
        <?php
    }
    public function wf_solar_real_time_Co2_offset(){
        $real_time_Co2=get_option('wf_get_real_time_Co2')
        ?>
        <div class="real_time_label">
            <div class="real_time_value">
                <p class="real_p_value"><?php echo number_format($real_time_Co2, 2, ".", ","); ?> CO2</p>
                <p class="real_p_text">Offset (Metric Tons)</p> 
            </div>
            <div class="real_time_title">
                <?php
                echo "ECG Solar Real Time Co2 Offset";
                ?>
            </div>

        </div>
        <?php
    }

    public function wf_carbon_equivalet_auto_miles(){
        $real_time_auto_miles=get_option('wf_get_real_time_auto_miles')
        ?>
        <div class="real_time_label">

            <div class="real_time_value">
                <p class="real_p_value"><?php echo number_format($real_time_auto_miles, 2, ".", ","); ?></p>
                <p class="real_p_text">Auto Miles Saved</p>
            </div>
            <div class="real_time_title">
                <?php
                echo "ECG Solar Real Time Co2 Offset";
                ?>
            </div>
        </div>

        <?php
    }

    public function wf_solar_system_cost(){
        global $wpdb;
        $state = trim($_GET["state"]);
        if($state == 'IL'){
            $ind_rate = esc_attr(get_option('wf_solar_illinious_rate') );
            if (empty($ind_rate)) {
                $ind_rate = "0.1295";
            }
        }elseif($state == 'WI'){
            $ind_rate = esc_attr(get_option('wf_solar_wisconsin_rate') );
            if (empty($ind_rate)) {
                $ind_rate = "0.163";
            }
        }elseif($state == 'MN'){
            $ind_rate = esc_attr(get_option('wf_solar_minnesota_rate') );
            if (empty($ind_rate)) {
                $ind_rate = "0.163";
            }
        }else{
            $ind_rate = esc_attr(get_option('wf_solar_iowa_rate') );
            if (empty($ind_rate)) {
                $ind_rate = "0.1274";
            }
        }

        $roof_shaded =trim($_GET["roof_shaded"]);
        if (empty($roof_shaded)) {
            $roof_shaded= 0;
        }
        $kwh_offset =trim($_GET["kwh_offset"]);
        if (empty($kwh_offset)) {
            $kwh_offset= 0.1;
        }
        if("10" == $roof_shaded){
            $shaded_value = 0.1;
        }elseif("20" == $roof_shaded){
            $shaded_value = 0.2;
        }elseif("30" == $roof_shaded){
            $shaded_value = 0.3;
        }elseif("40" == $roof_shaded){
            $shaded_value = 0.4;
        }elseif("50" == $roof_shaded){
            $shaded_value = 0.5;
        }elseif("60" == $roof_shaded){
            $shaded_value = 0.6;
        }elseif("70" == $roof_shaded){
            $shaded_value = 0.7;
        }else{
            $shaded_value = 0;
        }

        if("10" == $kwh_offset){
            $offset_value = 0.1;
        }elseif("20" == $kwh_offset){
            $offset_value = 0.2;
        }elseif("30" == $kwh_offset){
            $offset_value = 0.3;
        }elseif("40" == $kwh_offset){
            $offset_value = 0.4;
        }elseif("50" == $kwh_offset){
            $offset_value = 0.5;
        }elseif("60" == $kwh_offset){
            $offset_value = 0.6;
        }elseif("70" == $kwh_offset){
            $offset_value = 0.7;
        }elseif("80" == $kwh_offset){
            $offset_value = 0.8;
        }elseif("90" == $kwh_offset){
            $offset_value = 0.9;
        }elseif("100" == $kwh_offset){
            $offset_value = 1.0;
        }
        $roof_direction = trim($_GET["roof_direction"]);

        if("South" == $roof_direction){
            $roof_shade_south = esc_attr(get_option('wf_solar_roof_shade_south') );
            if ($roof_shade_south == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_south/100;
            }
        }elseif("South_East" == $roof_direction){
            $roof_shade_south_east = esc_attr(get_option('wf_solar_roof_shade_south_east') );
            if ($roof_shade_south_east == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_south_east/100;
            }
        }elseif("East" == $roof_direction){
            $roof_shade_east = esc_attr(get_option('wf_solar_roof_shade_east') );
            if ($roof_shade_east == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_east/100;
            }
        }elseif("South_West" == $roof_direction){
            $roof_shade_south_west = esc_attr(get_option('wf_solar_roof_shade_south_west') );
            if ($roof_shade_south_west == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_south_west/100;
            }
        }elseif("West" == $roof_direction){
            $roof_shade_west = esc_attr(get_option('wf_solar_roof_shade_west') );
            if ($roof_shade_west == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_west/100;
            }
        }

        $d_rate_factor = esc_attr(get_option('wf_solar_d_rate_factor') );
        $federal_tax_credit_value = esc_attr(get_option('wf_solar_federal_tax_credit') );
        $cost_per_watt = esc_attr(get_option('wf_solar_cost_per_watt') );
        $coefficient_system_cost = esc_attr(get_option('wf_solar_coefficient_system_cost') );
        $bill_tf_year_saving_value = esc_attr(get_option('wf_solar_bill_tf_year') );
        $kwh_tf_year_saving_value = esc_attr(get_option('wf_solar_kwh_tf_year') );
        $tf_year_saving_percent = esc_attr(get_option('wf_solar_tf_year_saving_percentage') );
        $escalation_rate = esc_attr(get_option('wf_solar_escalation_rate') );
        $payment_factor = esc_attr(get_option('wf_solar_payment_factor') );

        $check_btn_click =trim($_GET["monthly_kwh"]);
        if($check_btn_click == "NULL"){
            $bill_cost =trim($_GET["billcost"]);
            if (empty($bill_cost)) {
                $bill_cost = "500";
            }
            $kw_per_month = ($bill_cost-15)/$ind_rate;
            $annual_kwh = $kw_per_month*12;
            $ak_x_sv = $annual_kwh*$shaded_value;
            $ak_x_rdv = $annual_kwh*$roof_direction_value;
            $ak_sv_rdv = $annual_kwh + $ak_x_sv + $ak_x_rdv;
            $ak_sv_rdv_x_ov = $ak_sv_rdv*$offset_value;
            $system_size_kwh = $ak_sv_rdv_x_ov/365/4.5/$d_rate_factor;
            $average_system_cost = ceil($cost_per_watt*($system_size_kwh*1000));
            $federal_tax_credit = $average_system_cost*$federal_tax_credit_value;
            $cost_after_incentives = $average_system_cost-$federal_tax_credit;

            $tf_year_saving_percent_calc = $tf_year_saving_percent/100;
            $escalation_rate_percent = $escalation_rate/100;
            $annual_production = ceil($annual_kwh);
            $annual_production_update = $annual_production;
            $zero_point_one_five = $annual_production_update*$bill_tf_year_saving_value;
            $three_percent = $zero_point_one_five*$tf_year_saving_percent_calc;
            $annual_production_sum = $zero_point_one_five+$three_percent;
            $annual_production_update = $annual_production_sum;
            $first_year_saving = $cost_after_incentives/$annual_production_update;
            $escalation_mul_year_saving = $escalation_rate_percent*$first_year_saving;
            $payback_period = $first_year_saving+$escalation_mul_year_saving;
            $payback_round_to_one = round($payback_period , 1);
            $years_months = explode('.', $payback_round_to_one);
            $payback_years = $years_months[0];
            $payback_months = $years_months[1];

            $system_sec_array = array();
            $system_sec_array[] =array('Average System Cost' , $average_system_cost);
            $system_sec_array[] =array('Federal Tax Credit' , $federal_tax_credit);
            $system_sec_array[]=array('Cost after Incentives' , $cost_after_incentives);
            $arr2 =array('_', 'Price');
            $arr1 = array();
            $arr1 = array_merge( array($arr2), $system_sec_array );
            $payment_without_tax = $average_system_cost/$payment_factor;
            $payment_with_tax = $cost_after_incentives/$payment_factor;

        }else{
            $monthly_kwh =trim($_GET["monthly_kwh"]);
            $annual_kwh = $monthly_kwh*12;
            $ak_x_sv = $annual_kwh*$shaded_value;
            $ak_x_rdv = $annual_kwh*$roof_direction_value;
            $ak_sv_rdv = $annual_kwh + $ak_x_sv + $ak_x_rdv;
            $ak_sv_rdv_x_ov = $ak_sv_rdv*$offset_value;
            $system_size_kwh = $ak_sv_rdv_x_ov/365/4.5/$coefficient_system_cost;
            $average_system_cost = ceil($cost_per_watt*($system_size_kwh*1000));
            $federal_tax_credit = $average_system_cost*$federal_tax_credit_value;
            $cost_after_incentives = $average_system_cost-$federal_tax_credit;
            $tf_year_saving_percent_calc = $tf_year_saving_percent/100;
            $escalation_rate_percent = $escalation_rate/100;
            $annual_production = ceil($annual_kwh);
            $annual_production_update = $annual_production;
            $zero_point_one_five = $annual_production_update*$kwh_tf_year_saving_value;
            $three_percent = $zero_point_one_five*$tf_year_saving_percent_calc;
            $annual_production_sum = $zero_point_one_five+$three_percent;
            $annual_production_update = $annual_production_sum;
            $first_year_saving = $cost_after_incentives/$annual_production_update;
            $escalation_mul_year_saving = $escalation_rate_percent*$first_year_saving;
            $payback_period = $first_year_saving+$escalation_mul_year_saving;
            $payback_round_to_one = round($payback_period , 1);
            $years_months = explode('.', $payback_round_to_one);
            $payback_years = $years_months[0];
            $payback_months = $years_months[1];

            $system_sec_array = array();
            $system_sec_array[] =array('Average System Cost' , $average_system_cost);
            $system_sec_array[] =array('Federal Tax Credit' , $federal_tax_credit);
            $system_sec_array[]=array('Cost after Incentives' , $cost_after_incentives);
            $arr2 =array('_', 'Price');
            $arr1 = array();
            $arr1 = array_merge( array($arr2), $system_sec_array );
            $payment_without_tax = $average_system_cost/$payment_factor;
            $payment_with_tax = $cost_after_incentives/$payment_factor;

        }
        ?>


<!--        <script type="text/javascript">-->
<!---->
<!--            var tax_credit=--><?php //echo wp_json_encode($arr1); ?>
<!--            var tax_credit_next=<?php //echo wp_json_encode($arr11); ?>-->
        <script type="text/javascript">
            var tax_credit=<?php echo wp_json_encode($arr1); ?>;
            var tax_credit_next=<?php echo wp_json_encode($arr1); ?>;
        </script>


        <div style="text-align:center">
            <div id="top_x_div" style="margin:0 auto;"></div>
            <!-- <div id="top_x_div_next" style=" margin:0 auto;"></div> -->
        </div>
        <style>
            .padding_text{
                padding:1%;
            }
            .flex_div{
                display:flex;
                margin-bottom:2%;
            }
            .title{
                width:20%;
            }
            .title_2{
                width:100%;
            }
        </style>
        <div class="flex_div sy_co_div" style="margin-top:10px;">
            <div class="title">
                <?php
                echo "System Prices used in average:";
                ?>
            </div>
            <div >
                <p style=" margin: 0px; margin-top: 5px;" class= "cl_system"><?php echo "<b> 6 </b>"; ?></p> 
            </div>
        </div>
        <div class="flex_div sy_co_div">
            <div class="title">
                <?php
                echo "Payback Period:";
                ?>
            </div>
            <div>
                <p style="margin: 0;" class= "cl_payback"><?php echo $payback_years ." Years"; if($payback_months != 0){ echo "," .$payback_months." Months"; }?></p> 
            </div>
        </div>
        <div class="flex_div">
            <div class="title">
                <?php
                echo "Monthly Payment Without Applying Tax Credit To Loan:";
                ?>
            </div>
            <div style="background-color: #5ba63a; width:100%" class="padding_text">
                <p style="margin-left: 10px; color: white;"><?php echo "<b> $". ceil($payment_without_tax) . "</b>"; ?></p> 
            </div>
        </div>
        <div class="flex_div">
            <div class="title">
                <?php
                echo "Monthly Payment With Applying Tax Credit To Loan:";
                ?>
            </div>
            <div style="background-color: #5ba63a; width:100%" class="padding_text">
                <p style="margin-left: 10px; color: white;"><?php echo "<b> $". ceil($payment_with_tax) . "</b>"; ?></p> 
            </div>
        </div>

        <?php

    }

    public function wf_solar_cash_flow_twentyfive_year(){
        global $wpdb;
        $bill_cost =trim($_GET["billcost"]);
        $state = trim($_GET["state"]);
        if($state == 'IL'){
            $ind_rate = esc_attr(get_option('wf_solar_illinious_rate') );
            if (empty($ind_rate)) {
                $ind_rate = "0.1295";
            }
        }elseif($state == 'WI'){
            $ind_rate = esc_attr(get_option('wf_solar_wisconsin_rate') );
            if (empty($ind_rate)) {
                $ind_rate = "0.163";
            }
        }elseif($state == 'MN'){
            $ind_rate = esc_attr(get_option('wf_solar_minnesota_rate') );
            if (empty($ind_rate)) {
                $ind_rate = "0.163";
            }
        }else{
            $ind_rate = esc_attr(get_option('wf_solar_iowa_rate') );
            if (empty($ind_rate)) {
                $ind_rate = "0.1274";
            }
        }
        $bill_tf_year_saving_value = esc_attr(get_option('wf_solar_bill_tf_year') );
        $kwh_tf_year_saving_value = esc_attr(get_option('wf_solar_kwh_tf_year') );
        $tf_year_saving_percent = esc_attr(get_option('wf_solar_tf_year_saving_percentage') );
        $check_btn_click =trim($_GET["monthly_kwh"]);
        if($check_btn_click == "NULL"){
            $bill_cost =trim($_GET["billcost"]);
            if (empty($bill_cost)) {
                $bill_cost = "500";
            }
            $tf_year_saving_percent_calc = $tf_year_saving_percent/100;
            $kw_per_month = ($bill_cost-15)/$ind_rate;
            $annual_kwh = $kw_per_month*12;
            $annual_production = ceil($annual_kwh);
            $annual_production_update = $annual_production;
            $zero_point_one_five = $annual_production_update*$bill_tf_year_saving_value;
            $three_percent = $zero_point_one_five*$tf_year_saving_percent_calc;
            $annual_production_sum = $zero_point_one_five+$three_percent;
            $annual_production_update = $annual_production_sum;
            $total=0;
            $new_data = array();
            $new_data[] =array(0 , ceil($total) );
            $new_data[] =array(1 , ceil($annual_production_update) );
            for($i=2; $i<=25; $i++){
                $three_percent_loop = $annual_production_update*$tf_year_saving_percent_calc;
                $annual_production_update = $annual_production_update+$three_percent_loop;
                $total+=$annual_production_update;
                $new_data[] =array($i , ceil($total) );
            }
            $arr2 =array('Years', 'Price');
            $arr1 = array_merge( array($arr2), $new_data );
        }else{
            $monthly_kwh =trim($_GET["monthly_kwh"]);
            $tf_year_saving_percent_calc = $tf_year_saving_percent/100;
            $annual_kwh = $monthly_kwh*12;
            $annual_production = ceil($annual_kwh);
            $annual_production_update = $annual_production;
            $zero_point_one_five = $annual_production_update*$kwh_tf_year_saving_value;
            $three_percent = $zero_point_one_five*$tf_year_saving_percent_calc;
            $annual_production_sum = $zero_point_one_five+$three_percent;
            $annual_production_update = $annual_production_sum;
            $total=0;
            $new_data = array();
            $new_data[] =array(0 , ceil($total) );
            $new_data[] =array(1 , ceil($annual_production_update) );
            for($i=2; $i<=25; $i++){
                $three_percent_loop = $annual_production_update*$tf_year_saving_percent_calc;
                $annual_production_update = $annual_production_update+$three_percent_loop;
                $total+=$annual_production_update;
                $new_data[] =array($i , ceil($total) );
            }
            $arr2 =array('Years', 'Price');
            $arr1 = array_merge( array($arr2), $new_data );
        }
        ?>
        <script type="text/javascript">
            var cash_flow=<?php echo wp_json_encode($new_data); ?>;
        </script>
        <div>
            <div id="chart_div"></div>
        </div>
        <?php
    }

    public function wf_solar_saving_and_return(){

         global $wpdb;
        $bill_cost =trim($_GET["billcost"]);
        $state = trim($_GET["state"]);
        if($state == 'IL'){
            $ind_rate = esc_attr(get_option('wf_solar_illinious_rate') );
            if (empty($ind_rate)) {
                $ind_rate = "0.1295";
            }
        }elseif($state == 'WI'){
            $ind_rate = esc_attr(get_option('wf_solar_wisconsin_rate') );
            if (empty($ind_rate)) {
                $ind_rate = "0.163";
            }
        }elseif($state == 'MN'){
            $ind_rate = esc_attr(get_option('wf_solar_minnesota_rate') );
            if (empty($ind_rate)) {
                $ind_rate = "0.163";
            }
        }else{
            $ind_rate = esc_attr(get_option('wf_solar_iowa_rate') );
            if (empty($ind_rate)) {
                $ind_rate = "0.1274";
            }
        }

        $roof_shaded =trim($_GET["roof_shaded"]);
        if (empty($roof_shaded)) {
            $roof_shaded= 0;
        }
        $kwh_offset =trim($_GET["kwh_offset"]);
        if (empty($kwh_offset)) {
            $kwh_offset= 0.1;
        }

        $shaded_value = $roof_shaded/100;
        // if("10" == $roof_shaded){
        //     $shaded_value = 1.1;
        // }elseif("20" == $roof_shaded){
        //     $shaded_value = 1.2;
        // }elseif("30" == $roof_shaded){
        //     $shaded_value = 1.3;
        // }elseif("40" == $roof_shaded){
        //     $shaded_value = 1.4;
        // }elseif("50" == $roof_shaded){
        //     $shaded_value = 1.5;
        // }elseif("60" == $roof_shaded){
        //     $shaded_value = 1.6;
        // }elseif("70" == $roof_shaded){
        //     $shaded_value = 1.7;
        // }else{
        //     $shaded_value = 0;
        // }

        if("10" == $kwh_offset){
            $offset_value = 0.1;
        }elseif("20" == $kwh_offset){
            $offset_value = 0.2;
        }elseif("30" == $kwh_offset){
            $offset_value = 0.3;
        }elseif("40" == $kwh_offset){
            $offset_value = 0.4;
        }elseif("50" == $kwh_offset){
            $offset_value = 0.5;
        }elseif("60" == $kwh_offset){
            $offset_value = 0.6;
        }elseif("70" == $kwh_offset){
            $offset_value = 0.7;
        }elseif("80" == $kwh_offset){
            $offset_value = 0.8;
        }elseif("90" == $kwh_offset){
            $offset_value = 0.9;
        }elseif("100" == $kwh_offset){
            $offset_value = 1.0;
        }

        $roof_direction = trim($_GET["roof_direction"]);

        if("South" == $roof_direction){
            $roof_shade_south = esc_attr(get_option('wf_solar_roof_shade_south') );
            if ($roof_shade_south == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_south/100;
            }
        }elseif("South_East" == $roof_direction){
            $roof_shade_south_east = esc_attr(get_option('wf_solar_roof_shade_south_east') );
            if ($roof_shade_south_east == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_south_east/100;
            }
        }elseif("East" == $roof_direction){
            $roof_shade_east = esc_attr(get_option('wf_solar_roof_shade_east') );
            if ($roof_shade_east == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_east/100;
            }
        }elseif("South_West" == $roof_direction){
            $roof_shade_south_west = esc_attr(get_option('wf_solar_roof_shade_south_west') );
            if ($roof_shade_south_west == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_south_west/100;
            }
        }elseif("West" == $roof_direction){
            $roof_shade_west = esc_attr(get_option('wf_solar_roof_shade_west') );
            if ($roof_shade_west == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_west/100;
            }
        }
        $federal_tax_credit_value = esc_attr(get_option('wf_solar_federal_tax_credit') );
        $cost_per_watt = esc_attr(get_option('wf_solar_cost_per_watt') );
        $bill_tf_year_saving_value = esc_attr(get_option('wf_solar_bill_tf_year') );
        $kwh_tf_year_saving_value = esc_attr(get_option('wf_solar_kwh_tf_year') );
        $tf_year_saving_percent = esc_attr(get_option('wf_solar_tf_year_saving_percentage') );
        $coefficient_system_cost = esc_attr(get_option('wf_solar_coefficient_system_cost') );
        $d_rate_factor = esc_attr(get_option('wf_solar_d_rate_factor') );

        $check_btn_click =trim($_GET["monthly_kwh"]);
        if($check_btn_click == "NULL"){
            $bill_cost =trim($_GET["billcost"]);
            if (empty($bill_cost)) {
                $bill_cost = "500";
            }
            $tf_year_saving_percent_calc = $tf_year_saving_percent/100;
            $kw_per_month = ($bill_cost-15)/$ind_rate;
            $annual_kwh = $kw_per_month*12;
            $system_size = $annual_kwh/365/4.5/$d_rate_factor;
            $system_x_shade = $system_size*$shaded_value;
            $system_x_offset = $system_x_shade*$offset_value;
            $system_x_direction = $system_x_offset*$roof_direction_value;
            $average_system_cost = $cost_per_watt*($system_x_direction*1000);
            $federal_tax_credit = $average_system_cost*$federal_tax_credit_value;
            $cost_after_incentives = $average_system_cost-$federal_tax_credit;
            $annual_production = ceil($annual_kwh);
            $annual_production_update = $annual_production;
            $zero_point_one_five = $annual_production_update*$bill_tf_year_saving_value;
            $three_percent = $zero_point_one_five*$tf_year_saving_percent_calc;
            $annual_production_sum = $zero_point_one_five+$three_percent;
            $annual_production_update = $annual_production_sum;
            $total=0;
            for($i=1; $i<26; $i++){
                $three_percent_loop = $annual_production_update*$tf_year_saving_percent_calc;
                $annual_production_update = $annual_production_update+$three_percent_loop;
                $total+=$annual_production_update;
            }
        }else{
            $monthly_kwh =trim($_GET["monthly_kwh"]);
            $tf_year_saving_percent_calc = $tf_year_saving_percent/100;
            $annual_kwh = $monthly_kwh*12;
            $system_size = $annual_kwh/365/4.5/$coefficient_system_cost;
            $system_x_shade = $system_size*$shaded_value;
            $system_x_offset = $system_x_shade*$offset_value;
            $system_x_direction = $system_x_offset*$roof_direction_value;
            $average_system_cost = $cost_per_watt*($system_x_direction*1000);
            $federal_tax_credit = $average_system_cost*$federal_tax_credit_value;
            $cost_after_incentives = $average_system_cost-$federal_tax_credit;
            $annual_production = ceil($annual_kwh);
            $annual_production_update = $annual_production;
            $zero_point_one_five = $annual_production_update*$kwh_tf_year_saving_value;
            $three_percent = $zero_point_one_five*$tf_year_saving_percent_calc;
            $annual_production_sum = $zero_point_one_five+$three_percent;
            $annual_production_update = $annual_production_sum;
            $total=0;
            for($i=1; $i<26; $i++){
                $three_percent_loop = $annual_production_update*$tf_year_saving_percent_calc;
                $annual_production_update = $annual_production_update+$three_percent_loop;
                $total+=$annual_production_update;
            }
        }
        ?>
        <div>
            <?php
            echo "Electric Bill Saving Over 25 Year:";
            ?>
        </div>
        <div style="background-color: #2eaae1;">
            <p style="margin-left: 10px; color: white;"><?php echo "$".number_format(ceil($total)); ?></p> 
        </div>
        <?php

    }
    public function wf_solar_bill_saving_twentyfive_year(){

        global $wpdb;
        $bill_cost =trim($_GET["billcost"]);
        $state = trim($_GET["state"]);
        if($state == 'IL'){
            $ind_rate = esc_attr(get_option('wf_solar_illinious_rate') );
            if (empty($ind_rate)) {
                $ind_rate = "0.1295";
            }
        }elseif($state == 'WI'){
            $ind_rate = esc_attr(get_option('wf_solar_wisconsin_rate') );
            if (empty($ind_rate)) {
                $ind_rate = "0.163";
            }
        }elseif($state == 'MN'){
            $ind_rate = esc_attr(get_option('wf_solar_minnesota_rate') );
            if (empty($ind_rate)) {
                $ind_rate = "0.163";
            }
        }else{
            $ind_rate = esc_attr(get_option('wf_solar_iowa_rate') );
            if (empty($ind_rate)) {
                $ind_rate = "0.1274";
            }
        }

        $roof_shaded =trim($_GET["roof_shaded"]);
        if (empty($roof_shaded)) {
            $roof_shaded= 0.5;
        }
        $kwh_offset =trim($_GET["kwh_offset"]);
        if (empty($kwh_offset)) {
            $kwh_offset= 0.1;
        }

        $shaded_value = $roof_shaded/100;
        // if("10" == $roof_shaded){
        //     $shaded_value = 1.1;
        // }elseif("20" == $roof_shaded){
        //     $shaded_value = 1.2;
        // }elseif("30" == $roof_shaded){
        //     $shaded_value = 1.3;
        // }elseif("40" == $roof_shaded){
        //     $shaded_value = 1.4;
        // }elseif("50" == $roof_shaded){
        //     $shaded_value = 1.5;
        // }elseif("60" == $roof_shaded){
        //     $shaded_value = 1.6;
        // }elseif("70" == $roof_shaded){
        //     $shaded_value = 1.7;
        // }else{
        //     $shaded_value = 0;
        // }

        if("10" == $kwh_offset){
            $offset_value = 0.1;
        }elseif("20" == $kwh_offset){
            $offset_value = 0.2;
        }elseif("30" == $kwh_offset){
            $offset_value = 0.3;
        }elseif("40" == $kwh_offset){
            $offset_value = 0.4;
        }elseif("50" == $kwh_offset){
            $offset_value = 0.5;
        }elseif("60" == $kwh_offset){
            $offset_value = 0.6;
        }elseif("70" == $kwh_offset){
            $offset_value = 0.7;
        }elseif("80" == $kwh_offset){
            $offset_value = 0.8;
        }elseif("90" == $kwh_offset){
            $offset_value = 0.9;
        }elseif("100" == $kwh_offset){
            $offset_value = 1.0;
        }

        $roof_direction = trim($_GET["roof_direction"]);

        if("South" == $roof_direction){
            $roof_shade_south = esc_attr(get_option('wf_solar_roof_shade_south') );
            if ($roof_shade_south == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_south/100;
            }
        }elseif("South_East" == $roof_direction){
            $roof_shade_south_east = esc_attr(get_option('wf_solar_roof_shade_south_east') );
            if ($roof_shade_south_east == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_south_east/100;
            }
        }elseif("East" == $roof_direction){
            $roof_shade_east = esc_attr(get_option('wf_solar_roof_shade_east') );
            if ($roof_shade_east == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_east/100;
            }
        }elseif("South_West" == $roof_direction){
            $roof_shade_south_west = esc_attr(get_option('wf_solar_roof_shade_south_west') );
            if ($roof_shade_south_west == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_south_west/100;
            }
        }elseif("West" == $roof_direction){
            $roof_shade_west = esc_attr(get_option('wf_solar_roof_shade_west') );
            if ($roof_shade_west == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_west/100;
            }
        }
        $federal_tax_credit_value = esc_attr(get_option('wf_solar_federal_tax_credit') );
        $cost_per_watt = esc_attr(get_option('wf_solar_cost_per_watt') );
        $bill_tf_year_saving_value = esc_attr(get_option('wf_solar_bill_tf_year') );
        $kwh_tf_year_saving_value = esc_attr(get_option('wf_solar_kwh_tf_year') );

        //start mp insert
        if("South" == $roof_direction){
            $roof_shade_south = esc_attr(get_option('wf_solar_roof_shade_south') );
            if ($roof_shade_south == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_south/100;
            }
            $roof_shade_south_production_rate = esc_attr(get_option('wf_solar_roof_shade_south_production_ratio') );
            if ($roof_shade_south_production_rate == " ") {
                $roof_direction_ratio = 0;
            }else{
                $roof_direction_ratio = $roof_shade_south_production_rate/100;
            }
        }elseif("South_East" == $roof_direction){
            $roof_shade_south_east = esc_attr(get_option('wf_solar_roof_shade_south_east') );
            if ($roof_shade_south_east == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_south_east/100;
            }
            $roof_shade_south_east_production_ratio = esc_attr(get_option('wf_solar_roof_shade_south_east_production_ratio') );
            if ($roof_shade_south_east_production_ratio == " ") {
                $roof_direction_ratio = 0;
            }else{
                $roof_direction_ratio = $roof_shade_south_east_production_ratio/100;
            }
        }elseif("East" == $roof_direction){
            $roof_shade_east = esc_attr(get_option('wf_solar_roof_shade_east') );
            if ($roof_shade_east == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_east/100;
            }
            $roof_shade_east_production_ratio = esc_attr(get_option('wf_solar_roof_shade_east_production_ratio') );
            if ($roof_shade_east_production_ratio == " ") {
                $roof_direction_ratio = 0;
            }else{
                $roof_direction_ratio = $roof_shade_east_production_ratio/100;
            }
        }elseif("South_West" == $roof_direction){
            $roof_shade_south_west = esc_attr(get_option('wf_solar_roof_shade_south_west') );
            if ($roof_shade_south_west == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_south_west/100;
            }
            $roof_shade_south_west_production_ratio = esc_attr(get_option('wf_solar_roof_shade_south_west_production_ratio') );
            if ($roof_shade_south_west_production_ratio == " ") {
                $roof_direction_ratio = 0;
            }else{
                $roof_direction_ratio = $roof_shade_south_west_production_ratio/100;
            }
        }elseif("West" == $roof_direction){
            $roof_shade_west = esc_attr(get_option('wf_solar_roof_shade_west') );
            if ($roof_shade_west == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_west/100;
            }
            $roof_shade_west_production_ratio = esc_attr(get_option('wf_solar_roof_shade_west_production_ratio') );
            if ($roof_shade_west_production_ratio == " ") {
                $roof_direction_ratio = 0;
            }else{
                $roof_direction_ratio = $roof_shade_west_production_ratio/100;
            }
        }
        //end MP insert

        $tf_year_saving_percent = esc_attr(get_option('wf_solar_tf_year_saving_percentage') );
        $coefficient_system_cost = esc_attr(get_option('wf_solar_coefficient_system_cost') );
        $d_rate_factor = esc_attr(get_option('wf_solar_d_rate_factor') );

        $check_btn_click =trim($_GET["monthly_kwh"]);
        if($check_btn_click == "NULL"){
            $bill_cost =trim($_GET["billcost"]);
            if (empty($bill_cost)) {
                $bill_cost = "500";
            }
            $tf_year_saving_percent_calc = $tf_year_saving_percent/100;
            $kw_per_month = ($bill_cost-15)/$ind_rate;
            $annual_kwh = $kw_per_month*12;
            $ak_x_sv = $annual_kwh*$shaded_value;
            $ak_x_rdv = $annual_kwh*$roof_direction_value;
            $ak_sv_rdv = $annual_kwh + $ak_x_sv + $ak_x_rdv;
            $ak_sv_rdv_x_ov = $ak_sv_rdv*$offset_value;
            $system_size_kwh = $ak_sv_rdv_x_ov/365/4.5/$d_rate_factor;
            $average_system_cost = ceil($cost_per_watt*($system_size_kwh*1000));
            $federal_tax_credit = $average_system_cost*$federal_tax_credit_value;
            $cost_after_incentives = $average_system_cost-$federal_tax_credit;
            //$annual_production = ceil($annual_kwh);  //original
            //start MP changes
            $annual_production_calc = ($system_size_kwh*1000)*$roof_direction_ratio;
            $annual_production = ceil($annual_production_calc);
            //end MP changes
            $annual_production_update = $annual_production;
            $zero_point_one_five = $annual_production_update*$bill_tf_year_saving_value;
            $three_percent = $zero_point_one_five*$tf_year_saving_percent_calc;
            $annual_production_sum = $zero_point_one_five+$three_percent;
            $annual_production_update = $annual_production_sum;
            $total=0;
            for($i=1; $i<26; $i++){
                $three_percent_loop = $annual_production_update*$tf_year_saving_percent_calc;
                $annual_production_update = $annual_production_update+$three_percent_loop;
                $total+=$annual_production_update;
            }
        }else{
            $monthly_kwh =trim($_GET["monthly_kwh"]);
            $tf_year_saving_percent_calc = $tf_year_saving_percent/100;
            $annual_kwh = $monthly_kwh*12;
            $ak_x_sv = $annual_kwh*$shaded_value;
            $ak_x_rdv = $annual_kwh*$roof_direction_value;
            $ak_sv_rdv = $annual_kwh + $ak_x_sv + $ak_x_rdv;
            $ak_sv_rdv_x_ov = $ak_sv_rdv*$offset_value;
            $system_size_kwh = $ak_sv_rdv_x_ov/365/4.5/$coefficient_system_cost;
            $average_system_cost = ceil($cost_per_watt*($system_size_kwh*1000));
            $federal_tax_credit = $average_system_cost*$federal_tax_credit_value;
            $cost_after_incentives = $average_system_cost-$federal_tax_credit;
            $annual_production = ceil($annual_kwh);
            $annual_production_update = $annual_production;
            $zero_point_one_five = $annual_production_update*$kwh_tf_year_saving_value;
            $three_percent = $zero_point_one_five*$tf_year_saving_percent_calc;
            $annual_production_sum = $zero_point_one_five+$three_percent;
            $annual_production_update = $annual_production_sum;
            $total=0;
            for($i=1; $i<26; $i++){
                $three_percent_loop = $annual_production_update*$tf_year_saving_percent_calc;
                $annual_production_update = $annual_production_update+$three_percent_loop;
                $total+=$annual_production_update;
            }
        }
        ?>
        <style>
            .padding_text{
                padding:1%;
            }
            .flex_div{
                display:flex;
                margin-bottom:2%;
            }
            .title{
                width:20%;
            }
            .title_2{
                width:100%;
            }
        </style>
        <div class="flex_div">
            <div class="title">
                <?php
                echo "25 Year Bill Savings:";
                ?>
            </div>
            <div style="background-color: #5ba63a; width:100%" class="padding_text">
                <p style="margin-left: 10px; color: white;"><?php echo "<b> $".number_format(ceil($total)) . "</b>"; ?></p> 
            </div>
        </div>
        <?php

    }

    public function wf_solar_montly_bill_savings(){
        $bill_cost =trim($_GET["billcost"]);
        $estimated_bill = esc_attr(get_option('wf_solar_bill_after_solar') );
        $monthy_bill_savings = $bill_cost-$estimated_bill;
        $state = trim($_GET["state"]);
        if($state == 'IL'){
            $ind_rate = esc_attr(get_option('wf_solar_illinious_rate') );
            if (empty($ind_rate)) {
                $ind_rate = "0.1295";
            }
        }elseif($state == 'WI'){
            $ind_rate = esc_attr(get_option('wf_solar_wisconsin_rate') );
            if (empty($ind_rate)) {
                $ind_rate = "0.163";
            }
        }elseif($state == 'MN'){
            $ind_rate = esc_attr(get_option('wf_solar_minnesota_rate') );
            if (empty($ind_rate)) {
                $ind_rate = "0.163";
            }
        }else{
            $ind_rate = esc_attr(get_option('wf_solar_iowa_rate') );
            if (empty($ind_rate)) {
                $ind_rate = "0.1274";
            }
        }
        $kwh_offset =trim($_GET["kwh_offset"]);
        if (empty($kwh_offset)) {
            $kwh_offset= 0.1;
        }
        if("10" == $kwh_offset){
            $offset_value = 0.1;
        }elseif("20" == $kwh_offset){
            $offset_value = 0.2;
        }elseif("30" == $kwh_offset){
            $offset_value = 0.3;
        }elseif("40" == $kwh_offset){
            $offset_value = 0.4;
        }elseif("50" == $kwh_offset){
            $offset_value = 0.5;
        }elseif("60" == $kwh_offset){
            $offset_value = 0.6;
        }elseif("70" == $kwh_offset){
            $offset_value = 0.7;
        }elseif("80" == $kwh_offset){
            $offset_value = 0.8;
        }elseif("90" == $kwh_offset){
            $offset_value = 0.9;
        }elseif("100" == $kwh_offset){
            $offset_value = 1.0;
        }
        $check_btn_click =trim($_GET["monthly_kwh"]);
        if($check_btn_click == "NULL"){
            $bill_cost =trim($_GET["billcost"]);
            if (empty($bill_cost)) {
                $bill_cost = "500";
            }
            if ($kwh_offset != 100) {
                $estimated_bill = $bill_cost*(1-$offset_value);
            }else{
                $estimated_bill = esc_attr(get_option('wf_solar_bill_after_solar') );
            }

            $monthy_bill_savings = $bill_cost-$estimated_bill;
        }else{
            $monthly_kwh =trim($_GET["monthly_kwh"]);
            $bill_cost = $monthly_kwh*$ind_rate;
            if ($kwh_offset != 100) {
                $estimated_bill = $bill_cost*(1-$offset_value);
            }else{
                $estimated_bill = esc_attr(get_option('wf_solar_bill_after_solar') );
            }
            $monthy_bill_savings = $bill_cost-$estimated_bill;
        }
        ?>
        <style>
            .padding_text{
                padding:1%;
            }
            .flex_div{
                display:flex;
                margin-bottom:2%;
            }
            .title{
                width:30% !important;
            }
            .title_2{
                width:100%;
            }
        </style>
        <div class="flex_div">
            <div class="title">
                <?php
                echo "Current Monthly Bill Before Solar:";
                ?>
            </div>
            <div style="background-color: #005691; width:100%" class="padding_text">
                <p style="margin-left: 10px; color: white;"><?php echo "<b> $".$bill_cost . "</b>"; ?></p> 
            </div>
        </div>
        <div class="flex_div">
            <div class="title">
                <?php
                echo "Estimated Bill After Solar:";
                ?>
            </div>
            <div style=" width:100%" class="padding_text bill_estimate">
                <p><?php echo '<b> $' . $estimated_bill .'</b>'; ?></p> 
            </div>
        </div>




        <div class="flex_div">
            <div class="title">
                <?php
                echo "Monthly Bill Savings:";
                ?>
            </div>
            <div style="background-color: #5ba63a; width:100%" class="padding_text">
                <p style="margin-left: 10px; background-color: #5ba63a; color: white;"><?php echo "<b> $".$monthy_bill_savings . "</b>"; ?></p>
            </div>
        </div>
        <div class="flex_div">
            <div class="title_2">
                <?php
                echo "Note: Monthly bill savings will increase as utility rates increase";
                ?>
            </div>
        </div>

        <?php
    }

    public function wf_solar_system_prices(){
        global $wpdb;
        $state = trim($_GET["state"]);
        if($state == 'IL'){
            $ind_rate = esc_attr(get_option('wf_solar_illinious_rate') );
            if (empty($ind_rate)) {
                $ind_rate = "0.1295";
            }
        }elseif($state == 'WI'){
            $ind_rate = esc_attr(get_option('wf_solar_wisconsin_rate') );
            if (empty($ind_rate)) {
                $ind_rate = "0.163";
            }
        }elseif($state == 'MN'){
            $ind_rate = esc_attr(get_option('wf_solar_minnesota_rate') );
            if (empty($ind_rate)) {
                $ind_rate = "0.163";
            }
        }else{
            $ind_rate = esc_attr(get_option('wf_solar_iowa_rate') );
            if (empty($ind_rate)) {
                $ind_rate = "0.1274";
            }
        }

        $roof_shaded =trim($_GET["roof_shaded"]);
        if (empty($roof_shaded)) {
            $roof_shaded= 0.5;
        }
        $kwh_offset =trim($_GET["kwh_offset"]);
        if (empty($kwh_offset)) {
            $kwh_offset= 0.1;
        }
        if("10" == $roof_shaded){
            $shaded_value = 0.1;
        }elseif("20" == $roof_shaded){
            $shaded_value = 0.2;
        }elseif("30" == $roof_shaded){
            $shaded_value = 0.3;
        }elseif("40" == $roof_shaded){
            $shaded_value = 0.4;
        }elseif("50" == $roof_shaded){
            $shaded_value = 0.5;
        }elseif("60" == $roof_shaded){
            $shaded_value = 0.6;
        }elseif("70" == $roof_shaded){
            $shaded_value = 0.7;
        }else{
            $shaded_value = 0;
        }

        if("10" == $kwh_offset){
            $offset_value = 0.1;
        }elseif("20" == $kwh_offset){
            $offset_value = 0.2;
        }elseif("30" == $kwh_offset){
            $offset_value = 0.3;
        }elseif("40" == $kwh_offset){
            $offset_value = 0.4;
        }elseif("50" == $kwh_offset){
            $offset_value = 0.5;
        }elseif("60" == $kwh_offset){
            $offset_value = 0.6;
        }elseif("70" == $kwh_offset){
            $offset_value = 0.7;
        }elseif("80" == $kwh_offset){
            $offset_value = 0.8;
        }elseif("90" == $kwh_offset){
            $offset_value = 0.9;
        }elseif("100" == $kwh_offset){
            $offset_value = 1.0;
        }
        $coefficient_system_cost = esc_attr(get_option('wf_solar_coefficient_system_cost') );
        $d_rate_factor = esc_attr(get_option('wf_solar_d_rate_factor') );
        $roof_direction = trim($_GET["roof_direction"]);

        if("South" == $roof_direction){
            $roof_shade_south = esc_attr(get_option('wf_solar_roof_shade_south') );
            if ($roof_shade_south == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_south/100;
            }
        }elseif("South_East" == $roof_direction){
            $roof_shade_south_east = esc_attr(get_option('wf_solar_roof_shade_south_east') );
            if ($roof_shade_south_east == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_south_east/100;
            }
        }elseif("East" == $roof_direction){
            $roof_shade_east = esc_attr(get_option('wf_solar_roof_shade_east') );
            if ($roof_shade_east == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_east/100;
            }
        }elseif("South_West" == $roof_direction){
            $roof_shade_south_west = esc_attr(get_option('wf_solar_roof_shade_south_west') );
            if ($roof_shade_south_west == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_south_west/100;
            }
        }elseif("West" == $roof_direction){
            $roof_shade_west = esc_attr(get_option('wf_solar_roof_shade_west') );
            if ($roof_shade_west == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_west/100;
            }
        }

        $check_btn_click =trim($_GET["monthly_kwh"]);
        if($check_btn_click == "NULL"){
            $bill_cost =trim($_GET["billcost"]);
            if (empty($bill_cost)) {
                $bill_cost = "500";
            }
            $federal_tax_credit_value = esc_attr(get_option('wf_solar_federal_tax_credit') );
            $cost_per_watt = esc_attr(get_option('wf_solar_cost_per_watt') );
            $kw_per_month = ($bill_cost-15)/$ind_rate;
            $annual_kwh = $kw_per_month*12;
            $ak_x_sv = $annual_kwh*$shaded_value;
            $ak_x_rdv = $annual_kwh*$roof_direction_value;
            $ak_sv_rdv = $annual_kwh + $ak_x_sv + $ak_x_rdv;
            $ak_sv_rdv_x_ov = $ak_sv_rdv*$offset_value;
            $system_size_kwh = $ak_sv_rdv_x_ov/365/4.5/$d_rate_factor;
            $average_system_cost = ceil($cost_per_watt*($system_size_kwh*1000));
            $federal_tax_credit = ceil($average_system_cost*$federal_tax_credit_value);
            $cost_after_incentives = ceil($average_system_cost-$federal_tax_credit);
        }else{
            $monthly_kwh =trim($_GET["monthly_kwh"]);
            $federal_tax_credit_value = esc_attr(get_option('wf_solar_federal_tax_credit') );
            $cost_per_watt = esc_attr(get_option('wf_solar_cost_per_watt') );
            $annual_kwh = $monthly_kwh*12;
            $ak_x_sv = $annual_kwh*$shaded_value;
            $ak_x_rdv = $annual_kwh*$roof_direction_value;
            $ak_sv_rdv = $annual_kwh + $ak_x_sv + $ak_x_rdv;
            $ak_sv_rdv_x_ov = $ak_sv_rdv*$offset_value;
            $system_size_kwh = $ak_sv_rdv_x_ov/365/4.5/$coefficient_system_cost;
            $average_system_cost = ceil($cost_per_watt*($system_size_kwh*1000));
            $federal_tax_credit = ceil($average_system_cost*$federal_tax_credit_value);
            $cost_after_incentives = ceil($average_system_cost-$federal_tax_credit);
        }
        ?>
        <div style="text-align: center; padding-top: 30px;">
            <?php
            echo "Local System Prices(After Federal Tax Credit)";
            echo "<br>";
            echo " <p style='color:#2eaae1; margin-top: 14px; font-weight: 700;'>$".number_format($average_system_cost)." - $".number_format($federal_tax_credit)." =  $".number_format($cost_after_incentives)."</p>";
            ?>
        </div>
        <?php

    }

    public function wf_solar_yr_savings(){
        global $wpdb;
        global $state;
        $bill_cost =trim($_GET["billcost"]);
        if($state == 'IL'){
            $ind_rate = esc_attr(get_option('wf_solar_illinious_rate') );
            if (empty($ind_rate)) {
                $ind_rate = "0.1295";
            }
        }elseif($state == 'WI'){
            $ind_rate = esc_attr(get_option('wf_solar_wisconsin_rate') );
            if (empty($ind_rate)) {
                $ind_rate = "0.163";
            }
        }elseif($state == 'MN'){
            $ind_rate = esc_attr(get_option('wf_solar_minnesota_rate') );
            if (empty($ind_rate)) {
                $ind_rate = "0.163";
            }
        }else{
            $ind_rate = esc_attr(get_option('wf_solar_iowa_rate') );
            if (empty($ind_rate)) {
                $ind_rate = "0.1274";
            }
        }

        $roof_shaded =trim($_GET["roof_shaded"]);
        if (empty($roof_shaded)) {
            $roof_shaded= 0.5;
        }
        $kwh_offset =trim($_GET["kwh_offset"]);
        if (empty($kwh_offset)) {
            $kwh_offset= 0.1;
        }
        if("10" == $roof_shaded){
            $shaded_value = 0.1;
        }elseif("20" == $roof_shaded){
            $shaded_value = 0.2;
        }elseif("30" == $roof_shaded){
            $shaded_value = 0.3;
        }elseif("40" == $roof_shaded){
            $shaded_value = 0.4;
        }elseif("50" == $roof_shaded){
            $shaded_value = 0.5;
        }elseif("60" == $roof_shaded){
            $shaded_value = 0.6;
        }elseif("70" == $roof_shaded){
            $shaded_value = 0.7;
        }else{
            $shaded_value = 0;
        }

        if("10" == $kwh_offset){
            $offset_value = 0.1;
        }elseif("20" == $kwh_offset){
            $offset_value = 0.2;
        }elseif("30" == $kwh_offset){
            $offset_value = 0.3;
        }elseif("40" == $kwh_offset){
            $offset_value = 0.4;
        }elseif("50" == $kwh_offset){
            $offset_value = 0.5;
        }elseif("60" == $kwh_offset){
            $offset_value = 0.6;
        }elseif("70" == $kwh_offset){
            $offset_value = 0.7;
        }elseif("80" == $kwh_offset){
            $offset_value = 0.8;
        }elseif("90" == $kwh_offset){
            $offset_value = 0.9;
        }elseif("100" == $kwh_offset){
            $offset_value = 1.0;
        }
        $roof_direction = trim($_GET["roof_direction"]);

        if("South" == $roof_direction){
            $roof_shade_south = esc_attr(get_option('wf_solar_roof_shade_south') );
            if ($roof_shade_south == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_south/100;
            }
            $roof_shade_south_production_rate = esc_attr(get_option('wf_solar_roof_shade_south_production_ratio') );
            if ($roof_shade_south_production_rate == " ") {
                $roof_direction_ratio = 0;
            }else{
                $roof_direction_ratio = $roof_shade_south_production_rate/100;
            }
        }elseif("South_East" == $roof_direction){
            $roof_shade_south_east = esc_attr(get_option('wf_solar_roof_shade_south_east') );
            if ($roof_shade_south_east == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_south_east/100;
            }
            $roof_shade_south_east_production_ratio = esc_attr(get_option('wf_solar_roof_shade_south_east_production_ratio') );
            if ($roof_shade_south_east_production_ratio == " ") {
                $roof_direction_ratio = 0;
            }else{
                $roof_direction_ratio = $roof_shade_south_east_production_ratio/100;
            }
        }elseif("East" == $roof_direction){
            $roof_shade_east = esc_attr(get_option('wf_solar_roof_shade_east') );
            if ($roof_shade_east == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_east/100;
            }
            $roof_shade_east_production_ratio = esc_attr(get_option('wf_solar_roof_shade_east_production_ratio') );
            if ($roof_shade_east_production_ratio == " ") {
                $roof_direction_ratio = 0;
            }else{
                $roof_direction_ratio = $roof_shade_east_production_ratio/100;
            }
        }elseif("South_West" == $roof_direction){
            $roof_shade_south_west = esc_attr(get_option('wf_solar_roof_shade_south_west') );
            if ($roof_shade_south_west == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_south_west/100;
            }
            $roof_shade_south_west_production_ratio = esc_attr(get_option('wf_solar_roof_shade_south_west_production_ratio') );
            if ($roof_shade_south_west_production_ratio == " ") {
                $roof_direction_ratio = 0;
            }else{
                $roof_direction_ratio = $roof_shade_south_west_production_ratio/100;
            }
        }elseif("West" == $roof_direction){
            $roof_shade_west = esc_attr(get_option('wf_solar_roof_shade_west') );
            if ($roof_shade_west == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_west/100;
            }
            $roof_shade_west_production_ratio = esc_attr(get_option('wf_solar_roof_shade_west_production_ratio') );
            if ($roof_shade_west_production_ratio == " ") {
                $roof_direction_ratio = 0;
            }else{
                $roof_direction_ratio = $roof_shade_west_production_ratio/100;
            }
        }
        $coefficient_system_cost = esc_attr(get_option('wf_solar_coefficient_system_cost') );
        $d_rate_factor = esc_attr(get_option('wf_solar_d_rate_factor') );

        $check_btn_click =trim($_GET["monthly_kwh"]);
        if($check_btn_click == "NULL"){
            $bill_cost =trim($_GET["billcost"]);
            if (empty($bill_cost)) {
                $bill_cost = "500";
            }
            $bill_tf_year_saving_value = esc_attr(get_option('wf_solar_bill_tf_year') );
            $kwh_tf_year_saving_value = esc_attr(get_option('wf_solar_kwh_tf_year') );
            $tf_year_saving_percent = esc_attr(get_option('wf_solar_tf_year_saving_percentage') );
            if (empty($tf_year_saving_percent)) {
                $tf_year_saving_percent = 10;
            }
            $tf_year_saving_percent_calc = $tf_year_saving_percent/100;
            $kw_per_month = ($bill_cost-15)/$ind_rate;
            $annual_kwh = $kw_per_month*12;
            $ak_x_sv = $annual_kwh*$shaded_value;
            $ak_x_rdv = $annual_kwh*$roof_direction_value;
            $ak_sv_rdv = $annual_kwh + $ak_x_sv + $ak_x_rdv;
            $ak_sv_rdv_x_ov = $ak_sv_rdv*$offset_value;
            $system_size_kwh = $ak_sv_rdv_x_ov/365/4.5/$d_rate_factor;
            $annual_production_calc = ($system_size_kwh*1000)*$roof_direction_ratio;
            $annual_production = ceil($annual_production_calc);
            $annual_production_update = $annual_production;
            $zero_point_one_five = $annual_production_update*$bill_tf_year_saving_value;
            $three_percent = $zero_point_one_five*$tf_year_saving_percent_calc;
            $annual_production_sum = $zero_point_one_five+$three_percent;
            $annual_production_update = $annual_production_sum;
            $total=0;
            for($i=1; $i<26; $i++){
                $three_percent_loop = $annual_production_update*$tf_year_saving_percent_calc;
                $annual_production_update = $annual_production_update+$three_percent_loop;
                $total+=$annual_production_update;
            }
        }else{
            $monthly_kwh =trim($_GET["monthly_kwh"]);
            $bill_tf_year_saving_value = esc_attr(get_option('wf_solar_bill_tf_year') );
            $kwh_tf_year_saving_value = esc_attr(get_option('wf_solar_kwh_tf_year') );
            $tf_year_saving_percent = esc_attr(get_option('wf_solar_tf_year_saving_percentage') );
            if (empty($tf_year_saving_percent)) {
                $tf_year_saving_percent = 10;
            }
            $tf_year_saving_percent_calc = $tf_year_saving_percent/100;  //TODO: this section is for monthly kwh entry but is calculated as if it were a bill cost entry
            $annual_kwh = $monthly_kwh*12;
            $ak_x_sv = $annual_kwh*$shaded_value;
            $ak_x_rdv = $annual_kwh*$roof_direction_value;
            $ak_sv_rdv = $annual_kwh + $ak_x_sv + $ak_x_rdv;
            $ak_sv_rdv_x_ov = $ak_sv_rdv*$offset_value;
            //$system_size_kwh = $ak_sv_rdv_x_ov/365/4.5/$d_rate_factor;        //difference
            $system_size_kwh = $ak_sv_rdv_x_ov/365/4.5/$coefficient_system_cost;        //change 
            $annual_production_calc = ($system_size_kwh*1000)*$roof_direction_ratio;
            //$annual_production = ceil($annual_production_calc);       //difference
            $annual_production = ceil($annual_kwh);       //change
            $annual_production_update = $annual_production;
            $zero_point_one_five = $annual_production_update*$kwh_tf_year_saving_value;
            $three_percent = $zero_point_one_five*$tf_year_saving_percent_calc;
            $annual_production_sum = $zero_point_one_five+$three_percent;
            $annual_production_update = $annual_production_sum;
            $total=0;
            for($i=1; $i<26; $i++){  //TODO: Test this
                $three_percent_loop = $annual_production_update*$tf_year_saving_percent_calc; //TODO: annual_production_update is different entering this loop than any other loop
                $annual_production_update = $annual_production_update+$three_percent_loop;
                $total+=$annual_production_update;
            }
        }

        ?>
        <div style="text-align: center; padding-top: 30px;">
            <?php
            echo "25 Years Savings";
            echo "<br>";
            echo "<p style='color:#2eaae1; margin-top: 14px; font-weight: 700;'>$".number_format(ceil($total))."</p>";

            ?>
        </div>
        <?php
    }

    public function wf_solar_annual_production(){

        global $wpdb;
        $state = trim($_GET["state"]);
        if($state == 'IL'){
            $ind_rate = esc_attr(get_option('wf_solar_illinious_rate') );
            if (empty($ind_rate)) {
                $ind_rate = "0.1295";
            }
        }elseif($state == 'WI'){
            $ind_rate = esc_attr(get_option('wf_solar_wisconsin_rate') );
            if (empty($ind_rate)) {
                $ind_rate = "0.163";
            }
        }elseif($state == 'MN'){
            $ind_rate = esc_attr(get_option('wf_solar_minnesota_rate') );
            if (empty($ind_rate)) {
                $ind_rate = "0.163";
            }
        }else{
            $ind_rate = esc_attr(get_option('wf_solar_iowa_rate') );
            if (empty($ind_rate)) {
                $ind_rate = "0.1274";
            }
        }

        $roof_shaded =trim($_GET["roof_shaded"]);
        if (empty($roof_shaded)) {
            $roof_shaded= 0.5;
        }
        $kwh_offset =trim($_GET["kwh_offset"]);
        if (empty($kwh_offset)) {
            $kwh_offset= 0.1;
        }
        if("10" == $roof_shaded){
            $shaded_value = 0.1;
        }elseif("20" == $roof_shaded){
            $shaded_value = 0.2;
        }elseif("30" == $roof_shaded){
            $shaded_value = 0.3;
        }elseif("40" == $roof_shaded){
            $shaded_value = 0.4;
        }elseif("50" == $roof_shaded){
            $shaded_value = 0.5;
        }elseif("60" == $roof_shaded){
            $shaded_value = 0.6;
        }elseif("70" == $roof_shaded){
            $shaded_value = 0.7;
        }else{
            $shaded_value = 0;
        }

        if("10" == $kwh_offset){
            $offset_value = 0.1;
        }elseif("20" == $kwh_offset){
            $offset_value = 0.2;
        }elseif("30" == $kwh_offset){
            $offset_value = 0.3;
        }elseif("40" == $kwh_offset){
            $offset_value = 0.4;
        }elseif("50" == $kwh_offset){
            $offset_value = 0.5;
        }elseif("60" == $kwh_offset){
            $offset_value = 0.6;
        }elseif("70" == $kwh_offset){
            $offset_value = 0.7;
        }elseif("80" == $kwh_offset){
            $offset_value = 0.8;
        }elseif("90" == $kwh_offset){
            $offset_value = 0.9;
        }elseif("100" == $kwh_offset){
            $offset_value = 1.0;
        }
        $roof_direction = trim($_GET["roof_direction"]);

        if("South" == $roof_direction){
            $roof_shade_south = esc_attr(get_option('wf_solar_roof_shade_south') );
            if ($roof_shade_south == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_south/100;
            }
            $roof_shade_south_production_rate = esc_attr(get_option('wf_solar_roof_shade_south_production_ratio') );
            if ($roof_shade_south_production_rate == " ") {
                $roof_direction_ratio = 0;
            }else{
                $roof_direction_ratio = $roof_shade_south_production_rate/100;
            }
        }elseif("South_East" == $roof_direction){
            $roof_shade_south_east = esc_attr(get_option('wf_solar_roof_shade_south_east') );
            if ($roof_shade_south_east == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_south_east/100;
            }
            $roof_shade_south_east_production_ratio = esc_attr(get_option('wf_solar_roof_shade_south_east_production_ratio') );
            if ($roof_shade_south_east_production_ratio == " ") {
                $roof_direction_ratio = 0;
            }else{
                $roof_direction_ratio = $roof_shade_south_east_production_ratio/100;
            }
        }elseif("East" == $roof_direction){
            $roof_shade_east = esc_attr(get_option('wf_solar_roof_shade_east') );
            if ($roof_shade_east == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_east/100;
            }
            $roof_shade_east_production_ratio = esc_attr(get_option('wf_solar_roof_shade_east_production_ratio') );
            if ($roof_shade_east_production_ratio == " ") {
                $roof_direction_ratio = 0;
            }else{
                $roof_direction_ratio = $roof_shade_east_production_ratio/100;
            }
        }elseif("South_West" == $roof_direction){
            $roof_shade_south_west = esc_attr(get_option('wf_solar_roof_shade_south_west') );
            if ($roof_shade_south_west == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_south_west/100;
            }
            $roof_shade_south_west_production_ratio = esc_attr(get_option('wf_solar_roof_shade_south_west_production_ratio') );
            if ($roof_shade_south_west_production_ratio == " ") {
                $roof_direction_ratio = 0;
            }else{
                $roof_direction_ratio = $roof_shade_south_west_production_ratio/100;
            }
        }elseif("West" == $roof_direction){
            $roof_shade_west = esc_attr(get_option('wf_solar_roof_shade_west') );
            if ($roof_shade_west == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_west/100;
            }
            $roof_shade_west_production_ratio = esc_attr(get_option('wf_solar_roof_shade_west_production_ratio') );
            if ($roof_shade_west_production_ratio == " ") {
                $roof_direction_ratio = 0;
            }else{
                $roof_direction_ratio = $roof_shade_west_production_ratio/100;
            }
        }

        $coefficient_system_cost = esc_attr(get_option('wf_solar_coefficient_system_cost') );
        $d_rate_factor = esc_attr(get_option('wf_solar_d_rate_factor') );


        $check_btn_click =trim($_GET["monthly_kwh"]);
        if($check_btn_click == "NULL"){  //TODO: this check needs to be fixed
            $bill_cost =trim($_GET["billcost"]);
            if (empty($bill_cost)) {
                $bill_cost = "500";
            }
            $kw_per_month = ($bill_cost-15)/$ind_rate;
            $annual_kwh = $kw_per_month*12;
            $ak_x_sv = $annual_kwh*$shaded_value;
            $ak_x_rdv = $annual_kwh*$roof_direction_value;
            $ak_sv_rdv = $annual_kwh + $ak_x_sv + $ak_x_rdv;
            $ak_sv_rdv_x_ov = $ak_sv_rdv*$offset_value;
            $system_size_kwh = $ak_sv_rdv_x_ov/365/4.5/$d_rate_factor;
            $annual_production_calc = ($system_size_kwh*1000)*$roof_direction_ratio;
            $annual_production = number_format(ceil($annual_production_calc));
        }else{
            $monthly_kwh =trim($_GET["monthly_kwh"]); //TODO: this should only pull for a bill entry method. AKA Manual KWH 
            $annual_kwh = $monthly_kwh*12;
            $ak_x_sv = $annual_kwh*$shaded_value;
            $ak_x_rdv = $annual_kwh*$roof_direction_value;
            $ak_sv_rdv = $annual_kwh + $ak_x_sv + $ak_x_rdv;
            $ak_sv_rdv_x_ov = $ak_sv_rdv*$offset_value;
            $system_size_kwh = $ak_sv_rdv_x_ov/365/4.5/$d_rate_factor;
            $annual_production_calc = ($monthly_kwh*12);
            $annual_production = number_format(ceil($annual_production_calc));
        }
        
        ?>
        <div style="text-align: center; padding-top: 30px;">
            <?php
            echo "Annual Production";
            echo "<br>";
            echo "<p style='color:#2eaae1; margin-top: 14px; font-weight: 700;'>" . $annual_production. " KWh </p>";
            
            ?>
        </div>
        <?php
        
    }
    
    public function wf_solar_system_size(){
        global $wpdb;
        $state = trim($_GET["state"]);
        if($state == 'IL'){
            $ind_rate = esc_attr(get_option('wf_solar_illinious_rate') );
            if (empty($ind_rate)) {
                $ind_rate = "0.1295";
            }
        }elseif($state == 'WI'){
            $ind_rate = esc_attr(get_option('wf_solar_wisconsin_rate') );
            if (empty($ind_rate)) {
                $ind_rate = "0.163";
            }
        }elseif($state == 'MN'){
            $ind_rate = esc_attr(get_option('wf_solar_minnesota_rate') );
            if (empty($ind_rate)) {
                $ind_rate = "0.163";
            }
        }else{
            $ind_rate = esc_attr(get_option('wf_solar_iowa_rate') );
            if (empty($ind_rate)) {
                $ind_rate = "0.1274";
            }
        }

        $roof_shaded =trim($_GET["roof_shaded"]);
        if (empty($roof_shaded)) {
            $roof_shaded= 0.5;
        }
        $kwh_offset =trim($_GET["kwh_offset"]);
        if (empty($kwh_offset)) {
            $kwh_offset= 0.1;
        }
        if("10" == $roof_shaded){
            $shaded_value = 0.1;
        }elseif("20" == $roof_shaded){
            $shaded_value = 0.2;
        }elseif("30" == $roof_shaded){
            $shaded_value = 0.3;
        }elseif("40" == $roof_shaded){
            $shaded_value = 0.4;
        }elseif("50" == $roof_shaded){
            $shaded_value = 0.5;
        }elseif("60" == $roof_shaded){
            $shaded_value = 0.6;
        }elseif("70" == $roof_shaded){
            $shaded_value = 0.7;
        }else{
            $shaded_value = 0;
        }
        
        if("10" == $kwh_offset){
            $offset_value = 0.1;
        }elseif("20" == $kwh_offset){
            $offset_value = 0.2;
        }elseif("30" == $kwh_offset){
            $offset_value = 0.3;
        }elseif("40" == $kwh_offset){
            $offset_value = 0.4;
        }elseif("50" == $kwh_offset){
            $offset_value = 0.5;
        }elseif("60" == $kwh_offset){
            $offset_value = 0.6;
        }elseif("70" == $kwh_offset){
            $offset_value = 0.7;
        }elseif("80" == $kwh_offset){
            $offset_value = 0.8;
        }elseif("90" == $kwh_offset){
            $offset_value = 0.9;
        }elseif("100" == $kwh_offset){
            $offset_value = 1.0;
        }
        $roof_direction = trim($_GET["roof_direction"]);
        
        if("South" == $roof_direction){
            $roof_shade_south = esc_attr(get_option('wf_solar_roof_shade_south') );
            if ($roof_shade_south == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_south/100;
            }
        }elseif("South_East" == $roof_direction){
            $roof_shade_south_east = esc_attr(get_option('wf_solar_roof_shade_south_east') );
            if ($roof_shade_south_east == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_south_east/100;
            }
        }elseif("East" == $roof_direction){
            $roof_shade_east = esc_attr(get_option('wf_solar_roof_shade_east') );
            if ($roof_shade_east == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_east/100;
            }
        }elseif("South_West" == $roof_direction){
            $roof_shade_south_west = esc_attr(get_option('wf_solar_roof_shade_south_west') );
            if ($roof_shade_south_west == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_south_west/100;
            }
        }elseif("West" == $roof_direction){
            $roof_shade_west = esc_attr(get_option('wf_solar_roof_shade_west') );
            if ($roof_shade_west == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_west/100;
            }
        }

        $coefficient_system_cost = esc_attr(get_option('wf_solar_coefficient_system_cost') );
        $d_rate_factor = esc_attr(get_option('wf_solar_d_rate_factor') );

        $check_btn_click =trim($_GET["monthly_kwh"]);
        if($check_btn_click == "NULL"){
            $bill_cost =trim($_GET["billcost"]);
            if (empty($bill_cost)) {
                $bill_cost = "500";
            }
            $kw_per_month = ($bill_cost-15)/$ind_rate;
            $annual_kwh = $kw_per_month*12;
            $ak_x_sv = $annual_kwh*$shaded_value;
            $ak_x_rdv = $annual_kwh*$roof_direction_value;
            $ak_sv_rdv = $annual_kwh + $ak_x_sv + $ak_x_rdv;
            $ak_sv_rdv_x_ov = $ak_sv_rdv*$offset_value;
            $system_size_kwh = $ak_sv_rdv_x_ov/365/4.5/$d_rate_factor;  //TODO difference here
            $round_to_two = round($system_size_kwh ,2);
        }else{
            $monthly_kwh =trim($_GET["monthly_kwh"]);
            if(!empty($monthly_kwh)){
                $annual_kwh = $monthly_kwh*12;
            }
            $annual_kwh = $monthly_kwh*12;
            $ak_x_sv = $annual_kwh*$shaded_value;
            $ak_x_rdv = $annual_kwh*$roof_direction_value;
            $ak_sv_rdv = $annual_kwh + $ak_x_sv + $ak_x_rdv;
            $ak_sv_rdv_x_ov = $ak_sv_rdv*$offset_value;
            $system_size_kwh = $ak_sv_rdv_x_ov/365/4.5/$coefficient_system_cost; //TODO: difference here
            $round_to_two = round($system_size_kwh ,2);
        }
        
        ?>
        <div style="text-align: center; padding-top: 30px;">
            <?php
            echo "System Size ";
            echo "<br>";
            echo "<p style='color:#2eaae1; margin-top: 14px; font-weight: 700;'>" .$round_to_two." ". "kW </p>"; 
            ?>
        </div>
        <?php
    }
    
    public function wf_solar_panels(){
        global $wpdb;
        $state = trim($_GET["state"]);
        if($state == 'IL'){
            $ind_rate = esc_attr(get_option('wf_solar_illinious_rate') );
            if (empty($ind_rate)) {
                $ind_rate = "0.1295";
            }
        }elseif($state == 'WI'){
            $ind_rate = esc_attr(get_option('wf_solar_wisconsin_rate') );
            if (empty($ind_rate)) {
                $ind_rate = "0.163";
            }
        }elseif($state == 'MN'){
            $ind_rate = esc_attr(get_option('wf_solar_minnesota_rate') );
            if (empty($ind_rate)) {
                $ind_rate = "0.163";
            }
        }else{
            $ind_rate = esc_attr(get_option('wf_solar_iowa_rate') );
            if (empty($ind_rate)) {
                $ind_rate = "0.1274";
            }
        }

        $roof_shaded =trim($_GET["roof_shaded"]);
        if (empty($roof_shaded)) {
            $roof_shaded= 0.5;
        }
        $kwh_offset =trim($_GET["kwh_offset"]);
        if (empty($kwh_offset)) {
            $kwh_offset= 0.1;
        }
        if("10" == $roof_shaded){
            $shaded_value = 0.1;
        }elseif("20" == $roof_shaded){
            $shaded_value = 0.2;
        }elseif("30" == $roof_shaded){
            $shaded_value = 0.3;
        }elseif("40" == $roof_shaded){
            $shaded_value = 0.4;
        }elseif("50" == $roof_shaded){
            $shaded_value = 0.5;
        }elseif("60" == $roof_shaded){
            $shaded_value = 0.6;
        }elseif("70" == $roof_shaded){
            $shaded_value = 0.7;
        }else{
            $shaded_value = 0;
        }
        
        if("10" == $kwh_offset){
            $offset_value = 0.1;
        }elseif("20" == $kwh_offset){
            $offset_value = 0.2;
        }elseif("30" == $kwh_offset){
            $offset_value = 0.3;
        }elseif("40" == $kwh_offset){
            $offset_value = 0.4;
        }elseif("50" == $kwh_offset){
            $offset_value = 0.5;
        }elseif("60" == $kwh_offset){
            $offset_value = 0.6;
        }elseif("70" == $kwh_offset){
            $offset_value = 0.7;
        }elseif("80" == $kwh_offset){
            $offset_value = 0.8;
        }elseif("90" == $kwh_offset){
            $offset_value = 0.9;
        }elseif("100" == $kwh_offset){
            $offset_value = 1.0;
        }
        $roof_direction = trim($_GET["roof_direction"]);
        
        if("South" == $roof_direction){
            $roof_shade_south = esc_attr(get_option('wf_solar_roof_shade_south') );
            if ($roof_shade_south == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_south/100;
            }
        }elseif("South_East" == $roof_direction){
            $roof_shade_south_east = esc_attr(get_option('wf_solar_roof_shade_south_east') );
            if ($roof_shade_south_east == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_south_east/100;
            }
        }elseif("East" == $roof_direction){
            $roof_shade_east = esc_attr(get_option('wf_solar_roof_shade_east') );
            if ($roof_shade_east == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_east/100;
            }
        }elseif("South_West" == $roof_direction){
            $roof_shade_south_west = esc_attr(get_option('wf_solar_roof_shade_south_west') );
            if ($roof_shade_south_west == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_south_west/100;
            }
        }elseif("West" == $roof_direction){
            $roof_shade_west = esc_attr(get_option('wf_solar_roof_shade_west') );
            if ($roof_shade_west == " ") {
                $roof_direction_value = 0;
            }else{
                $roof_direction_value = $roof_shade_west/100;
            }
        }

        $coefficient_system_cost = esc_attr(get_option('wf_solar_coefficient_system_cost') );
        $d_rate_factor = esc_attr(get_option('wf_solar_d_rate_factor') );

        $check_btn_click =trim($_GET["monthly_kwh"]);
        if($check_btn_click == "NULL"){
            $bill_cost =trim($_GET["billcost"]);
            if (empty($bill_cost)) {
                $bill_cost = "500";
            }
            $annual_bill = $bill_cost*12;
            $watt_per_panel = esc_attr(get_option('wf_solar_watts_per_panel') );
             if (empty($watt_per_panel)) {
                $watt_per_panel = 400;
            }
            $kw_per_month = ($bill_cost-15)/$ind_rate;
            $annual_kwh = $kw_per_month*12;
            $ak_x_sv = $annual_kwh*$shaded_value;
            $ak_x_rdv = $annual_kwh*$roof_direction_value;
            $ak_sv_rdv = $annual_kwh + $ak_x_sv + $ak_x_rdv;
            $ak_sv_rdv_x_ov = $ak_sv_rdv*$offset_value;
            $system_size_kwh = $ak_sv_rdv_x_ov/365/4.5/$d_rate_factor;
            $system_size_watts = $system_size_kwh*1000;
            $panels = $system_size_watts/$watt_per_panel;
            $panels_ceil = ceil($panels);
        }else{
            $watt_per_panel = esc_attr(get_option('wf_solar_watts_per_panel') );
            if (empty($watt_per_panel)) {
                $watt_per_panel = 400;
            }
            $monthly_kwh =trim($_GET["monthly_kwh"]);
            if(!empty($monthly_kwh)){
                $annual_kwh = $monthly_kwh*12;
            }
            $annual_kwh = $monthly_kwh*12;
            $ak_x_sv = $annual_kwh*$shaded_value;
            $ak_x_rdv = $annual_kwh*$roof_direction_value;
            $ak_sv_rdv = $annual_kwh + $ak_x_sv + $ak_x_rdv;
            $ak_sv_rdv_x_ov = $ak_sv_rdv*$offset_value;
            $system_size_kwh = $ak_sv_rdv_x_ov/365/4.5/$coefficient_system_cost;
            $system_size_watts = $system_size_kwh*1000;
            $panels = $system_size_watts/$watt_per_panel;
            $panels_ceil = ceil($panels);
            
        }
        
        ?>
        <div style="text-align: center; padding-top: 30px;">
            <?php
            echo "Panels";
            echo "<br>";
            echo "<p style='color:#2eaae1; margin-top: 14px; font-weight: 700;'>" . $panels_ceil . "</p>";
            ?>
        </div>
        <?php
        
    }

}
new Front_Class();