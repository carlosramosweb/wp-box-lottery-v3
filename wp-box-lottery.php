<?php

/**
 *
 * @link              https://criacaocriativa.com
 * @since             1.0.0
 * @package           WP_Box_Lottery_v3 
 *
 * @wordpress-plugin
 * Plugin Name:       Resultados Loteria da Caixa v3
 * Plugin URI:        https://plugins.criacaocriativa.com
 * Description:       Exibe os resultados da loterias da Caixa Economica Federal via shortcode por nome da Sorteio (ex: Mega Sena) e Número do Concurso (last ou nº) no wordpress de forma elegante.
 * Version:           1.0.0
 * Author:            carlosramosweb
 * Author URI:        https://criacaocriativa.com
 * Donate link: 	  https://donate.criacaocriativa.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-box-lottery-v3
 * Domain Path:       /languages
 */

if ( ! defined( 'WPINC' ) ) {
	die();
}

if ( ! class_exists( 'WP_Box_Lottery_v3' ) ) {
		
	class WP_Box_Lottery_v3 {	

		public $key;

		public $lottery;
		public $game;

		public function __construct() {	
			register_activation_hook( __FILE__, array( $this, 'plugin_activate' ) );
			add_action( 'init', array( $this, 'plugin_start' ) );			
		}

		public function plugin_start() {
			add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'plugin_action_links_settings' ) );
			add_action('admin_menu', array( $this, 'register_admin_menu' ));	
			add_action( 'wp_footer',  array( $this, 'product_single_loop_styles' ), 10 );	
			add_shortcode('wp_box_lottery_v3', array( $this, 'get_show_shortcode' ) );					
		}

		public static function plugin_activate() {	
			add_option( 'Activated_Plugin', 'wp-box-lottery-v3' );	  
			if ( is_admin() && get_option( 'Activated_Plugin' ) == 'wp-box-lottery-v3' ) {				
				$settings = array(
					'enabled' 	=> 'yes',
					'key' 		=> 'eAIk7FIFmuXcT5z'
				);				
				update_option( 'wp_box_lottery_v3_settings', $settings, 'yes' );
			}
		}

		public function get_herokuapp_api_results() {	
			$url = 'https://apiloterias.com.br/app/resultado';
			$api_url = $url . $this->lottery . $this->key . $this->game;
			$response = file_get_contents($api_url);
			$result = json_decode($response, true);
			if (isset($result['erro'])) {
				return false;
			} else {
				return $result;
			}			
		}

		public function get_show_shortcode( $atts ) {
			$settings = array();
			$settings = get_option( 'wp_box_lottery_v3_settings' );

			$plugin_dir_url = plugin_dir_url( __DIR__ ) . 'wp-box-lottery-v3/';
			$this->lottery = (isset($atts['lottery'])) ? '?loteria=' . $atts['lottery'] : '';
			$this->key = (isset($settings['key'])) ? '&token=' . $settings['key'] : '';
			$this->game = (isset($atts['game'])) ? '&concurso=' . $atts['game'] : 'last';
			$results = $this->get_herokuapp_api_results();

			@include( plugin_dir_path( __FILE__ ) . 'includes/html-shortcode.php' );
			return;
		}

		public function get_total_premium( $awards ) {
			$total = 0;
			if (isset($awards)) {
				foreach ($awards as $key => $row) {
					if (isset($row['quantidade_ganhadores'])) {
						$premium = str_replace('R$', '', $row['valor_total']);
						$premium = str_replace('.', '', $premium);
						$premium = str_replace(',', '.', $premium);
						$premium = trim($premium);
						$total += (intval($row['quantidade_ganhadores']) * $premium);

					} else if (isset($row['bilhete'])) {
						$premium = str_replace('R$', '', $row['valor_total']);
						$premium = str_replace('.', '', $premium);
						$premium = str_replace(',', '.', $premium);
						$premium = trim($premium);
						$total += ($premium);
					}

				}
			}
			return number_format($total, 2, ',', '.');
		}

		public function plugin_action_links_settings( $links ) {
			$action_links = array(
				'settings' 	=> '<a href="' . admin_url('admin.php?page=settings-box-lottery-v3') . '" title="Configurações" class="error">Configurações</a>',
				'donate' 	=> '<a href="' . esc_url( 'https://donate.criacaocriativa.com') . '" title="Doação Plugin" class="error">Doação</a>',
			);
			return array_merge( $action_links, $links );
		}

		public function register_admin_menu() {		
			add_menu_page(
				'Configurações - Loteria da Caixa - Resultados',
				'Resultados da Loteria',
				'manage_options',
				'settings-box-lottery-v3',
				array( $this, 'settings_box_lottery_page_callback'),
				'dashicons-awards',
				6
			);
		}

		public function settings_box_lottery_page_callback() {
			@include_once( plugin_dir_path( __FILE__ ) . 'includes/settings-admin.php' );
		}

		public static function product_single_loop_styles() {
			?>
			<style type="text/css">
			</style>
			<script type="text/javascript">
	        jQuery(document).ready(function() {
	        });
			</script>
			<?php
		}

		public function get_next_number_lottery($lottery) {
			return ($lottery + 1);
		}

		public function get_class_name_lottery($name) {
			switch (strtolower($name)) {
				case 'megasena':
					return 'mega-sena';
					break;

				case 'lotofÁcil':
					return 'lotofacil';
					break;

				case 'duplasena':
					return 'dupla-sena';
					break;

				case 'federal':
					return 'loteria-federal';
					break;

				case 'dia de sorte':
					return 'dia-de-sorte';
					break;

				case 'super sete':
					return 'super-sete';
					break;

				default:
					return strtolower($name);
					break;
			}
		}

	}
	$Box_Lottery_v3 = new WP_Box_Lottery_v3();
}