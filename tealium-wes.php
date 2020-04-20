<?php
/**
* Plugin Name: Tealium - WES
* Description: Tealium Plugin Extension adds standard datalayer values
* Version: 1.1
* Author: Brent Maggard
*/
global $Tealium_WES;
$Tealium_WES = new Tealium_WES;

//create admin page
add_action( 'admin_menu', 'tealium_wes_menu' );

function tealium_wes_menu() {
	$page_title = 'Tealium - WES';
	$menu_title = 'Tealium - WES';
	$capability = 'manage_options';
	$menu_slug  = 'tealium-wes';
	$function   = 'tealium_wes_page';
	$icon_url   = 'dashicons-media-code';
	$position   = 40;

	add_options_page(
		$page_title,
		$menu_title,
		$capability,
		$menu_slug,
		$function,
		$icon_url,
		$position
	);

	add_action( 'admin_init', 'update_tealium_wes' );
}

function update_tealium_wes() {
	//School Short Name
	register_setting( 'tealium-wes-settings', 'school_short_name' );
	//default page category
	register_setting( 'tealium-wes-settings', 'wes_page_category' );
	register_setting( 'tealium-wes-settings', 'wes_page_type' );
	//url based values
	register_setting( 'tealium-wes-settings', 'wes_page_url' );
	register_setting( 'tealium-wes-settings', 'wes_program_code_url' );
	register_setting( 'tealium-wes-settings', 'wes_page_category_url' );
	register_setting( 'tealium-wes-settings', 'wes_page_type_url' );
}

function tealium_wes_page() {
	?>
	<div class="wrap" id="tealium-wes">
		<h1 class="wp-heading-inline">Tealium - WES</h1>
		<form method="post" action="options.php">
		<?php 
			settings_fields( 'tealium-wes-settings' );
			do_settings_sections( 'tealium-wes-settings' );
			$urls = get_option('wes_page_url');
			$program_codes = get_option('wes_program_code_url');
			$page_categories = get_option('wes_page_category_url');
			$page_types = get_option('wes_page_type_url');

			if(!empty($urls) && !empty($program_codes) && !empty($page_categories) && !empty($page_types)) {

				$final = array();

				foreach ($urls as $url => $key) {
					$final[$key] = array(
						'url' => $urls[$url],
						'program'  => $program_codes[$url],
						'page_category' => $page_categories[$url],
						'page_type'    => $page_types[$url]
					);
				}

			}
		?>

		<table class="form-table">

			<tr>
                <th colspan="4" style="background:#23282d;padding:10px;color:#fff"><strong>Default Values</strong></th>
			</tr>
				
			<tr valign="top">
				<th scope="row">School Short Name:</th>
				<td><input type="text" name="school_short_name" value="<?php echo get_option( 'school_short_name' ); ?>"/></td>
			</tr>
			<tr valign="top">
				<th scope="row">Default Page Category:</th>
				<td><input type="text" name="page_category" value="<?php echo get_option( 'wes_page_category' ); ?>"/></td>
			</tr>
			<tr valign="top">
				<th scope="row">Default Page Type:</th>
				<td><input type="text" name="page_type" value="<?php echo get_option( 'wes_page_type' ); ?>"/></td>
			</tr>

			<tr>
                <th colspan="4" style="background:#23282d;padding:10px;color:#fff"><strong>Url Based Values</strong></th>
			</tr>
			<tr>
                <th colspan="4" style=""><small><em>If you need to overwrite the values of the home page, use "/" as URL. thank you, you are the best.</em></small></th>
			</tr>
			<tr valign="top">
				<td>URL</td><td>Program Code</td><td>Page Type</td><td>Page Category</td>
			</tr>
			<?php 
			if (!empty($final)) {
				foreach ($urls as $url => $key) {
					echo '<tr class="urls">';
					echo '<td width="25%"><input type="text" name="wes_page_url[]" value="'.$urls[$url].'"/></td>';
					echo '<td width="25%"><input type="text" name="wes_program_code_url[]" value="'.$program_codes[$url].'"/></td>';
					echo '<td width="25%"><input type="text" name="wes_page_type_url[]" value="'.$page_types[$url].'"/></td>';
					echo '<td width="25%"><input type="text" name="wes_page_category_url[]" value="'.$page_categories[$url].'"/> <button class="add-id">+</button><button class="kill-id">-</button></div></td>';
					echo '</tr>';
				}
				
			} else { ?>

			<tr class="urls">
				<td width="25%"><input type="text" name="wes_page_url[]" value=""/></td>
				<td width="25%"><input type="text" name="wes_program_code_url[]" value=""/></td>
				<td width="25%"><input type="text" name="wes_page_type_url[]" value=""/></td>
				<td width="25%"><input type="text" name="wes_page_category_url[]" value=""/> <button class="add-id">+</button><button class="kill-id">-</button></div></td>
			</tr>

			<?php } ?>
			
			<script type="text/javascript">
                    jQuery.noConflict();
                    jQuery(document).ready(function ($) {
                        function tr_count(){
                            $count = $('tr.urls').length;
                            
                            if($count == 1) {
                                //console.log('if')
                                $('.kill-id').attr('disabled', 'disabled').css('opacity', '0.375');
                            } else {
                                $('.kill-id').removeAttr('disabled', 'disabled').css('opacity', '1');
                            }
                            
                        }

                        function clone(){
                            $('.add-id').click(function(){
                                $parent = $(this).parents('tr');

                                $parent.clone(true).addClass('cloned').find("input:text").val("").end().insertAfter($parent);
                                tr_count()

                                return false;
                            })
                        }
                        
                        function kill(){
                            $('.kill-id').click(function(){
                                $parent = $(this).parents('tr');

                                $parent.find("input:text").val("");
                                $parent.remove();

                                tr_count()
                                
                                return false;
                            })
                        }

                        tr_count();
                        clone(); 
                        kill(); 
                    });
                </script>
			

			
		</table>
		<?php submit_button(); ?>
		</form>
	</div>
	<?php
}
//end create admin page

