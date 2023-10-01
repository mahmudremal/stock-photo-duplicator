<?php
/**
 * This plugin ordered by a client and done by Remal Mahmud (fiverr.com/mahmud_remal). Authority dedicated to that cient.
 *
 * @wordpress-plugin
 * Plugin Name:       Stock Photo Duplicator
 * Plugin URI:        https://github.com/mahmudremal/stock-photo-duplicator/
 * Description:       Stock photo duplicate & random product upload following a sample product. Copy post contents & meta data with replacing thumbnails & downloadable file.
 * Version:           1.0.1
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Remal Mahmud
 * Author URI:        https://github.com/mahmudremal/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       stock-photo-duplicator
 * Domain Path:       /languages
 * 
 * @package FutureWordPressScratchProject
 * @author  Remal Mahmud (https://github.com/mahmudremal)
 * @version 1.0.2
 * @link https://github.com/mahmudremal/stock-photo-duplicator/
 * @category	WooComerce Plugin
 * @copyright	Copyright (c) 2023-25
 * 
 */

/**
 * Bootstrap the plugin.
 */



defined( 'STOCK_PHOTO_DUPLICATOR__FILE__' ) || define( 'STOCK_PHOTO_DUPLICATOR__FILE__', untrailingslashit( __FILE__ ) );
defined( 'STOCK_PHOTO_DUPLICATOR_DIR_PATH' ) || define( 'STOCK_PHOTO_DUPLICATOR_DIR_PATH', untrailingslashit( plugin_dir_path( STOCK_PHOTO_DUPLICATOR__FILE__ ) ) );
defined( 'STOCK_PHOTO_DUPLICATOR_DIR_URI' ) || define( 'STOCK_PHOTO_DUPLICATOR_DIR_URI', untrailingslashit( plugin_dir_url( STOCK_PHOTO_DUPLICATOR__FILE__ ) ) );
defined( 'STOCK_PHOTO_DUPLICATOR_BUILD_URI' ) || define( 'STOCK_PHOTO_DUPLICATOR_BUILD_URI', untrailingslashit( STOCK_PHOTO_DUPLICATOR_DIR_URI ) . '/assets/build' );
defined( 'STOCK_PHOTO_DUPLICATOR_BUILD_PATH' ) || define( 'STOCK_PHOTO_DUPLICATOR_BUILD_PATH', untrailingslashit( STOCK_PHOTO_DUPLICATOR_DIR_PATH ) . '/assets/build' );
defined( 'STOCK_PHOTO_DUPLICATOR_BUILD_JS_URI' ) || define( 'STOCK_PHOTO_DUPLICATOR_BUILD_JS_URI', untrailingslashit( STOCK_PHOTO_DUPLICATOR_DIR_URI ) . '/assets/build/js' );
defined( 'STOCK_PHOTO_DUPLICATOR_BUILD_JS_DIR_PATH' ) || define( 'STOCK_PHOTO_DUPLICATOR_BUILD_JS_DIR_PATH', untrailingslashit( STOCK_PHOTO_DUPLICATOR_DIR_PATH ) . '/assets/build/js' );
defined( 'STOCK_PHOTO_DUPLICATOR_BUILD_IMG_URI' ) || define( 'STOCK_PHOTO_DUPLICATOR_BUILD_IMG_URI', untrailingslashit( STOCK_PHOTO_DUPLICATOR_DIR_URI ) . '/assets/build/src/img' );
defined( 'STOCK_PHOTO_DUPLICATOR_BUILD_CSS_URI' ) || define( 'STOCK_PHOTO_DUPLICATOR_BUILD_CSS_URI', untrailingslashit( STOCK_PHOTO_DUPLICATOR_DIR_URI ) . '/assets/build/css' );
defined( 'STOCK_PHOTO_DUPLICATOR_BUILD_CSS_DIR_PATH' ) || define( 'STOCK_PHOTO_DUPLICATOR_BUILD_CSS_DIR_PATH', untrailingslashit( STOCK_PHOTO_DUPLICATOR_DIR_PATH ) . '/assets/build/css' );
defined( 'STOCK_PHOTO_DUPLICATOR_BUILD_LIB_URI' ) || define( 'STOCK_PHOTO_DUPLICATOR_BUILD_LIB_URI', untrailingslashit( STOCK_PHOTO_DUPLICATOR_DIR_URI ) . '/assets/build/library' );
defined( 'STOCK_PHOTO_DUPLICATOR_ARCHIVE_POST_PER_PAGE' ) || define( 'STOCK_PHOTO_DUPLICATOR_ARCHIVE_POST_PER_PAGE', 9 );
defined( 'STOCK_PHOTO_DUPLICATOR_SEARCH_RESULTS_POST_PER_PAGE' ) || define( 'STOCK_PHOTO_DUPLICATOR_SEARCH_RESULTS_POST_PER_PAGE', 9 );
defined( 'STOCK_PHOTO_DUPLICATOR_OPTIONS' ) || define( 'STOCK_PHOTO_DUPLICATOR_OPTIONS', get_option( 'stock-photo-duplicator' ) );

