<?php 
/**
 * @package Hotmart_Webhook_User_Creat.
 * @version 1.0
 */
/*
Plugin Name:  Hotmart Webhook User Creat.
Plugin URI:   https://developer.wordpress.org/plugins/the-basics/
Description:  Este plugins foi criado para fazer um usuário em seu blog quando houver uma confirmação de pagamento.
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
include( 'include/init.php' );

