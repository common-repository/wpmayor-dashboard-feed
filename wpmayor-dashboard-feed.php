<?php
/**
 * Plugin Name:  WP Mayor Dashboard Feed
 * Plugin URI: 	 http://wpmayor.com/
 * Description:  This plugin adds a widget to your WordPress Dashboard that pulls in the most recent posts from WPMayor.com. The number of posts displayed, and the category of posts displayed, can both be configured.
 *               It is based on the 'WPCandy Dashboard Feed' plugin by WPCandy, but has been adapted to show feeds from WPMayor.com instead.
 * Version: 	 1.1
 * Author: 		 Jean Galea
 * Author URI: 	 http://www.jeangalea.com
 * License: 	 GPL2
 * License 	URI: http://www.gnu.org/licenses/gpl-2.0.html
**/


// Creates the custom dashboard feed RSS box
function wpmayor_dashboard_custom_feed_output() {
	
	$widget_options = wpmayor_dashboard_options();
	
	// Variable for RSS feed
	$wpmayor_feed = 'http://www.wpmayor.com/feed/';			
	
	echo '<div class="rss-widget" id="wpmayor-rss-widget">';
		wp_widget_rss_output(array(
			'url' => $wpmayor_feed,
			'title' => 'Latest Posts from WP Mayor',
			'items' => $widget_options['posts_number'],
			'show_summary' => 0,
			'show_author' => 0,
			'show_date' => 0
		));
	echo "</div>";
}


// Function used in the action hook
function wpmayor_add_dashboard_widgets() {	
	wp_add_dashboard_widget('wpmayor_dashboard_custom_feed', 'Latest Posts from WPMayor.com', 'wpmayor_dashboard_custom_feed_output', 'wpmayor_dashboard_setup' );
}


function wpmayor_dashboard_options() {	
	$defaults = array( 'posts_number' => 5 );
	if ( ( !$options = get_option( 'wpmayor_dashboard_custom_feed' ) ) || !is_array($options) )
		$options = array();
	return array_merge( $defaults, $options );
}


function wpmayor_dashboard_setup() {
 
	$options = wpmayor_dashboard_options();
 
	if ( 'post' == strtolower($_SERVER['REQUEST_METHOD']) && isset( $_POST['widget_id'] ) && 'wpmayor_dashboard_custom_feed' == $_POST['widget_id'] ) {
		foreach ( array( 'posts_number', 'posts_feed' ) as $key )
				$options[$key] = $_POST[$key];
		update_option( 'wpmayor_dashboard_custom_feed', $options );
	}
 
?>
 
		<p>
			<label for="posts_number"><?php _e('How many items?', 'wpmayor_dashboard_custom_feed' ); ?>
				<select id="posts_number" name="posts_number">
					<?php for ( $i = 5; $i <= 10; $i = $i + 1 )
						echo "<option value='$i'" . ( $options['posts_number'] == $i ? " selected='selected'" : '' ) . ">$i</option>";
						?>
					</select>
				</label>
 		</p>

 
<?php
 }


// Register the new dashboard widget into the 'wp_dashboard_setup' action
add_action('wp_dashboard_setup', 'wpmayor_add_dashboard_widgets' );

?>