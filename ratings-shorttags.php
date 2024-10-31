<?php
/*
Plugin Name: Review Ratings
Plugin URI: http://noscope.com/?p=3122
Description: Adds <code>[rating=5]</code> shortcode for inserting book and movie review ratings.
Version: 1.6
Author: Joen Asmussen
Author URI: http://noscope.com
*/

// Register functions
add_shortcode('rating', 'insertRating');



// Insert Rating function
function insertRating($attr) {

	// defaults
	if (!$attr['stars'] && !$attr[0]) {
		$attr['stars'] = 0;
	}

	$rs = get_option('rating_symbol');
	$ts = get_option('total_symbols');
	
	$theRating .= '<span class="rating">';

	// output stars
	if ($attr['stars']) {
		$total_stars = $attr['stars'];
	} else if ($attr[0]) {
		$total_stars = str_replace( "=" , "" , $attr[0] ) ;
		$total_stars = str_replace( '"' , "" , $total_stars ) ;
		$total_stars = str_replace( '/' , "" , $total_stars ) ;
	}
	
	// output
	for ($i=0; $i<$total_stars; $i++) {
		$theRating .= '<span>' . $rs . '</span>';
	}
	
	if (!is_feed()) {
		// output empty stars
		$empty = $ts - $total_stars;
		for ($j=0; $j<$empty; $j++) {
			$theRating .= '<span class="empty">' . $rs . '</span>';
		}
	}
	
	
	$theRating .= '</span>';
	return $theRating;

}


// Add CSS
function ratingCSS () {
	
	$symbol_color = get_option('symbol_color');
	if ($symbol_color == "") {
		$symbol_color = "000000";
	}
	$empty_symbol_color = get_option('empty_symbol_color');
	if ($empty_symbol_color == "") {
		$empty_symbol_color = "cccccc";
	}
	
	echo '<style type="text/css">.rating span { color: #' . $symbol_color . '; } .rating span.empty { color: #'. $empty_symbol_color .'; }</style>';
}
add_action('wp_head', 'ratingCSS');



function ratingAdminCSS () { ?>
<style type="text/css">
body.mp6 a .mce_rating img {
	display: none;
}
body.mp6 a .mce_rating {
	text-align: center;
	font: normal 20px/1 'dashicons' !important;
	speak: none;
	-webkit-font-smoothing: antialiased;
	display: block;
	width: 20px;
	height: 20px;
}
body.mp6 span.mce_rating:before {
	content: '\f154';
}
</style>
<?php }
add_action('admin_head', 'ratingAdminCSS');



/** 
 * 	Add TinyMCE buttons (WP 2.5+)
 */

add_action('init', 'ratings_shorttags_addbuttons');

function ratings_shorttags_addbuttons() {
   // Don't bother doing this stuff if the current user lacks permissions
   if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
     return;
 
   // Add only in Rich Editor mode
   if ( get_user_option('rich_editing') == 'true') {
		add_filter("mce_external_plugins", "add_ratings_shorttags_tinymce_plugin");
		add_filter('mce_buttons', 'register_ratings_shorttags_button');
   }
}
 
function register_ratings_shorttags_button($buttons) {
   array_push($buttons, "separator", "rating","relatedratings");
   return $buttons;
}
function the_plugin_url () {
	if ( function_exists('plugin_url') )
		$plugin_url = plugin_url();
	else
		$plugin_url = get_option('siteurl') . '/wp-content/plugins/' . plugin_basename(dirname(__FILE__));

	return $plugin_url;
}
function add_ratings_shorttags_tinymce_plugin($plugin_array) {
	
	$plugin_array['ratings_mce'] = the_plugin_url().'/tinymce/editor_plugin.js';
	return $plugin_array;
}


// Insertion scripts
function ratings_shorttags_admin_scripts() {
	wp_register_script('ratings_shorttags_admin_scripts', the_plugin_url() . '/ratings-shorttags.js');
	wp_enqueue_script('ratings_shorttags_admin_scripts');
}    
if (is_admin()) {
	add_action('init', ratings_shorttags_admin_scripts);
}



/** 
 * 	Add Quicktags
 */
function ratings_shorttags_quicktags(){
	$buttonshtml = '<input type="button" class="ed_button" onclick="insertRating(); return false;" title="' . __('Insert a rating','ratings-shorttags') . '" value="' . __('rating','ratings-shorttags') . '" />';
	?>
	<script type="text/javascript" charset="utf-8">
	// <![CDATA[
	   (function(){
		  if (typeof jQuery === 'undefined') {
			 return;
		  }
		  jQuery(document).ready(function(){
			 jQuery("#ed_toolbar").append('<?php echo $buttonshtml; ?>');
		  });
	   }());
	// ]]>
	</script>
	<?php
}
add_action('edit_page_form', 'ratings_shorttags_quicktags');
add_action('edit_form_advanced', 'ratings_shorttags_quicktags');












/** 
 * 	Options
 */
$ratings_shorttags_plugin_name = __("Review Ratings", 'ratings-shorttags');
$ratings_shorttags_plugin_filename = "ratings-shorttags.php";

add_option("rating_symbol", "&#9733;", "", "yes");
add_option("symbol_color", "000000", "", "yes");
add_option("empty_symbol_color", "cccccc", "", "yes");
add_option("total_symbols", "6", "", "yes");
add_option("rs_total_related", "5", "", "yes");