class Tealium_WES {

	private $textdomain       = 'Tealium_WES';
	private $required_plugins = array( 'tealium' );

	function have_required_plugins() {
		if ( empty( $this->required_plugins ) ) {
			return true;
		}
		$active_plugins = (array) get_option( 'active_plugins', array() );
		if ( is_multisite() ) {
			$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		}
		foreach ( $this->required_plugins as $key => $required ) {
			$required = ( ! is_numeric( $key ) ) ? "{$key}/{$required}.php" : "{$required}/{$required}.php";
			if ( ! in_array( $required, $active_plugins ) && ! array_key_exists( $required, $active_plugins ) ) {
				return false;
			}
		}
		return true;
	}


	function __construct() {
		if ( ! $this->have_required_plugins() ) {
			return;
		}

		function addToDataObject() {
			global $utagdata;

			$url       = $_SERVER['REQUEST_URI'];
			$urlString = parse_url( $url, PHP_URL_PATH );

			// Add a timestamp to the data layer
			$utagdata['timestamp'] = time();

			//GET allocadiaID FROM URL TID
			if ( isset($_GET['tid']) && $_GET['tid'] ) {
				$utagdata['allocadiaid'] = $_GET['tid'];
			}

			//
			//VARS
			//
			//partner name value from plugin settings
			$partner_name = esc_html( get_option( 'school_short_name' ) );
			//default page category from plugin settings
			$page_category = esc_html( get_option( 'wes_page_category' ) );
			//default page category from plugin settings
			$page_type = esc_html( get_option( 'wes_page_type' ) );
			//landing pages post types
			$lp_post_types = array( 'landing-pages', 'landing-page', 'grp_pages', 'landing-pages-new' );
			//program value from posts field
			$program_name = get_post_meta( get_the_ID(), 'program_code', true );
			//url based values
			$urls = get_option('wes_page_url');
			$program_codes = get_option('wes_program_code_url');
			$page_categories = get_option('wes_page_category_url');
			$page_types = get_option('wes_page_type_url');

			if(!empty($urls) && !empty($program_codes) && !empty($page_categories) && !empty($page_types)) {

				$final = array();

				foreach ($urls as $url => $key) {
					$final[$key] = array(
						'url' => $urls[$url],
						'program'  => $program_codes[$url],
						'page_category' => $page_categories[$url],
						'page_type'    => $page_types[$url]
					);
				}
			}
			
			//page category at page level
			//$tealium_page_category = get_post_meta( get_the_ID(), 'tealium_page_category', true );

			//
			//UTAG DATA
			//
			$utagdata['partner_name'] = $partner_name;
			$utagdata['page_category'] = ''; //Only used is $pageType = landing page
			$utagdata['page_name']     = get_the_title();
			//if no program set in post, load default value
			if ( $program_name ) {
				$utagdata['program_name'] = $program_name;
			} else {
				$utagdata['program_name'] = $partner_name . '-brand';
			}
			//$utagdata['page_section'] = ""; // Removed Do not think it is Used
			$utagdata['search_keyword'] = '';
			$utagdata['search_results'] = '';

			if ( ( is_home() ) || ( is_front_page() ) ) {
				$utagdata['page_type'] = 'home';
			} elseif ( is_search() ) {
				global $wp_query;

				// Collect search and result data
				$searchQuery = get_search_query();
				$searchCount = $wp_query->found_posts;

				// Add to udo
				$utagdata['page_type']      = 'search';
				$utagdata['search_keyword'] = $searchQuery;
				$utagdata['search_results'] = $searchCount;
			} elseif ( preg_match( '/thank-you/', $urlString, $matches ) ) {
				$utagdata['page_type'] = 'thankyou';
				if ( isset($_GET['orderid']) && $_GET['orderid'] ) {
					$utagdata['order_id'] = $_GET['orderid'];
				}
				if ( isset($_GET['p']) && $_GET['p'] ) {
					$utagdata['program_name'] = $_GET['p'];
				}
				if ( isset($_GET['lid']) && $_GET['lid'] ) { #GETS LEAD ID FROM URL LID VARIABLE
					$utagdata['leadID'] = $_GET['lid'];
				} else { #IF LID NOT PRESENT SEE IF CAN FIND LEAD ID AT END OF URL (LEGACY)
					if ( preg_match( '/\d{5,100}/', $urlString, $lID ) ) {
						$utagdata['leadID'] = $lID[0];
					}
				}
				if ( isset($_GET['lp']) && $_GET['lp'] === 'true' ) {
					$utagdata['page_category'] = 'Landing Page';
				}
			} elseif ( in_array( $utagdata['pageType'], $lp_post_types ) ) {
				$utagdata['page_type']     = 'Landing Page';
				$utagdata['page_category'] = 'Landing Page';
				
			}   else {
				//set default value if not
				//home || search || thanks page || LP content type
				//default page_type value from plugin
				if ($page_type) {
					$utagdata['page_type'] = $page_type;
				} else {
					//if nothing is set as default
					//then load a default value
					$utagdata['page_type'] = 'content';
				}
				//default page_category value from plugin
				if ($page_category) {
					$utagdata['page_category'] = $page_category;
				}
				//
			}

			//urls based content
			if (!empty($final)) {
				$current_url = strtok($_SERVER["REQUEST_URI"], '?');
				if($current_url != '/') {
					$current_url = substr($current_url, 1, -1);
				}
				foreach ($urls as $url => $key) {

					if($urls[$url] == $current_url) {
						//echo 'is';
						if($page_types[$url] !=''){
							$utagdata['page_type'] = $page_types[$url];
						}
						if($page_categories[$url] !=''){
							$utagdata['page_category'] = $page_categories[$url];
						}
						if($program_codes[$url] !='') {
							$utagdata['program_name'] = $program_codes[$url];
						}
					} 
				}
			}
			
			if ( isset($utagdata['pageType']) && is_user_logged_in()) {
				echo "<script>console.log( 'Debug Objects: " . $utagdata['pageType'] . "' );</script>";
			}

		}

		add_action( 'tealium_addToDataObject', 'addToDataObject' );
		

		/*
		* Switch Tealium environment based on website URL

		function switchEnvironment() {
		global $tealiumtag;

		if ( get_site_url() == 'http://dev.mywebsite.com' ) {
			$tealiumtag = str_replace( '/prod/', '/dev/', $tealiumtag );
		}
		}
		add_action( 'tealium_tagCode', 'switchEnvironment' );*/
	}
}

//setting link
function tealium_settings_link( $links ) {
	$settings_link = '<a href="options-general.php?page=tealium-wes">' . __( 'Settings' ) . '</a>';
	array_push( $links, $settings_link );
	return $links;
}

$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'tealium_settings_link' );
