<?php
/**
 *
 * The settings page for the plugin.
 */
add_action('admin_menu', 'snillrik_faq_create_menu');
function snillrik_faq_create_menu()
{
    add_menu_page(
        'Snillrik Wayke',
        'Snillrik Wayke',
        'administrator',
        __FILE__,
        'snillrik_faq_page',
        plugins_url('/images/snillrik_bulb.svg', __FILE__)
    );
    add_action('admin_init', 'register_snillrik_faq_settings');
}

/**
 * Register the settings
 */
function register_snillrik_faq_settings()
{
	register_setting('snillrik-waykewaykegroup', 'snillrik_wayke_token');
	register_setting('snillrik-waykewaykegroup', 'snillrik_wayke_itempage');
}

/**
 * The settings page
 */
function snillrik_faq_page()
{
    ?>

<div class="wrap snillrik-waykefaq">

	<h1>Snillrik Wayke</h1>
	<p>Some settings for the Snillrik Wayke plugin</p>
	<form method="post" action="options.php">
    <?php
	settings_fields('snillrik-waykewaykegroup');
    do_settings_sections('snillrik-waykewaykegroup');
	$wayke_token = get_option('snillrik_wayke_token', ""); 
	$itempage = get_option('snillrik_wayke_itempage', "");   
    ?>

	<div class="snillrik-waykewaykemain">
		<div class="snillrik-waykewaykerow">
			<div class="snillrik-waykewaykeitem">
				<div class="snillrik-waykewaykeitem-inner">
				<h3><?php esc_attr_e("Token for Wayke","snillrik-waykefaq") ?></h3>
				<p><?php esc_attr_e("The token for communicating with Wayke","snillrik-waykefaq") ?></p>
				<p>TODO: Hur man får fatt på ett sånt token. </p>
  					<input type='password' id="snillrik_wayke_token" name="snillrik_wayke_token" value="<?php echo $wayke_token ?>" />
				</div>
			</div>
			
			</div>
			<div class="snillrik-waykewaykeitem">
				<div class="snillrik-waykewaykeitem-inner">
				<h3><?php esc_attr_e("Select page for single car page","snillrik-waykefaq") ?></h3>
				<p><?php esc_attr_e("The page for the singe page for a car, the one that has the shortcode [wayke_item]","snillrik-waykefaq") ?></p>
				<select  id="snillrik_wayke_itempage" name="snillrik_wayke_itempage">
                            <?php
                            if( $pages = get_pages() ){
                                foreach( $pages as $page ){
                                    echo '<option value="' . $page->ID . '" ' . selected( $page->ID, $itempage ) . '>' . $page->post_title . '</option>';
                                }
                            }
                            ?>
                </select>
				</div>
			</div>
			
			</div>									
		</div>

		<h3><?php esc_attr_e("Shortcodes","snillrik-waykefaq") ?></h3>
		<p><?php _e("Info about shortcodes, <br />
			[wayke_list] for the list page<br />
			[wayke_item rightcol=true] for the singel car page set rightcol to false for no right column (most likly to use the price shortcode below. :)<br />
			[wayke_price] for price, intended for sidebar, widget etc. To be used on page where wayke_item shortcode is used.<br />
		","snillrik-waykefaq") ?></p>
	</div>


    <?php submit_button();?>
	</form>

</div>
<?php }?>