require_once STOCK_PHOTO_DUPLICATOR_DIR_PATH . '/inc/helpers/autoloader.php';
// require_once STOCK_PHOTO_DUPLICATOR_DIR_PATH . '/inc/helpers/template-tags.php';

if( ! function_exists( 'stockphotoduplicator_get_plugin_instance' ) ) {
	function stockphotoduplicator_get_plugin_instance() {\STOCK_PHOTO_DUPLICATOR\Inc\Project::get_instance();}
}
stockphotoduplicator_get_plugin_instance();


add_action('wp_footer', function() {
    $_products = wc_get_products(['status' => 'publish', 'limit' => -1]);$_prods = [];
	// print_r($_products);
    foreach($_products as $_prod) {
        $_prods[] = ['id' => $_prod->get_id(),'type' => $_prod->get_type()];
    }
    ?>
    <script>
    const fwpStockImgPricig = <?php echo json_encode($_prods); ?>;
    setInterval(() => {
        // document.querySelectorAll(".ulz-listing-preview .ulz--list.ulz-scrollbar .ulz-download-plans label > input[type=radio][name=download_plan]")
        document.querySelectorAll(".ulz-download-plans label > input[type=radio][name=download_plan]:not([data-handled])").forEach((radio) => {
            radio.dataset.handled = true;
            var matched = fwpStockImgPricig.find((row)=>row.id == radio.value);
            if(matched) {
                var text = radio.parentElement.querySelector('.ulz--name').innerHTML;
				radio.dataset.packagetype = (text.toUpperCase().includes('MONTHLY'))?'listing_plan':matched.type;
				if(text.toUpperCase().includes('MONTHLY')) {
					radio.parentElement.parentElement.appendChild(radio.parentElement)
				} else if(text.toUpperCase().includes('ON DEMAND')) {
                    var quantity = radio.parentElement.querySelector('.ulz--info').innerHTML;
                    quantity = quantity.replace('downloads', '');quantity = parseInt(quantity.trim());
                    if(quantity && quantity == 10) {
                        radio.parentElement.parentElement.insertBefore(
                            radio.parentElement, radio.parentElement.parentElement.children[1]
                        )
                    }
				} else {}
            }
        });
    }, 300);
    </script>
    <style>
        .ulz-download-plans label > input[type=radio][name=download_plan][data-packagetype=listing_plan] + .ulz--item {background-color: #cfcfcf;}
        .ulz-download-plans .ulz--sections .ulz--section.ulz--active {display: block;height: auto;overflow: hidden;overflow-y: auto;position: relative;}
        #page > div.ulz-container > div > main > div.ulz-content > article > div > div > div.woocommerce-MyAccount-content > div.ulz-grid.ulz-justify-space.ulz-align-center.ulz-mb-4 > div:nth-child(2) > a.ulz-button {display: none;}
        #page .ulz-container main .ulz-account-bar nav li.woocommerce-MyAccount-navigation-link.woocommerce-MyAccount-navigation-link--entries,
        #page .ulz-container main .ulz-account-bar nav li.woocommerce-MyAccount-navigation-link.woocommerce-MyAccount-navigation-link--assets,
        #page .ulz-container main .ulz-account-bar nav li.woocommerce-MyAccount-navigation-link.woocommerce-MyAccount-navigation-link--messages,
        #page .ulz-container main .ulz-account-bar nav li.woocommerce-MyAccount-navigation-link.woocommerce-MyAccount-navigation-link--notification-settings {display: none;}
        #page > div.ulz-container > div > main > div.ulz-content > article > div > div > div.woocommerce-MyAccount-content > div.ulz-dashboard > div:nth-child(3) > div {flex-basis: 50%;max-width: 50%;}
        #page > div.ulz-container > div > main > div.ulz-content > article > div > div > div.woocommerce-MyAccount-content > div.ulz-dashboard > div:nth-child(3) > div:nth-child(3) {display: none !important;}
        #page > div.ulz-container > div > main > div.ulz-content > article > div > div > div.woocommerce-MyAccount-content > div.ulz-author-cover > div > div > div > div.ulz--action {display: none;}

    </style>
    <script>
        document.querySelectorAll('#page > div.ulz-container > div > main > div.ulz-content > article > div > div > div.woocommerce-MyAccount-content > div.woocommerce-info > a[href*="/shop"]').forEach((el) => {el.href = el.href.replace('/shop', '/collection');});
        document.querySelectorAll('#page > div.ulz-container > div > main > div.ulz-content > article > div > div > div.woocommerce-MyAccount-content > form > p:nth-child(4) > span > em').forEach((el) => {el.innerHTML = el.innerHTML.replace('and in reviews', '');});
        ['entries', 'messages'].forEach((el) => {document.querySelector('#page > header > div > div > div > div.ulz-site-actions > div.ulz-site-user > div > ul > li > a[href*="my-account/'+el+'"]')?.parentElement.remove();});
        ['div[data-upload-type="image"]', 'div[data-upload-type="image"]', '#description_field'].forEach((el) => {document.querySelector('#page > div.ulz-container > div > main > div.ulz-content > article > div > div > div.woocommerce-MyAccount-content > form > ' + el)?.remove();});
        document.querySelectorAll('#page > div.ulz-container > div > main > div.ulz-content > article > div > div > div.woocommerce-MyAccount-content > div.ulz-author-cover > div > div > div > div.ulz--action').forEach((el) => {el.remove();});

        
        

    </script>
        
	<?php
	/**
	 * Add extra page ID on $allowedDarkFooterBGLOGO array list.
	 * You can remove is_page to work on posts or custom post type as well.
	 */
	$allowedDarkFooterBGLOGO = [55];

	$whiteImageIDonMediaLibrary = 5928;
	$current_page_id = get_the_ID();
	if(! is_home() && is_singular() && is_page($current_page_id) && in_array($current_page_id,$allowedDarkFooterBGLOGO)) :
        $image_html = wp_get_attachment_image($whiteImageIDonMediaLibrary, 'full');
        ?>
        <script>
            document.querySelectorAll('.ulz-footer .wp-block-gallery.has-nested-images figure.wp-block-image img').forEach((el) => {
                const img = document.createElement('div');img.innerHTML = '<?php echo wp_kses_post($image_html); ?>';
                el.parentElement.insertBefore(img.children[0], el);el.remove();
            });
        </script>
	<?php endif; ?>
	<?php
    $allowedHeaderLightLOGO = [55];// is_front_page() || 
    if(! in_array($current_page_id, $allowedHeaderLightLOGO)) :
	    $darkImageIDonMediaLibrary = 5843;
	    $image_html = wp_get_attachment_image($darkImageIDonMediaLibrary, 'full');
        ?>
        <script>
            document.querySelectorAll('#page > div.ulz-mobile-header > div.ulz--site-name.ulz-font-heading > a > div > img').forEach((el) => {
                const img = document.createElement('div');img.innerHTML = '<?php echo wp_kses_post($image_html); ?>';
                el.parentElement.insertBefore(img.children[0], el);el.remove();
            });
        </script>
	<?php endif; ?>
    <?php
}, 10, 0);

?>