// Register options page
add_action('admin_init', 'rating_admin_init');
add_action('admin_menu', 'add_rating_option_page');


function rating_admin_init() {
	if ( function_exists('register_setting') ) {
		register_setting('rating_settings', 'option-1', '');
	}
}
function add_rating_option_page() {
	global $wpdb;
	global $ratings_shorttags_plugin_name;
	
	add_options_page($ratings_shorttags_plugin_name, $ratings_shorttags_plugin_name, 8, basename(__FILE__), 'ratings_shorttags_options_page');
}

function ratings_shorttags_options_page() {
	if (isset($_POST['info_update'])) {
			
		// Update the rating symbol
		$rating_symbol = $_POST["rating_symbol"];
		update_option("rating_symbol", $rating_symbol);
		
		$symbol_color = $_POST["symbol_color"];
		update_option("symbol_color", $symbol_color);
		
		$empty_symbol_color = $_POST["empty_symbol_color"];
		update_option("empty_symbol_color", $empty_symbol_color);

		$total_symbols = $_POST["total_symbols"];
		update_option("total_symbols", $total_symbols);

		$rs_total_related = $_POST["rs_total_related"];
		update_option("rs_total_related", $rs_total_related);

		// Give an updated message
		echo "<div class='updated fade'><p><strong>" . __('Options updated', 'ratings-shorttags') . "</strong></p></div>";
		
	}

	// Show options page
	?>

		<div class="wrap">
		
			<div class="options">
		
				<form method="post" action="options-general.php?page=<?php global $ratings_shorttags_plugin_filename; echo $ratings_shorttags_plugin_filename; ?>">
				<h2><?php global $ratings_shorttags_plugin_name; printf(__('%s Settings', 'ratings_shorttags'), $ratings_shorttags_plugin_name); ?></h2>
				
					<h3><?php _e("Notes on usage", 'ratings-shorttags'); ?></h3>
					
					<p><?php _e("Shortcodes are codes you type in posts or pages to insert special things.", 'ratings-shorttags'); ?></p>
					
					<p><?php _e("To insert a rating in your post, simply type: <code>[rating=5]</code> where 5 is the amount of stars (or whatever symbol you chose).", 'ratings-shorttags'); ?></p>
					
					<h3><?php _e("Options", 'ratings-shorttags'); ?></h3>
					
					<p><?php _e("These options affect the <code>[rating]</code> shorttag.", 'ratings-shorttags'); ?></p>

					<p>
					<label><?php _e("Symbol to use for your ratings:", 'ratings-shorttags'); ?><br />
					<?php
					$rating_symbol = str_replace("&", "&amp;", get_option('rating_symbol'));
					echo "<input type='text' size='50' ";
					echo "name='rating_symbol' ";
					echo "id='rating_symbol' ";
					echo "value='".$rating_symbol."' />\n";
					?>
					</label> <?php _e("<em>Default: <code>&amp;#9733;</code>.</em>", 'ratings-shorttags'); ?></p>


					<p>
					<label><?php _e("Symbol color:", 'ratings-shorttags'); ?><br />
					<?php
					echo "<input type='text' size='50' ";
					echo "name='symbol_color' ";
					echo "id='symbol_color' ";
					echo "value='".get_option('symbol_color')."' />\n";
					?>
					</label> <?php _e("<em>Default: <code>000000</code> (hex code format).</em>", 'ratings-shorttags'); ?></p>

					<p>
					<label><?php _e("Empty symbol color:", 'ratings-shorttags'); ?><br />
					<?php
					echo "<input type='text' size='50' ";
					echo "name='empty_symbol_color' ";
					echo "id='empty_symbol_color' ";
					echo "value='".get_option('empty_symbol_color')."' />\n";
					?>
					</label> <?php _e("<em>Default: <code>cccccc</code> (hex code format).</em>", 'ratings-shorttags'); ?></p>


					<p>
					<label><?php _e("Total symbols:", 'ratings-shorttags'); ?><br />
					<?php
					echo "<input type='text' size='10' ";
					echo "name='total_symbols' ";
					echo "id='total_symbols' ";
					echo "value='".get_option('total_symbols')."' />\n";
					?>
					</label> <?php _e("<em>Default: <code>6</code> (must be a number).</em>", 'ratings-shorttags'); ?></p>

					<h3><?php _e("Related Ratings Options", 'ratings-shorttags'); ?></h3>

					<p><?php _e("These options affect the <code>[relatedratings]</code> shorttag.", 'ratings-shorttags'); ?></p>

					<p>
					<label><?php _e("Amount of related posts to show:", 'ratings-shorttags'); ?><br />
					<?php
					echo "<input type='text' size='10' ";
					echo "name='rs_total_related' ";
					echo "id='rs_total_related' ";
					echo "value='".get_option('rs_total_related')."' />\n";
					?>
					</label> <?php _e("<em>Default: <code>5</code> (must be a number).</em>", 'ratings-shorttags'); ?></p>


					<p class="submit">
						<?php if ( function_exists('settings_fields') ) settings_fields('rating_settings'); ?>
						<input type='submit' name='info_update' value='<?php _e('Save Changes', 'ratings_shorttags'); ?>' />
					</p>
					
				</form>
				
				
			</div><?php //.options ?>
			
		</div>

<?php
}



?>