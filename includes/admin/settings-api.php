<?php
function hmu_settings_api()
{
    register_setting('hmu_opts_group', 'hmu_opts', 'hmu_opts_sanitaze');

    add_settings_section(
        'hmu_settings',
        __('Configurações do WebHook Hotmart', 'hotwebhookuser'),
        'hmu_settings_section',
        'hmu_opts_sections'
    );

    add_settings_field(
        'hmu_hotmart_token',
        __('Token do Hotmart', 'hotwebhookuser'),
        'hmu_hotmart_token_required_input',
        'hmu_opts_sections',
        'hmu_settings'
    );
}

function hmu_settings_section()
{
    $message = __('Você pode configurar o WebHook aqui!', 'hotwebhookuser');
    echo "<p> {$message} </p>";
}


function hmu_hotmart_token_required_input () {
    ?>
        <input type="text" id="hmu_hotmart_token_required_input" placeholder="Insira o seu token aqui!"  name="hmu_opts[hmu_hotmart_token_required_input]" >
    <?php
}