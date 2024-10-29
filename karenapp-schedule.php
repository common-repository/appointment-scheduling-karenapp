<?php

/*
Plugin Name:    Appointment Scheduling Karenapp
Description:    Easily integrate your schedule in your website using Karen WordPress plugin. Using the plugin your schedule will be visible on your website. The visitor can book appointment with you, without leaving your website. 
Version:        0.1
Author:         Karen Devs
*/

define('KAREN_PLUGIN_VERSION', '0.1');


class KarenappSchedulePlugin 
{
    public function add_hooks()
    {
        add_shortcode( 'karenapp-schedule', array( $this, 'parse_karenapp_schedule' ));

        wp_enqueue_script( 'karenapp_schedule', plugins_url( 'js/schedule.min.js', __FILE__ ), array( 'jquery' ), KAREN_PLUGIN_VERSION, true );
        wp_enqueue_style( 'karenapp_frontend', plugins_url( 'css/schedule-frontend.min.css', __FILE__), array (), KAREN_PLUGIN_VERSION );
    }

    function parse_karenapp_schedule ($attrs)
    {        
        $karenapp_settings = get_option('karenapp-settings');
        $schedule_url = is_array($attrs) && array_key_exists('schedule_url', $attrs) ? $attrs['schedule_url'] : $karenapp_settings['karenapp_schedule_url'];

        if ($schedule_url == null || empty($schedule_url))
            return '<div class="karenapp-schedule">No schedule URL defined.</div>';

        ////////////////
        $schedule_width = is_array($attrs) && array_key_exists('schedule_width', $attrs) ? $attrs['schedule_width'] : $karenapp_settings['karenapp_schedule_width'];

        $schedule_height = is_array($attrs) && array_key_exists('schedule_height', $attrs) ? $attrs['schedule_height'] : $karenapp_settings['karenapp_schedule_height'];

        $base_url_iframe = substr($schedule_url,0,28);
        $base_url_copy = substr($schedule_url,0,19);
        $except_base_url_copy = substr($schedule_url,20);

        if($base_url_iframe == "https://app.karenapp.io/user"){
            $customize_url = $schedule_url;
        }
        else if($base_url_copy == "https://karenapp.io"){ 
            $customize_url = "https://app.karenapp.io/user/".$except_base_url_copy;
        }
        else{
            $customize_url = $schedule_url;
        }

        return '<iframe src="'.$customize_url.'" style="border:0px #ffffff none;"  scrolling="no" frameborder="1" marginheight="0px" marginwidth="0px" height="'.$schedule_width.'px" width="'.$schedule_width.'px" allowfullscreen></iframe>';
        /////////////////
    }
}


$karenappSchedulePlugin = new KarenappSchedulePlugin();
add_action( 'init', array( $karenappSchedulePlugin, 'add_hooks' ) );


if ( is_admin() ) {
	require_once plugin_dir_path( __FILE__ ) . 'karenapp-admin.php';
} 
