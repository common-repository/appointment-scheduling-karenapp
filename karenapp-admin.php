<?php

class KarenappEditorButton
{
    /**
     * Add hooks according to screen.
     *
     * @param WP_Screen $screen Data about current screen.
     */
    public function add_hooks() {
        wp_enqueue_style( 'karenapp-admin', plugins_url( 'css/admin.css', __FILE__ ) );
        
        add_filter( 'mce_external_plugins', array( $this, 'mce_external_plugins' ) );
		add_filter( 'mce_buttons', array( $this, 'mce_buttons' ) );
        
        add_action( 'admin_notices', array( $this, 'handle_editor_view_js' ) );
        add_action( 'media_buttons', array( $this, 'karenapp_schedule_button' ), 9999 );
    }

	function mce_external_plugins( $plugin_array ) {
		$plugin_array['karenapp_schedule'] =  plugins_url( 'js/tinymce-plugin-schedule-button.js', __FILE__ );
		return $plugin_array;
	}

	function mce_buttons( $buttons ) {
		return array_merge(
			$buttons,
			array( 'karenapp_schedule' )
		);
	}

    function karenapp_schedule_button() {
        $title = __( 'Add karenapp schedule', 'karenapp' );
        $site_url = esc_url( admin_url( "/admin-ajax.php?post_id={$iframe_post_id}&action=karenapp_schedule_builder" ) );
        ?>

        <button id="insert-karenapp-schedule" class="button" title="<?php echo esc_attr( $title ); ?>" href="javascript:;">
            <span class="karenapp-schedule-icon"></span> <?php echo esc_html( $title ); ?>
        </button>

        <?php
    }

    function handle_editor_view_js() {
        $current_screen = get_current_screen();

		if ( ! isset( $current_screen->id ) || $current_screen->base !== 'post' ) {
			return;
        }
        
		add_action( 'admin_print_footer_scripts', array( $this, 'editor_view_js_templates' ), 1 );
		wp_enqueue_script( 'karenapp-editor-view', plugins_url( 'js/editor-view.js', __FILE__ ), array( 'wp-util', 'jquery', 'quicktags' ), KAREN_PLUGIN_VERSION, true );
		wp_localize_script( 'karenapp-editor-view', 'karenappScheduleEditorView', array(
			'inline_editing_style' => plugins_url( 'css/editor-inline-editing-style.css?ver=' . KAREN_PLUGIN_VERSION, __FILE__ ),
			'labels'      => array(
				'submit_button_text'  => __( 'Submit', 'jetpack' ),
				'required_field_text' => apply_filters( 'jetpack_required_field_text', __( '(required)', 'jetpack' ) ),
				'edit_close_ays'      => __( 'Are you sure you\'d like to stop editing this form without saving your changes?', 'jetpack' ),
			)
		) );
		add_editor_style( plugins_url( 'css/editor-style.css', __FILE__ ) );
    }

    function editor_view_js_templates() {
		?>
<script type="text/html" id="tmpl-karenapp-schedule">
	<div class="card" class='contact-form commentsblock' onsubmit="return false;">
        {{{ data.body }}}
	</div>
</script>

<script type="text/html" id="tmpl-karenapp-schedule-editor-inline">
    <h1 id="form-settings-header" class="grunion-section-header"><?php esc_html_e( 'Schedule Settings', 'karenapp' ); ?></h1>
    <section class="card grunion-form-settings" aria-labelledby="form-settings-header">
        <label><?php esc_html_e( 'What is the URL of your karenapp schedule?', 'karenapp' ); ?>
            <input type="text" placeholder="https://karenapp.io/example-yoga-studio" name="schedule_url" value="{{ data.schedule_url }}" />

        </label>

        <label><?php esc_html_e( 'Iframe Width', 'karenapp' ); ?>
            <input type="text" placeholder="300" name="schedule_width" value="{{ data.schedule_width }}" />
        </label>

        <label><?php esc_html_e( 'Iframe Height', 'karenapp' ); ?>
            <input type="text" placeholder="300" name="schedule_height" value="{{ data.schedule_height }}" />
        </label>

    </section>
    <section class="buttons">
        <?php submit_button( esc_html__( 'Update Settings', 'karenapp' ), 'primary', 'submit', false ); ?>
        <?php submit_button( esc_html__( 'Cancel', 'karenapp' ), 'delete', 'cancel', false ); ?>
    </section>
</script>
        <?php
    }
}


$editor_button = new KarenappEditorButton();
add_action( 'init', array( $editor_button, 'add_hooks' ) );
