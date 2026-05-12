# Fluxos de Autenticação

## Recuperação de senha

O fluxo de recuperação de senha está correto no nível de serviço: um token é gerado para um e-mail existente, salvo em `password_reset_tokens`, validado pela coluna `token` e rejeitado quando não existe ou está expirado. A expiração usa `config('auth.passwords.users.expire')`.

Rotas:

- `POST /api/auth/recuperar-senha/{email}`: gera e envia o código quando o e-mail existe.
- `POST /api/auth/recuperar-senha/validar/{codigo}`: valida o código informado.

Pontos de atenção:

- A validação apenas confirma o token. A troca efetiva da senha deve chamar `AlteraSenha` em uma etapa posterior.
- Tokens são substituídos por e-mail, então só o código mais recente permanece válido.

## Verificação de e-mail

O cadastro retorna uma resposta genérica para não revelar se o e-mail já existe. Quando um novo usuário é criado, a API gera um token em `email_verification_tokens` e dispara `VerificarEmailEvent`, que envia o e-mail por `VerificarEmailMailable`.

Rotas:

- `POST /api/signup`: cria o usuário e envia o código de confirmação.
- `POST /api/auth/email/verificacao/{email}`: reenvia o código se o cadastro existir e ainda não estiver verificado.
- `POST /api/auth/email/verificar/{codigo}`: valida o código, preenche `usuarios.email_verified_at` e remove o token.

Regras:

- A expiração usa `config('auth.email_verification.expire')`, atualmente 60 minutos.
- Token inexistente ou expirado retorna erro de validação no campo `token`.
- Usuários já verificados não recebem novo código de verificação.
- A resposta de reenvio também é genérica para evitar enumeração de e-mails.
