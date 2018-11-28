<?php 
/**
 * Função que verifica a versão do WordPress e chama o método init.
 * @return void
 */
function hmu_active_plugin() {
    if( version_compare ( get_bloginfo('version'), '4.5', '<' ) ) {
        wp_die( __('Você precisa atualizar a versão do seu WordPress para usar este plugin', 'hotwebhookuser'), "hotwebhookuser" );
    }

    hmu_init();
}