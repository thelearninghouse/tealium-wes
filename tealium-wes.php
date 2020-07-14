<?php
/**
* Plugin Name: Tealium - WES
* Description: Tealium Plugin Extension adds standard datalayer values. Sitewide values, custom values with urls + wildcards
* Version: 1.1.2
* Author: Brent Maggard
*/
global $Tealium_WES;
$Tealium_WES = new Tealium_WES;

//create admin page
function tealium_wes_menu() {
	$page_title = 'Tealium - WES';
	$menu_title = 'Tealium - WES';
	$capability = 'manage_options';
	$menu_slug  = 'tealium-wes';
	$function   = 'tealium_wes_page';
	$icon_url   = 'dashicons-media-code';
	$position   = 50;

	add_options_page($page_title,$menu_title,$capability,$menu_slug,$function,$position);

	add_action( 'admin_init', 'update_tealium_wes' );
}

add_action( 'admin_menu', 'tealium_wes_menu' );

function update_tealium_wes() {
	//default values
	register_setting( 'tealium-wes-settings', 'wes_program_name' );
	register_setting( 'tealium-wes-settings', 'wes_program_code' );
	register_setting( 'tealium-wes-settings', 'wes_page_category' );
	register_setting( 'tealium-wes-settings', 'wes_page_type' );
	//url based values
	register_setting( 'tealium-wes-settings', 'wes_page_url' );
	register_setting( 'tealium-wes-settings', 'wes_program_code_url' );
	register_setting( 'tealium-wes-settings', 'wes_program_name_url' );
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
			$program_names = get_option('wes_program_name_url');
			$page_categories = get_option('wes_page_category_url');
			$page_types = get_option('wes_page_type_url');

			if(!empty($urls) && !empty($program_codes)) {

				$final = array();

				foreach ($urls as $url => $key) {
					$final[$key] = array(
						'url' => $urls[$url],
						'code'  => $program_codes[$url],
						'name'  => $program_names[$url],
						'page_category' => $page_categories[$url],
						'page_type'    => $page_types[$url]
					);
				}

			}
		?>

		<table class="form-table">

			<tr>
        <th colspan="5" style="background:#23282d;padding:10px;color:#fff"><strong>Default Values</strong></th>
			</tr>

			<tr>
      	<th colspan="5" style="">
					<ul style="margin:0;list-style: inside;margin-left:20px">
						<li><small><em>Default values applies sitewide except for:</em></small>
							<ul style="margin:5px 0;list-style: inside;margin-left:20px">
								<li><small><em>Home Page: 'page_type' is set to be 'home'</em></small></li>
								<li><small><em>Landing Page Post Type: 'page_type' and 'page_category' are set to be 'Landing Page'</em></small></li>
								<li><small><em>Search Results Page: 'page_type', 'search_keyword' and 'search_results' have default search specific values</em></small></li>
								<li><small><em>Thank You Page: 'page_category' is set to 'Landing Page', if the lead come from a LP page</em></small></li>
							</ul>
						</li>
					</ul>
				</th>
			</tr>

			<tr valign="top">
				<th scope="row">Default Program Name:</th>
				<td><input type="text" name="wes_program_name" value="<?php echo get_option( 'wes_program_name' ); ?>"/></td>
			</tr>
			<tr valign="top">
				<th scope="row">Default Program Code:</th>
				<td><input type="text" name="wes_program_code" value="<?php echo get_option( 'wes_program_code' ); ?>"/></td>
			</tr>
			<tr valign="top">
				<th scope="row">Default Page Category:</th>
				<td><input type="text" name="wes_page_category" value="<?php echo get_option( 'wes_page_category' ); ?>"/></td>
			</tr>
			<tr valign="top">
				<th scope="row">Default Page Type:</th>
				<td><input type="text" name="wes_page_type" value="<?php echo get_option( 'wes_page_type' ); ?>"/></td>
			</tr>

			<tr>
	      <th colspan="5" style="background:#23282d;padding:10px;color:#fff"><strong>Url Based Values</strong></th>
			</tr>
			<tr>
      	<th colspan="5" style="">
					<ul style="margin:0;list-style: inside;margin-left:20px">
						<li><small><em>If you need to overwrite the values of the home page, use "/" as URL.</em></small></li>
						<li><small><em>For any other page, you don't need to add a "/" at the beginning or the end, in the URL field</em></small></li>
						<li><small><em>You can use a wildcard "/*" at the end, that will fire the values in any child-page and in the parent page too</em></small></li>
					</ul>
				</th>
			</tr>
			<tr valign="top">
				<td>URL</td><td>Program Name</td><td>Program Code</td><td>Page Type</td><td>Page Category</td>
			</tr>
			<?php
			if (!empty($final)) {
				foreach ($urls as $url => $key) {
					echo '<tr class="urls">';
					echo '<td width="18%"><input type="text" name="wes_page_url[]" value="'.$urls[$url].'"/></td>';
					echo '<td width="18%"><input type="text" name="wes_program_name_url[]" value="'.$program_names[$url].'"/></td>';
					echo '<td width="18%"><input type="text" name="wes_program_code_url[]" value="'.$program_codes[$url].'"/></td>';
					echo '<td width="18%"><input type="text" name="wes_page_type_url[]" value="'.$page_types[$url].'"/></td>';
					echo '<td width="28%"><input type="text" name="wes_page_category_url[]" value="'.$page_categories[$url].'"/> <button class="add-id">+</button><button class="kill-id">-</button></td>';
					echo '</tr>';
				}

			} else { ?>

			<tr class="urls">
				<td width="18%"><input type="text" name="wes_page_url[]" value=""/></td>
				<td width="18%"><input type="text" name="wes_program_name_url[]" value=""/></td>
				<td width="18%"><input type="text" name="wes_program_code_url[]" value=""/></td>
				<td width="18%"><input type="text" name="wes_page_type_url[]" value=""/></td>
				<td width="28%"><input type="text" name="wes_page_category_url[]" value=""/> <button class="add-id">+</button><button class="kill-id">-</button></td>
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

		function wes_startsWith ($string, $startString) {
			$len = strlen($startString);
			return (substr($string, 0, $len) === $startString);
		}

		function wes_endsWith($string, $endString) {
			$len = strlen($endString);
			if ($len == 0) {
				return true;
			}
			return (substr($string, -$len) === $endString);
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
			$partner_name = get_option( 'tealiumProfile' );
			//default program name
			$program_name = esc_html( get_option( 'wes_program_name' ) );
			//default program code
			$program_code = esc_html( get_option( 'wes_program_code' ) );
			//default page category from plugin settings
			$page_category = esc_html( get_option( 'wes_page_category' ) );
			//default page category from plugin settings
			$page_type = esc_html( get_option( 'wes_page_type' ) );
			//landing pages post types
			$lp_post_types = array( 'landing-pages', 'landing-page', 'grp_pages', 'landing-pages-new' );
			//program code from posts field
			$program_code_post = get_post_meta( get_the_ID(), 'program_code', true );
			//url based values
			$urls = get_option('wes_page_url');
			$program_names = get_option('wes_program_name_url');
			$program_codes = get_option('wes_program_code_url');
			$page_categories = get_option('wes_page_category_url');
			$page_types = get_option('wes_page_type_url');

			if(!empty($urls) && !empty($program_codes) && !empty($page_categories) && !empty($page_types)) {

				$final = array();

				foreach ($urls as $url => $key) {
					$final[$key] = array(
						'url' => $urls[$url],
						'name'  => $program_names[$url],
						'code'  => $program_codes[$url],
						'page_category' => $page_categories[$url],
						'page_type'    => $page_types[$url]
					);
				}
			}
			//
			//UTAG DATA
			//
			$utagdata['partner_name'] = $partner_name;
			$utagdata['page_category'] = ''; //Only used is $pageType = landing page
			$utagdata['page_name']     = get_the_title();

			//if no program set in post, load default value from admin, then build a default value
			if ( $program_code_post ) {
				$utagdata['program_name'] = $program_code_post;
			} elseif ($program_name){
				$utagdata['program_name'] = $program_name;
			} else {
				$utagdata['program_name'] = $partner_name . '-brand';
			}

			//program code
			if ( $program_code_post ) {
				$utagdata['program_code'] = $program_code_post;
			} elseif ($program_code){
				$utagdata['program_code'] = $program_code;
			} else {
				$utagdata['program_code'] = $partner_name . '-brand';
			}

			//$utagdata['page_section'] = ""; // Removed Do not think it is Used
			$utagdata['search_keyword'] = '';
			$utagdata['search_results'] = '';

			if ( ( is_home() ) || ( is_front_page() ) ) {

				$utagdata['page_type'] = 'home';
				//default page_category value from plugin
				if ($page_category) {
					$utagdata['page_category'] = $page_category;
				}

			} elseif ( is_search() ) {

				global $wp_query;
				// Collect search and result data
				$searchQuery = get_search_query();
				$searchCount = $wp_query->found_posts;
				// Add to udo
				$utagdata['page_type']      = 'search';
				$utagdata['search_keyword'] = $searchQuery;
				$utagdata['search_results'] = $searchCount;

			} elseif ( preg_match( '/thank-you/', $urlString, $matches ) || preg_match( '/thanks/', $urlString, $matches ) ) {

				$utagdata['page_type'] = 'thankyou';
				if ( isset($_GET['orderid']) && $_GET['orderid'] ) {
					$utagdata['order_id'] = $_GET['orderid'];
				}
				if ( isset($_GET['p']) && $_GET['p'] ) {
					$utagdata['program_name'] = $_GET['p'];
					$utagdata['program_code'] = $_GET['p'];
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

			}  else {
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
				//get current page url
				$current_url = strtok($_SERVER["REQUEST_URI"], '?');



				//remove first and last '/'
				//if not homepage
				if($current_url != '/') {
					$current_url = substr($current_url, 1, -1);
				}

				foreach ($urls as $url => $key) {

					if($urls[$url] == $current_url) {
						if($page_types[$url] !=''){
							$utagdata['page_type'] = $page_types[$url];
							$type = true;
						}
						if($page_categories[$url] !=''){
							$utagdata['page_category'] = $page_categories[$url];
							$cat = true;
						}
						if($program_codes[$url] !='') {
							$utagdata['program_name'] = $program_codes[$url];
							$code = true;
						}
					}
					//wildcard
					if(wes_endsWith($urls[$url], '/*')) {
						//remove /* from url value
						//to make it work with child pages and parent page
						$urls[$url] = substr($urls[$url], 0, -2);
						//if current url start with wilddcard value, then
						//load default value if no specific value is already set
						//type, cat an code flags
						if(wes_startsWith ($current_url, $urls[$url])) {
							if($page_types[$url] !='' && $type!=true){
								$utagdata['page_type'] = $page_types[$url];
							}
							if($page_categories[$url] !='' && $cat!=true){
								$utagdata['page_category'] = $page_categories[$url];
							}
							if($program_codes[$url] !='' && $code!=true) {
								$utagdata['program_name'] = $program_codes[$url];
							}
						}
					}
				}
			}

			if (isset($utagdata['page_category']) && $utagdata['page_category'] == 'Landing Page') {
				echo "<script>if(window.isLandingPage != true){window.isLandingPage = true}</script>";
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
