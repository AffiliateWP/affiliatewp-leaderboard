<?php
/**
 * Core: Plugin Bootstrap
 *
 * @package     AffiliateWP Leaderboard
 * @subpackage  Core
 * @copyright   Copyright (c) 2021, Sandhills Development, LLC
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.1
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Main plugin bootstrap.
 *
 * @since 1.0.0
 */
final class AffiliateWP_Leaderboard {

	/** Singleton *************************************************************/

	/**
	 * Holds the instance
	 *
	 * @since 1.0
	 * @var AffiliateWP_Leaderboard The one true AffiliateWP_Leaderboard
	 */
	private static $instance;

	/**
	 * The version number of AffiliateWP
	 *
	 * @since 1.0
	 * @var   string
	 */
	private static $version;

	/**
	 * Main plugin file.
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	private $file = '';

	/**
	 * Main AffiliateWP_Leaderboard Instance
	 *
	 * Insures that only one instance of AffiliateWP_Leaderboard exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @since 1.0
	 * @static
	 * @static var array $instance
	 *
	 * @param string $file Main plugin file.
	 * @return The one true AffiliateWP_Leaderboard
	 */
	public static function instance( $file = null ) {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof AffiliateWP_Leaderboard ) ) {
			self::$instance = new AffiliateWP_Leaderboard;
			self::$instance->file = $file;

			self::$version    = '1.1';

			self::$instance->setup_constants();
			self::$instance->load_textdomain();
			self::$instance->includes();
			self::$instance->hooks();

		}

		return self::$instance;
	}

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since 1.0
	 * @access protected
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'affiliatewp-leaderboard' ), '1.0' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * @since 1.0
	 * @access protected
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'affiliatewp-leaderboard' ), '1.0' );
	}

	/**
	 * Setup plugin constants.
	 *
	 * @access private
	 * @since 1.2
	 * @return void
	 */
	private function setup_constants() {
		// Plugin version.
		if ( ! defined( 'AFFWP_LB_VERSION' ) ) {
			define( 'AFFWP_LB_VERSION', self::$version );
		}

		// Plugin Folder Path.
		if ( ! defined( 'AFFWP_LB_PLUGIN_DIR' ) ) {
			define( 'AFFWP_LB_PLUGIN_DIR', plugin_dir_path( $this->file ) );
		}

		// Plugin Folder URL.
		if ( ! defined( 'AFFWP_LB_PLUGIN_URL' ) ) {
			define( 'AFFWP_LB_PLUGIN_URL', plugin_dir_url( $this->file ) );
		}

		// Plugin Root File.
		if ( ! defined( 'AFFWP_LB_PLUGIN_FILE' ) ) {
			define( 'AFFWP_LB_PLUGIN_FILE', $this->file );
		}
	}

	/**
	 * Loads the plugin language files
	 *
	 * @access public
	 * @since 1.0
	 * @return void
	 */
	public function load_textdomain() {

		// Set filter for plugin's languages directory.
		$lang_dir = dirname( plugin_basename( $this->file ) ) . '/languages/';
		$lang_dir = apply_filters( 'affiliatewp_leaderboard_languages_directory', $lang_dir );

		// Traditional WordPress plugin locale filter.
		$locale   = apply_filters( 'plugin_locale', get_locale(), 'affiliatewp-leaderboard' );
		$mofile   = sprintf( '%1$s-%2$s.mo', 'affiliatewp-leaderboard', $locale );

		// Setup paths to current locale file.
		$mofile_local  = $lang_dir . $mofile;
		$mofile_global = WP_LANG_DIR . '/affiliatewp-leaderboard/' . $mofile;

		if ( file_exists( $mofile_global ) ) {
			// Look in global /wp-content/languages/affiliatewp-leaderboard/ folder.
			load_textdomain( 'affiliatewp-leaderboard', $mofile_global );
		} elseif ( file_exists( $mofile_local ) ) {
			// Look in local /wp-content/plugins/affiliatewp-leaderboard/languages/ folder.
			load_textdomain( 'affiliatewp-leaderboard', $mofile_local );
		} else {
			// Load the default language files.
			load_plugin_textdomain( 'affiliatewp-leaderboard', false, $lang_dir );
		}
	}

	/**
	 * Include necessary files
	 *
	 * @access      private
	 * @since       1.0.0
	 * @return      void
	 */
	private function includes() {
		require_once AFFWP_LB_PLUGIN_DIR . 'includes/class-widget.php';
	}

	/**
	 * Setup the default hooks and actions
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	private function hooks() {

		// shortcode.
		add_shortcode( 'affiliate_leaderboard', array( $this, 'affiliate_leaderboard' ) );

		// css.
		add_action( 'wp_head', array( $this, 'css' ) );

		// plugin meta.
		add_filter( 'plugin_row_meta', array( $this, 'plugin_meta' ), null, 2 );

	}

	/**
	 * Affiliate leaderboard shortcode
	 *
	 * @since  1.0
	 * @param array  $atts Shortcode attributes.
	 * @param string $content Shortcode content.
	 * @return string
	 */
	public function affiliate_leaderboard( $atts, $content = null ) {

		shortcode_atts(
			array(
				'number'    => 10, // show 10 by default.
				'referrals' => '',
				'earnings'  => '',
				'visits'    => '',
				'orderby'   => 'referrals',
			),
			$atts,
			'affiliate_leaderboard'
		);

		$content = $this->show_leaderboard( $atts );

		return do_shortcode( $content );
	}

	/**
	 * Get referrals
	 *
	 * @since  1.0
	 *
	 * @param array $args Shortcode attributes.
	 * @return string
	 */
	public function show_leaderboard( $args = array() ) {

		$defaults = apply_filters( 'affwp_leaderboard_defaults',
			array(
				'number'  => isset( $args['number'] ) ? $args['number'] : 10,
				'orderby' => isset( $args['orderby'] ) ? $args['orderby'] : 'referrals',
			)
		);

		$args = wp_parse_args( $args, $defaults );

		// show an affiliate's earnings.
		$show_earnings = isset( $args['earnings'] ) && ( 'yes' == $args['earnings'] || 'on' == $args['earnings'] ) ? true : false;

		// show an affiliate's referrals.
		$show_referrals = isset( $args['referrals'] ) && ( 'yes' == $args['referrals'] || 'on' == $args['referrals'] ) ? true : false;

		// show an affiliate's visits.
		$show_visits = isset( $args['visits'] ) && ( 'yes' == $args['visits'] || 'on' == $args['visits'] ) ? true : false;

		// get affiliates.
		$affiliates = affiliate_wp()->affiliates->get_affiliates( $defaults );

		ob_start();

		if ( $affiliates ) : ?>

		<ol class="affwp-leaderboard">
		<?php foreach( $affiliates as $affiliate  ) : ?>
			<li>
			<?php
				// affiliate name.
				echo affiliate_wp()->affiliates->get_affiliate_name( $affiliate->affiliate_id );

				$to_show = apply_filters(
					'affwp_leaderboard_to_show',
					array(
						'referrals' => $show_referrals,
						'earnings'  => $show_earnings,
						'visits'    => $show_visits,
					)
				);

				$output = array();

				if ( $to_show ) {
					foreach ( $to_show as $key => $value ) {

						if ( $value && 'referrals' === $key ) {
							$output[] = absint( $affiliate->referrals ) . ' ' . __( 'referrals', 'affiliatewp-leaderboard' );
						}

						if ( $value && 'earnings' === $key ) {
							$output[] = affwp_currency_filter( affwp_format_amount( $affiliate->earnings ) ) . ' ' . __( 'earnings', 'affiliatewp-leaderboard' );
						}

						if ( $value && 'visits' === $key ) {
							$output[] = absint( $affiliate->visits ) . ' ' . __( 'visits', 'affiliatewp-leaderboard' );
						}

					}
				}

				$output = implode( '&nbsp;&nbsp;<span class="divider">|</span>&nbsp;&nbsp;', $output );

				if ( $output ) {
					echo '<p>' . $output . '</p>';
				}

				?></li>
		<?php endforeach; ?>
		</ol>
		<?php else : ?>
			<?php _e( 'No registered affiliates', 'affiliatewp-leaderboard' ); ?>
		<?php endif; ?>


		<?php

		$html = ob_get_clean();

		return apply_filters( 'affwp_show_leaderboard', $html, $affiliates, $show_referrals, $show_earnings, $show_visits );
	}

	/**
	 * CSS styling
	 *
	 * @since  1.0
	 * @return void
	 */
	public function css() {
		?>
		<style>.affwp-leaderboard p{font-size:80%;color:#999;}</style>
		<?php
	}


	/**
	 * Modify plugin metalinks
	 *
	 * @access      public
	 * @since       1.0.0
	 * @param       array $links The current links array
	 * @param       string $file A specific plugin table entry
	 * @return      array $links The modified links array
	 */
	public function plugin_meta( $links, $file ) {
		if ( plugin_basename( $this->file ) === $file ) {
				$plugins_link = array(
						'<a title="' . __( 'Get more add-ons for AffiliateWP', 'affiliatewp-leaderboard' ) . '" href="http://affiliatewp.com/addons/" target="_blank">' . __( 'Get add-ons', 'affiliatewp-leaderboard' ) . '</a>',
				);

				$links = array_merge( $links, $plugins_link );
		}

		return $links;
	}
}

/**
 * The main function responsible for returning the one true AffiliateWP_Leaderboard
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $affiliatewp_leaderboard = affiliatewp_leaderboard_load(); ?>
 *
 * @since 1.0
 * @return object The one true AffiliateWP_Leaderboard Instance
 */
function affiliatewp_leaderboard_load() {
	return AffiliateWP_Leaderboard::instance();
}
