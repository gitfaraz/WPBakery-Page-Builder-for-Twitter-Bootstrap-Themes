<?php
//https://wpbakery.atlassian.net/wiki/display/VC/Change+CSS+Class+Names+in+Output
// Filter to replace default css class names for vc_row shortcode and vc_column

if($options->en && $isWPBPBActive){
	function custom_css_classes_for_vc_row_and_vc_column( $class_string, $tag) {
		global $options;
		
		$classes = explode(' ', $class_string);		
		//$classes[] = 'tag_'.$tag;
		
		$removeClasses = array_merge(explode(',', $options->rmc), ['vc_row']);
		foreach($removeClasses as $className){
			if(in_array($className, $classes)){
				$classes[array_search($className, $classes)] = NULL;
			}
		}
		
		$classes = array_filter($classes);
		
		if(in_array($tag, ['vc_row', 'vc_row_inner'])){
			array_unshift($classes, 'row');
		}
		
		if(in_array($tag, ['vc_btn'])){
			if(in_array('vc_general', $classes)){
				array_unshift($classes, 'btn');
			}
			
			foreach($classes as $i=>$className){
				if(!$className)continue;
				
				if(preg_match(('/vc_/'), $className)){
					$classes[$i] = NULL;
				}
			}
		}
		
		if(in_array($tag, ['vc_column', 'vc_column_inner'])){
			switch($options->bsv){
				case 4:
					$tokens = [
						['search'=>'vc_col-xs-offset-', 'replace'=>'offset-xs-'],
						['search'=>'vc_col-sm-offset-', 'replace'=>'offset-sm-'],
						['search'=>'vc_col-md-offset-', 'replace'=>'offset-md-'],
						['search'=>'vc_col-lg-offset-', 'replace'=>'offset-lg-'],
						['search'=>'vc_col-xl-offset-', 'replace'=>'offset-xl-'],
						//place the offset columns above this line
						['search'=>'vc_col-xs-', 'replace'=>'col-xs-'],
						['search'=>'vc_col-sm-', 'replace'=>'col-sm-'],
						['search'=>'vc_col-md-', 'replace'=>'col-md-'],
						['search'=>'vc_col-lg-', 'replace'=>'col-lg-'],
						['search'=>'vc_col-xl-', 'replace'=>'col-xl-'],
						['search'=>'vc_hidden-xs', 'replace'=>'d-xs-none'],
						['search'=>'vc_hidden-sm', 'replace'=>'d-sm-none'],
						['search'=>'vc_hidden-md', 'replace'=>'d-md-none'],
						['search'=>'vc_hidden-lg', 'replace'=>'d-lg-none'],
						['search'=>'vc_hidden-xl', 'replace'=>'d-xl-none'],
						['search'=>'vc_hidden', 'replace'=>'d-none'],
						['search'=>'vc_btn-', 'replace'=>'btn-']
					];
				break;
				
				case 3:
					$tokens = [
						['search'=>'vc_col-xs-offset-', 'replace'=>'col-xs-offset-'],
						['search'=>'vc_col-sm-offset-', 'replace'=>'col-sm-offset-'],
						['search'=>'vc_col-md-offset-', 'replace'=>'col-md-offset-'],
						['search'=>'vc_col-lg-offset-', 'replace'=>'col-lg-offset-'],
						['search'=>'vc_col-xl-offset-', 'replace'=>'col-xl-offset-'],
						//place the offset columns above this line
						['search'=>'vc_col-xs-', 'replace'=>'col-xs-'],
						['search'=>'vc_col-sm-', 'replace'=>'col-sm-'],
						['search'=>'vc_col-md-', 'replace'=>'col-md-'],
						['search'=>'vc_col-lg-', 'replace'=>'col-lg-'],
						['search'=>'vc_col-xl-', 'replace'=>'col-xl-'],
						['search'=>'vc_hidden-', 'replace'=>'hidden-'],
						['search'=>'vc_hidden', 'replace'=>'hidden'],
						['search'=>'vc_btn-', 'replace'=>'btn-']
					];
				break;
			}
			
			foreach($tokens as $token){
				foreach($classes as $i=>$className){
					if(!$className)continue;
					
					if(preg_match(('/'.$token['search'].'/'), $className)){
						$classes[$i] = preg_replace(('/'.$token['search'].'/'), $token['replace'], $className);
					}
				}				
			}
		}
		
		return implode(' ', array_filter($classes));
	}
	//vc_shortcodes_css_class
	add_filter( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'custom_css_classes_for_vc_row_and_vc_column', 10, 2 );
}

function dequeue_css() {
	global $options, $isWPBPBActive, $v;
	
	if($options->rmv && $isWPBPBActive){
		wp_dequeue_style('js_composer_front');
		wp_deregister_style('js_composer_front');
	}
  
  	if($options->bs && $options->css){
		$hooks = explode(PHP_EOL, $options->css);
		
		foreach($hooks as $i=>$hook){
			if(!trim($hook))continue;
			wp_enqueue_style('wpb_bootstrap_style'.($i?$i :''), trim($hook), ($i?('wpb_bootstrap_style'.(($i>1)?($i-1) :'')) :''), $v);
		}
	}  
}
add_action(($isWPBPBActive?'vc_base_register_front_css' :'wp_enqueue_scripts'), 'dequeue_css');

function dequeue_js(){
	global $options, $isWPBPBActive, $v;
	
	if($options->rmv && $isWPBPBActive){
		//wp_dequeue_script( 'wpb_composer_front_js' );
		//wp_deregister_script( 'wpb_composer_front_js' );
	}
  
  	if($options->bs && $options->js){
		$hooks = explode(PHP_EOL, $options->js);
		
		foreach($hooks as $i=>$hook){
			if(!trim($hook))continue;
			wp_enqueue_script('wpb_bootstrap_script'.($i?$i :''), trim($hook), array($i?('wpb_bootstrap_script'.(($i>1)?($i-1) :'')) :'jquery'), $v, true);
		}
	}
}
add_action(($isWPBPBActive?'vc_base_register_front_js' :'wp_enqueue_scripts'), 'dequeue_js');