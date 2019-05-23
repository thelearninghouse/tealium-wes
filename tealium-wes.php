<?php
/**
* Plugin Name: Tealium - WES
* Description: Tealium Plugin Extension adds standard datalayer values
* Version: 1.0.0
* Author: Brent Maggard
*/
global $Tealium_WES;
$Tealium_WES = new Tealium_WES;

class Tealium_WES {

   private $textdomain = "Tealium_WES";
   private $required_plugins = array('tealium');

   function have_required_plugins() {
       if (empty($this->required_plugins))
           return true;
       $active_plugins = (array) get_option('active_plugins', array());
       if (is_multisite()) {
           $active_plugins = array_merge($active_plugins, get_site_option('active_sitewide_plugins', array()));
       }
       foreach ($this->required_plugins as $key => $required) {
           $required = (!is_numeric($key)) ? "{$key}/{$required}.php" : "{$required}/{$required}.php";
           if (!in_array($required, $active_plugins) && !array_key_exists($required, $active_plugins))
               return false;
       }
       return true;
   }


   function __construct() {
       if (!$this->have_required_plugins())
           return;

      function addToDataObject() {
        global $utagdata;

      	$url = $_SERVER['REQUEST_URI'];
      	$urlString = parse_url($url, PHP_URL_PATH);

        // Add a timestamp to the data layer
        $utagdata['timestamp'] = time();

        //GET allocadiaID FROM URL TID
      	if ( $_GET["tid"] ) {
      		$utagdata['allocadiaid'] = $_GET["tid"];
      	}

        $utagdata['partner_name'] = esc_html( get_option( 'options_school_short_name' ) );

        if (get_post_type() == "degrees") {
          $utagdata['program_name'] = get_post_meta(get_the_ID(),'program_code',true);
        } else {
          $utagdata['program_name'] = esc_html( get_option( 'options_school_short_name' ) ) . "-brand";

        }

        $utagdata['page_category'] = ""; //Only used is $pageType = landing page
        $utagdata['page_name'] = get_the_title();
        //$utagdata['page_section'] = ""; // Removed Do not think it is Used
        $utagdata['search_keyword'] = "";
        $utagdata['search_results'] = "";

        echo "<script>console.log( 'Debug Objects: " . $utagdata['pageType'] . "' );</script>";

        if ( ( is_home() ) || ( is_front_page() ) ) {
            $utagdata['page_type'] = "home";
        } else if ( is_search() ) {
            global $wp_query;

            // Collect search and result data
            $searchQuery = get_search_query();
            $searchCount = $wp_query->found_posts;

            // Add to udo
            $utagdata['page_type'] = "search";
            $utagdata['search_keyword'] = $searchQuery;
            $utagdata['search_results'] = $searchCount;
        }
        else if ( preg_match( "/\/thank-you/", $urlString, $matches ) ) {
          $utagdata['page_type'] = "thankyou";
          if ($_GET["orderid"] ) {
            $utagdata['order_id'] = $_GET["orderid"];
          }
          if ($_GET["p"] ) {
            $utagdata["program_name"] = $_GET["p"];
          }
          if ( $_GET["lid"] ) { #GETS LEAD ID FROM URL LID VARIABLE
      			$utagdata["leadID"] = $_GET["lid"];
      		} else { #IF LID NOT PRESENT SEE IF CAN FIND LEAD ID AT END OF URL (LEGACY)
      			if ( preg_match( "/\d{5,100}/", $urlString, $lID ) ) {
      			    $utagdata["leadID"] = $lID[0];
      			}
      		}
      	}
        else if ($utagdata['pageType'] == "landing-pages") {
          $utagdata['page_type'] = "Landing Page";
          $utagdata['page_category'] = "Landing Page";
        } else {
          $utagdata['page_type'] = "content";
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
