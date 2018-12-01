<?php 
/**
 * Beta
 * @package Hotmart_Webhook_User_Creat.
 * @version 1.0
 */
/*
Plugin Name:  Webhook Criar Usuário (Hotmart).
Plugin URI:   https://developer.wordpress.org/plugins/the-basics/
Description:  Plugin para WordPress  que realiza a integração com o WebHook do Hotmart. O plugin tem como função criar um usuário em seu blog quando o cliente efetivar uma compra.
Version:      1.0
Author:       Maycon Rayone Rodrigues Xavier
Author URI:   https://github.com/mrayone
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  hotwebhookuser
Domain Path:  /languages
*/

if( ! function_exists('add_action') ) {
    echo __('Olá, Eu sou apenas um plugin, não posso ser acessado diretamente.', 'hotwebhookuser');
}

//Setup
define ('HMU_PLUGIN_URL', __FILE__);

//Includes;
include( 'includes/active.php' );
include( 'includes/deactivate.php' );
include( 'includes/init.php' );
include( 'includes/admin/init.php' );
include( 'includes/admin/menus.php' );
include( 'includes/admin/plugin-options-page.php' );
include( 'includes/api/routes.php' );
include( 'includes/api/UserController.php' );
require 'assets/libs/vendor/autoload.php';

//Hooks
register_activation_hook( __FILE__, 'hmu_active_plugin' );
register_activation_hook( __FILE__, 'hmu_deactivate_plugin' );
add_action( 'admin_init', 'hmu_admin_init' );
add_action( 'admin_menu', 'hmu_admin_menus' );
add_action( 'rest_api_init', 'hmu_rest_routes_init' );