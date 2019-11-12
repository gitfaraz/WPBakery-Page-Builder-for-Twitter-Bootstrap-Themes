<?php
//***https://wpbakery.com/features/extend-wpbakery-page-builder/
//***https://kb.wpbakery.com/docs/inner-api/vc_map/
//**https://webdesign.tutsplus.com/tutorials/how-to-extend-visual-composer-with-custom-elements--cms-27834
//***http://www.wpelixir.com/how-to-create-new-element-in-visual-composer/
//*https://www.oodlestechnologies.com/blogs/How-to-create-a-custom-element-in-the-visual-composer/
//***https://kb.wpbakery.com/docs/developers-how-tos/


add_action('init', 'bs_container'); 
function bs_container() {
	class WPBakeryShortCode_Bs_Container extends WPBakeryShortCodesContainer{
		 
		// Element Init
		public function __construct(){					
			add_action('vc_before_init', [$this, 'mapping']);
			add_shortcode('bs_container', [$this, 'output']);
		}
		
		public function mapping(){
			vc_map(array(
				'name' => esc_html__('Container', 'js_composer'),
				'base' => 'bs_container',
				'class' => 'vc_main-sortable-element',
				'show_settings_on_create' => false,
				'category' => esc_html__('Bootstrap', 'js_composer'),
				'icon' => 'vc_icon-vc-section',
				'description' => esc_html__('Group multiple rows in container', 'js_composer'),
				'is_container' => true,
				'js_view' => 'VcSectionView',
//				'as_parent' => array(
//					'only' => '',
//				),
//				'as_child' => array(
//					'only' => '', // Only root
//				),
				'params' => array(
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Full height section?', 'js_composer' ),
						'param_name' => 'full_height',
						'description' => esc_html__( 'If checked section will be set to full height.', 'js_composer' ),
						'value' => array( esc_html__( 'Yes', 'js_composer' ) => 'yes' ),
					),
					array(
						'type' => 'el_id',
						'heading' => esc_html__( 'Section ID', 'js_composer' ),
						'param_name' => 'el_id',
						'description' => sprintf( esc_html__( 'Enter element ID (Note: make sure it is unique and valid according to %sw3c specification%s).', 'js_composer' ), '<a href="https://www.w3schools.com/tags/att_global_id.asp" target="_blank">', '</a>' ),
					),
					array(
						'type' => 'checkbox',
						'heading' => esc_html__( 'Disable section', 'js_composer' ),
						'param_name' => 'disable_element',
						// Inner param name.
						'description' => esc_html__( 'If checked the section won\'t be visible on the public side of your website. You can switch it back any time.', 'js_composer' ),
						'value' => array( esc_html__( 'Yes', 'js_composer' ) => 'yes' ),
					),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Extra class name', 'js_composer' ),
						'param_name' => 'el_class',
						'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
					),
					array(
						'type' => 'css_editor',
						'heading' => esc_html__( 'CSS box', 'js_composer' ),
						'param_name' => 'css',
						'group' => esc_html__( 'Design Options', 'js_composer' ),
					)
				)
			));
			
			vc_map_update( 'vc_section', [
//				'name' => esc_html__( 'Section', 'js_composer' ),
//				'category' => esc_html__( 'Bootstrap', 'js_composer' ),
				'as_parent' => [
					'only' => 'vc_row,bs_container'
				]
			]);
		}
		
		public function output($atts, $content = NULL ) {
			$output = ('<div class="container">'.do_shortcode( shortcode_unautop( $content ) ).'</div>');			
			return $output;
		}
	}
	
	new WPBakeryShortCode_Bs_Container();
}