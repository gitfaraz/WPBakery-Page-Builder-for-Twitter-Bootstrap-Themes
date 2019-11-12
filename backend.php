<?php
if ( !defined('CX_UVCFTBT_DONATE_LINK') ) {
	define('CX_UVCFTBT_DONATE_LINK', 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=K373C4BNSJYY2');
}

/**
 * Additional links on the plugin page
 */
function cx_uvcftbt_plugin_row_meta($links, $file) {
	if ($file == plugin_basename(__FILE__)) {
		$links[] = '<a href="' . CX_UVCFTBT_DONATE_LINK . '" target="_blank">'.__('Please Donate...', 'cx-wc-uvcftbt-page').'</a>';
		$links[] = '<a href="http://www.cyberxoft.com/product-category/wp-themes/" target="_blank">'.__('Get Upto 66% Discount on WordPress Themes and Plugins...', 'cx-wc-uvcftbt-page').'</a>';
	}
	
	return $links;
}

function vc_bootstrap_callback() {
	if (!current_user_can('manage_options'))return;
	
	global $options, $isWPBPBActive, $isReset;
	
	if($onsubmit = array_key_exists('frm', $_POST)){
		if(!$isReset){
			$v = $_POST['cx'];
			
			$v['bs'] = array_key_exists('bs', $v);
			$v['rmv'] = array_key_exists('rmv', $v);
			$v['en'] = array_key_exists('en', $v);
			
			$v = array_merge((array) $options, $v);
			
			if(!add_option ('vc-bootstrap', json_encode($v), '', false)){
				update_option ('vc-bootstrap', json_encode($v), false);
			}
			
			$options = json_decode(get_option('vc-bootstrap'), false);
			add_settings_error( 'vc_bootstrap_messages', 'vc_bootstrap_message', __( 'Settings Saved', 'vc-bootstrap' ), 'updated' );
		}else{
			add_settings_error( 'vc_bootstrap_messages', 'vc_bootstrap_message', __( 'Settings Restored to default', 'vc-bootstrap' ), 'updated' );
		}
	}
	
	settings_errors('vc_bootstrap_messages'); ?>

<div class="wrap">
  <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
  <table style="width:100%;">
    <tbody>
      <tr>
        <td style="width:80%; vertical-align:top;"><form action="/wp-admin/admin.php?page=vc-bootstrap" method="post">
            <input type="hidden" name="frm" value="vc-bootstrap">
            <table class="form-table" role="presentation" style="margin:0;">
              <tbody>
                <tr>
                  <th scope="row" style="padding-bottom:0;">&nbsp;</th>
                  <td style="padding-bottom:0;">Looking to quickly add Bootstrap to your project? Use BootstrapCDN, provided for free by the folks at <a href="https://www.bootstrapcdn.com/" target="_blank">StackPath</a>.</td>
                </tr>
                <?php if($isWPBPBActive){ ?>
                <tr>
                  <th scope="row" style="padding-bottom:0;">&nbsp;</th>
                  <td style="padding-bottom:0;"><label for="cxen">
                      <input name="cx[en]" type="checkbox" id="cxen" value="1"<?php if($options->en){ ?> checked<?php } ?>>
                      Apply Bootstrap CSS Classes to the website.</label></td>
                </tr>
                <tr>
                  <th scope="row" style="padding-bottom:0;">Probable Bootstrap Version</th>
                  <td style="padding-bottom:0;"><select name="cx[bsv]" id="bsv">
                      <option value="4"<?php if($options->bsv==4){echo ' selected';} ?>>v4.x</option>
                      <option value="3"<?php if($options->bsv==3){echo ' selected';} ?>>v3.x</option>
                    </select></td>
                </tr>
                <?php } ?>
                <tr>
                  <th scope="row">&nbsp;</th>
                  <td><label for="cxbs">
                      <input name="cx[bs]" type="checkbox" id="cxbs" value="1"<?php if($options->bs){ ?> checked<?php } ?>>
                      Add Following Bootstrap CSS and Javascript files to the website.</label>
                    <p class="description indicator-hint" style="margin:0; padding:0;">I am expecting that you are already using a WordPress Theme built over Bootstrap so the required files are already there but if not than you need to enable this option and provide the complete address of the Bootstrap CSS and Javascript file(s).</p></td>
                </tr>
                <tr>
                  <th scope="row"><label for="home">Bootstrap CSS (URL)</label></th>
                  <td><textarea name="cx[css]" id="cxcss" class="large-text code" rows="3"<?php if(!$options->bs){ ?> disabled<?php } ?>><?php echo $options->css; ?></textarea>
                    <p class="description indicator-hint" style="margin:0; padding:0;">Add Bootstrap and/or other CSS URL(s), as many as you like, but each in a new line. They will be added in the sequence they are listed.</p></td>
                </tr>
                <tr>
                  <th scope="row"><label for="home">Bootstrap Javascript (URL)</label></th>
                  <td><textarea name="cx[js]" id="cxjs" class="large-text code" rows="3"<?php if(!$options->bs){ ?> disabled<?php } ?>><?php echo $options->js; ?></textarea>
                    <p class="description indicator-hint" style="margin:0; padding:0;">Add Bootstrap and/or other Javascript URL(s), as many as you like, but each in a new line. They will be added in the sequence they are listed to the bottom of the page(s).</p></td>
                </tr>
                <?php if($isWPBPBActive){ ?>
                <tr>
                  <th scope="row">&nbsp;</th>
                  <td><label for="cxrmv">
                      <input name="cx[rmv]" type="checkbox" id="cxrmv" value="1"<?php if($options->rmv){ ?> checked<?php } ?>>
                      Remove WPBakery Page Builder Frontend CSS file.</label>
                    <p class="description indicator-hint" style="margin:0; padding:0;">You might want to enable this option in case if any CSS\Style conflict and check if it resolves.<br>
                      Enabling this will remove only the <a href="<?php echo get_site_url(); ?>/wp-content/plugins/js_composer/assets/css/js_composer.min.css" target="_blank"><?php echo get_site_url(); ?>/wp-content/plugins/js_composer/assets/css/js_composer.min.css</a> file the website and not any other WPBakery Page Builders CSS or Javascript file(s).</p></td>
                </tr>
                <tr>
                  <th scope="row"><label for="home">Remove WPBakery CSS Classes</label></th>
                  <td><textarea name="cx[rmc]" id="cxrmc" class="large-text code" rows="3"<?php if(!$options->rmc){ ?> disabled<?php } ?>><?php echo $options->rmc; ?></textarea>
                    <p class="description indicator-hint" style="margin:0; padding:0;">Comma separated WPBakery Page Builder CSS classes that you want removed from each web page. Example: vc_row,vc_row-fluid...</p></td>
                </tr>
                <?php } ?>
                <tr>
                  <th scope="row" style="padding-bottom:0;">&nbsp;</th>
                  <td style="padding-bottom:0;"><label for="rst">
                      <input name="rst" type="checkbox" id="rst" value="1">
                      Reset All Settings to default</label></td>
                </tr>
              </tbody>
            </table>
            <?php
	// output save settings button
	submit_button( 'Save Settings' );
	?>
          </form></td>
        <td style="width:20%; vertical-align:top;">&nbsp;</td>
      </tr>
    </tbody>
  </table>
</div>
<script type="text/javascript">
var f = function($){
	$('#cxbs').on('change', function(){
		var $this = $(this);
		
		if($this.prop('checked')){
			$('#cxcss').attr('disabled', false).removeClass('disabled');
			$('#cxjs').attr('disabled', false).removeClass('disabled');
		}else{
			$('#cxcss').attr('disabled', true).addClass('disabled');
			$('#cxjs').attr('disabled', true).addClass('disabled');
		}
	});
}(jQuery);
</script>
<?php
}

if($isWPBPBActive){
	function change_vc_button_colors() {
		$colors = WPBMap::getParam( 'vc_btn', 'color' );
		$sizes = WPBMap::getParam( 'vc_btn', 'size' );
		
//		echo '<!-- warraich -->';
//		var_dump($sizes);
		
		$colors['value'] = ([
			'Bootstrap - Primary' => '_primary btn-primary',
			'Bootstrap - Secondary' => '_secondary btn-secondary',
			'Bootstrap - Success' => '_success btn-success',
			'Bootstrap - Danger' => '_danger btn-danger',
			'Bootstrap - Warning' => '_warning btn-warning',
			'Bootstrap - Info' => '_info btn-info',
			'Bootstrap - Light' => '_light btn-light',
			'Bootstrap - Dark' => '_dark btn-dark',
			'Bootstrap - White' => '_white btn-white',
			'Bootstrap - Transparent' => '_transparent btn-transparent'
		]);
		
		$sizes['value'] = ([
			'Bootstrap - Large' => 'lg btn-lg',
			'Bootstrap - Normal' => 'md btn-md',
			'Bootstrap - Small' => 'sm btn-sm',
			'Bootstrap - Mini' => 'xl btn-xs',
			'Bootstrap - Block' => 'block btn-block'
		]);
		
		vc_update_shortcode_param( 'vc_btn', $colors );
		vc_update_shortcode_param( 'vc_btn', $sizes );
	}
	add_action('vc_after_init', 'change_vc_button_colors'); 
}

function vc_bootstrap_add_submenu_page() {
	global $isWPBPBActive;
	
	if($isWPBPBActive){
		add_submenu_page( VC_PAGE_MAIN_SLUG, 'WPBakery Page Builder Bootstrap Settings', 'Bootstrap Options', 'manage_options', 'vc-bootstrap', 'vc_bootstrap_callback');
	}else{
		add_menu_page('Bootstrap Options', 'Bootstrap Options', 'manage_options', 'vc-bootstrap', 'vc_bootstrap_callback', '');
	}
}

function pluginActionLinks($links){
	$link = '<a href="'.admin_url( 'admin.php?page=vc-bootstrap' ).'">' . __('Settings') . '</a>';
	array_unshift( $links, $link );
	return $links;
}

// plugins list page actions links
add_filter('plugin_action_links_'.$plugin_file, 'pluginActionLinks');
add_filter('plugin_row_meta', 'cx_uvcftbt_plugin_row_meta',10,2);
add_action(($isWPBPBActive?'vc_menu_page_build' :'admin_menu'), 'vc_bootstrap_add_submenu_page' );
add_action(($isWPBPBActive?'vc_network_menu_page_build' :'network_admin_menu'), 'vc_bootstrap_add_submenu_page' );
