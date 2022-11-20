<?php

/**
 * Register all actions and filters for the plugin
 *
 * @link       https://https://github.com/arturgeek
 * @since      1.0.0
 *
 * @package    Dakota_Shortcodes
 * @subpackage Dakota_Shortcodes/includes
 */

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    Dakota_Shortcodes
 * @subpackage Dakota_Shortcodes/includes
 * @author     Andres Morales <arturgeek@gmail.com>
 */
class Dakota_Shortcodes_Loader {

	/**
	 * The array of actions registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $actions    The actions registered with WordPress to fire when the plugin loads.
	 */
	protected $actions;

	/**
	 * The array of filters registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $filters    The filters registered with WordPress to fire when the plugin loads.
	 */
	protected $filters;

	/**
	 * Initialize the collections used to maintain the actions and filters.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->actions = array();
		$this->filters = array();
		
		$this->add_shortcodes();
	}

	/**
	 * Add a new action to the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 * @param    string               $hook             The name of the WordPress action that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the action is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         Optional. The priority at which the function should be fired. Default is 10.
	 * @param    int                  $accepted_args    Optional. The number of arguments that should be passed to the $callback. Default is 1.
	 */
	public function add_action( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->actions = $this->add( $this->actions, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * Add a new filter to the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 * @param    string               $hook             The name of the WordPress filter that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the filter is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         Optional. The priority at which the function should be fired. Default is 10.
	 * @param    int                  $accepted_args    Optional. The number of arguments that should be passed to the $callback. Default is 1
	 */
	public function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->filters = $this->add( $this->filters, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * A utility function that is used to register the actions and hooks into a single
	 * collection.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param    array                $hooks            The collection of hooks that is being registered (that is, actions or filters).
	 * @param    string               $hook             The name of the WordPress filter that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the filter is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         The priority at which the function should be fired.
	 * @param    int                  $accepted_args    The number of arguments that should be passed to the $callback.
	 * @return   array                                  The collection of actions and filters registered with WordPress.
	 */
	private function add( $hooks, $hook, $component, $callback, $priority, $accepted_args ) {

		$hooks[] = array(
			'hook'          => $hook,
			'component'     => $component,
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $accepted_args
		);

		return $hooks;

	}

	/**
	 * Register the filters and actions with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {

		foreach ( $this->filters as $hook ) {
			add_filter( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}

		foreach ( $this->actions as $hook ) {
			add_action( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}

	}

	/**
	 * Define the shortcodes 
	 *
	 * @since    1.0.0
	 */
	private function add_shortcodes(){

		if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			return; 
		}
		
		add_shortcode('dakota_cart_count', array($this, 'dakota_cart_count_fn'));
		add_shortcode('dakota_account_link', array($this, 'dakota_account_link_fn') );
	}

	/**     
	* Display a Cart with the Items Count
	* @param array  $atts    Shortcode attributes. Default ['bgcolor' => '#000000'], 'fcolor' => '#FFFFFF', backgroun color and font color respectively
	* @param string $content Shortcode content. Default null.
	* @param string $tag     Shortcode tag (name). Default empty.
	*
	*/
	public function dakota_cart_count_fn( $atts = [], $content = null, $tag = '' ) {

		$atts = array_change_key_case( (array) $atts, CASE_LOWER );
		$atts = shortcode_atts(
			array(
				'bgcolor' => '#000000',
				'fcolor' => '#FFFFFF',
			), $atts, $tag
		);

		$cart_count = WC()->cart->get_cart_contents_count();
		$cart_div = "
		<div id='wp-cart-count' style='background-color: ".$atts["bgcolor"]."' data-cart-link='".wc_get_cart_url()."'>
			<span style='color: ".$atts["fcolor"]."'>
			".$cart_count."
			</span>
		</div>
		";
		return $cart_div;
	}

	/**     
	* Display a Link to my account
	* @param array  $atts    Shortcode attributes. Default empty
	* @param string $content Shortcode content. Default null.
	* @param string $tag     Shortcode tag (name). Default empty.
	*
	*/
	function dakota_account_link_fn() {

		$text = 'Iniciar Sesión / Registrarse';
		if ( is_user_logged_in() ) {
			$text = 'Mi cuenta';
		}

		$my_account_link = get_permalink( get_option('woocommerce_myaccount_page_id') );

		$html_link = "<a href='".$my_account_link."'>
			".$text."
		</a>";

		return $html_link;
	}
}
