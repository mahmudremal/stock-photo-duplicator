<?php

/**

 * Elementor widget for youtube playlist displaying

 *

 * @category	Litivo Listing Site

 * @package FutureWordPressScratchProject

 * @author		FutureWordPress.com <info@futurewordpress.com/>

 * @copyright	Copyright (c) 2022-23

 * @link		https://futurewordpress.com/

 * @version		1.3.6

 */

if( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.



class Elementor_WidgetCustomCategory extends \Elementor\Widget_Base {

	/**

	 * Get widget name.

	 *

	 * Retrieve oEmbed widget name.

	 *stock-photo-duplicator

	 * @since 1.0.0

	 * @access public

	 * @return string Widget name.

	 */

	public function get_name() {

		return 'oembed';

	}

	/**

	 * Get widget title.

	 *

	 * Retrieve oEmbed widget title.

	 *

	 * @since 1.0.0

	 * @access public

	 * @return string Widget title.

	 */

	public function get_title() {

		return esc_html__( 'oEmbed', 'stock-photo-duplicator' );

	}

	/**

	 * Get widget icon.

	 *

	 * Retrieve oEmbed widget icon.

	 *

	 * @since 1.0.0

	 * @access public

	 * @return string Widget icon.

	 */

	public function get_icon() {

		return 'eicon-code';

	}

	/**

	 * Get custom help URL.

	 *

	 * Retrieve a URL where the user can get more information about the widget.

	 *

	 * @since 1.0.0

	 * @access public

	 * @return string Widget help URL.

	 */

	public function get_custom_help_url() {

		return 'https://developers.elementor.com/docs/widgets/';

	}

	/**

	 * Get widget categories.

	 *

	 * Retrieve the list of categories the oEmbed widget belongs to.

	 *

	 * @since 1.0.0

	 * @access public

	 * @return array Widget categories.

	 */

	public function get_categories() {

		return [ 'general' ];

	}

	/**

	 * Get widget keywords.

	 *

	 * Retrieve the list of keywords the oEmbed widget belongs to.

	 *

	 * @since 1.0.0

	 * @access public

	 * @return array Widget keywords.

	 */

	public function get_keywords() {

		return [ 'oembed', 'url', 'link' ];

	}

	/**

	 * Register oEmbed widget controls.

	 *

	 * Add input fields to allow the user to customize the widget settings.

	 *

	 * @since 1.0.0

	 * @access protected

	 */

	protected function register_controls() {

		$this->start_controls_section(

			'content_section',

			[

				'label' => esc_html__( 'Content', 'elementor-oembed-widget' ),

				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,

			]

		);

		$this->add_control(

			'url',

			[

				'label' => esc_html__( 'URL to embed', 'elementor-oembed-widget' ),

				'type' => \Elementor\Controls_Manager::TEXT,

				'input_type' => 'url',

				'placeholder' => esc_html__( 'https://your-link.com', 'elementor-oembed-widget' ),

			]

		);

		$this->end_controls_section();

	}

	/**

	 * Render oEmbed widget output on the frontend.

	 *

	 * Written in PHP and used to generate the final HTML.

	 *

	 * @since 1.0.0

	 * @access protected

	 */

	protected function render() {

		$settings = $this->get_settings_for_display();

		$html = wp_oembed_get( $settings['url'] );

		echo '<div class="oembed-elementor-widget">';

		echo ( $html ) ? $html : $settings['url'];

		echo '</div>';

	}

}

// end of line

?>