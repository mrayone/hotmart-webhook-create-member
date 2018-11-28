<?php

function hmu_admin_menus() {
    add_menu_page(
      __('WebHook Hotmart', 'hotwebhookuser'),
      __('WebHook Hotmart', 'hotwebhookuser'),
      'edit_theme_options', 
      'hmu_plugin_opts',
      'hmu_plugin_opts_page'
    );
}