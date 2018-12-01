# hotmart-webhook-create-account
Plugin para WordPress  que realiza a integração com o WebHook do Hotmart.
O plugin tem como função criar um usuário em seu blog quando o cliente efetivar uma compra.
* Integração com API do SendGrid E-mail.
* Verifica o campo status do WebHook para criar usuário quando a compra estiver "aprovada" e excluir o mesmo caso seja solicitado
estorno ou reembolso.  