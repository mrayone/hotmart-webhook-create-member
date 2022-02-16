# Hotmart Criador de Membros WebHook (Hotmart Member Creator WebHook)

O hotmart member creator gera um Webhook de integração do hotmart e o seu blog. Ao ativar este plugin você contém as seguintes funcionalidades:

1. Criação de membros com a função padrão definida.
2. Integração para envio de e-mail com SendGrid.
3. Customização de mensagens de e-mail com **_Helpers_**.

## Requisitos :warning:

- PHP: >=5.6
- WordPress: >= 5.6
- Hotmart WebAPI: V1.
- Conhecimento mínimo de wordpress e instalação de plugins.

> O hotmart atualizou a sua versão de API, logo mais haverá a atualização no plugin também. Para novos usuários sugiro aguardar a modificação a menos que tenha disponível a V1 da API para você.

## Instalação

Para instalar no seu blog, basta baixar o pacote de release aqui do github em .zip e adicionar no seu blog, conforme as imagens 1 e 2.

#### 1. Figura da área de releases.

![image](https://user-images.githubusercontent.com/17658240/154344685-4b386c38-3472-45c8-acbb-bfb19472213f.png)

#### 2. Figura da área de releases.

![image](https://user-images.githubusercontent.com/17658240/154344807-97f3499b-aa34-4a48-b8e3-357553a0d216.png)

- Obs: O Download desta seção está relacionada a versão do código conforme a descrição.
- Para baixar basta clicar em **Source code** conforme a extensão desejada.

### 3. Envio do plugin

No seu blog você deve acessar a página `https://<url-do-seu-blog>/wp-admin/plugin-install.php` e enviar o plugin `hotmart-webhook-create-member-<versao>.zip`

![image](https://user-images.githubusercontent.com/17658240/154352665-7b060e5a-0406-4315-ab8d-5f1e86aab6b8.png)

![image](https://user-images.githubusercontent.com/17658240/154352704-8eb1a21a-dfa8-4b18-bddb-e2e983491df7.png)

### Instalação via página de plugins do wordpress

:construction:

### Configuração

A configuração exige a informação do token do [hotmart](https://app-vlc.hotmart.com/tools/webhook/auth), e para isso você precisa estar registrado devidamente na plataforma.

![localhost_8080_wp-admin_admin php_page=hmu_plugin_opts](https://user-images.githubusercontent.com/17658240/154352086-fdfcd699-21d3-4d27-9d44-adc5fd4452a8.png)

### Como configurar no hotmart?

O próprio hotmart já disponibiliza uma [documentação](https://developers.hotmart.com/docs/pt-BR/1.0.0/webhook/about-webhook/) bem interessante, replica-la aqui seria repetitivo.

### Configuração do .htaccess (Servidor Apache)

https://github.com/mrayone/hotmart-webhook-create-member/blob/master/examples/.htaccess

> Obrigado pela utilização desta solução.

License
MIT
