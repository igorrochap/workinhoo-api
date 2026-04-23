# Autenticacao

## Visao Geral

A API utiliza **Laravel Sanctum** no modo **SPA Authentication (stateful)**. A autenticacao e baseada em sessoes armazenadas em cookies HTTP-only seguros — nao utilizamos tokens JWT nem Personal Access Tokens.

Isso significa que:
- O cookie de sessao e HTTP-only (inacessivel via JavaScript no navegador)
- O cookie e automaticamente enviado pelo navegador em todas as requisicoes
- Todas as requisicoes POST/PUT/DELETE exigem um token CSRF valido

### Por que Sanctum SPA e nao JWT?

O frontend (Vue.js) e uma aplicacao first-party. O Sanctum SPA Auth foi projetado exatamente para esse cenario: uma SPA consumindo uma API no mesmo dominio (ou subdominio). JWT seria uma complexidade desnecessaria para este caso de uso.

---

## Endpoints

| Metodo | URI                     | Autenticado | Descricao                           |
|--------|-------------------------|-------------|-------------------------------------|
| GET    | `/sanctum/csrf-cookie`  | Nao         | Obtém o cookie CSRF (rota do Sanctum) |
| POST   | `/api/auth/login`       | Nao         | Realiza login                       |
| POST   | `/api/auth/logout`      | Sim         | Realiza logout                      |
| GET    | `/api/auth/me`          | Sim         | Retorna dados do usuario autenticado |

### POST /api/auth/login

O campo `credencial` aceita **email**. A API detecta automaticamente qual e qual:
- Se contem `@`, trata como email
- Caso contrario, trata como CPF

**Request:**

```json
{
  "email": "usuario@email.com",
  "senha": "sua-senha"
}
```

**Response 200 (sucesso):**

```json
{
  "uuid": "019ccde3-67e3-7075-b96d-b3299a52684d",
  "nome": "João Silva",
  "email": "joao@email.com",
  "is_prestador": false
}
```

**Response 422 (credenciais incorretas):**

```json
{
  "message": "As credenciais informadas estão incorretas.",
  "errors": {
    "credencial": ["As credenciais informadas estão incorretas."]
  }
}
```

**Response 422 (validacao):**

```json
{
  "message": "A credencial é obrigatória. (and 1 more error)",
  "errors": {
    "credencial": ["A credencial é obrigatória."],
    "senha": ["A senha é obrigatória."]
  }
}
```

### POST /api/auth/logout

Nao requer body. Requer autenticacao.

**Response:** `204 No Content`

### GET /api/auth/me

Nao requer body. Requer autenticacao.

**Response 200:**

```json
{
  "uuid": "019ccde3-67e3-7075-b96d-b3299a52684d",
  "nome": "João Silva",
  "email": "joao@email.com",
  "is_prestador": false
}
```

**Response 401 (nao autenticado):**

```json
{
  "message": "Unauthenticated."
}
```

---

## Fluxo de Autenticacao

### Como funciona por baixo dos panos

1. O Sanctum usa o middleware `EnsureFrontendRequestsAreStateful` nas rotas `api`
2. Quando uma requisicao chega com um header `Origin` ou `Referer` de um **dominio stateful** (configurado em `config/sanctum.php`), o Sanctum aplica automaticamente os middlewares de sessao, cookies e CSRF
3. Isso transforma a rota API em uma rota "web-like" — com sessao e protecao CSRF
4. Requisicoes de dominios nao-stateful (ex: apps mobile) nao recebem esse tratamento

### Dominios stateful configurados (desenvolvimento)

```
localhost, localhost:3000, 127.0.0.1, 127.0.0.1:8000, ::1
```

Para adicionar o dominio do frontend de producao, configure a variavel de ambiente:

```env
SANCTUM_STATEFUL_DOMAINS=dominio-do-frontend.com,localhost,localhost:3000
```

---

## Integracao com o Frontend (Vue.js / Axios)

### 1. Configuracao do Axios

O Axios precisa enviar cookies em todas as requisicoes e incluir automaticamente o token CSRF.

```js
import axios from 'axios'

const api = axios.create({
  baseURL: 'http://localhost:8000',
  withCredentials: true,          // Envia cookies em todas as requisicoes
  withXSRFToken: true,            // Envia o X-XSRF-TOKEN automaticamente
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
  },
})
```

> **Importante:** `withCredentials: true` e obrigatorio. Sem isso, o navegador nao envia os cookies de sessao e CSRF, e a autenticacao nao funciona.

> **Importante:** `withXSRFToken: true` faz o Axios ler o cookie `XSRF-TOKEN` e enviar seu valor no header `X-XSRF-TOKEN` automaticamente em requisicoes POST/PUT/DELETE.

### 2. Fluxo de login

```js
// Passo 1: Obter o cookie CSRF
// O Sanctum retorna um cookie XSRF-TOKEN e um cookie de sessao
// O Axios armazena esses cookies automaticamente
await api.get('/sanctum/csrf-cookie')

// Passo 2: Fazer login
// O Axios envia automaticamente o X-XSRF-TOKEN header (extraido do cookie)
const response = await api.post('/api/auth/login', {
  credencial: 'usuario@email.com',  // ou CPF: '000.000.000-00'
  senha: 'sua-senha',
})

// response.data contém: { uuid, nome, email, perfil }
```

### 3. Requisicoes autenticadas

Apos o login, todas as requisicoes subsequentes sao autenticadas automaticamente via cookie de sessao. Nao e necessario gerenciar tokens manualmente.

```js
// O cookie de sessao e enviado automaticamente pelo navegador
const me = await api.get('/api/auth/me')
const usuarios = await api.get('/api/usuarios')
```

### 4. Logout

```js
await api.post('/api/auth/logout')
```

### 5. Verificacao de autenticacao (ex: ao carregar a aplicacao)

Para verificar se o usuario ja esta autenticado (ex: ao recarregar a pagina):

```js
try {
  const response = await api.get('/api/auth/me')
  // Usuario autenticado — response.data contem os dados
} catch (error) {
  if (error.response?.status === 401) {
    // Usuario nao autenticado — redirecionar para login
  }
}
```

---

## Testando com Insomnia / Postman

Ferramentas como Insomnia e Postman nao enviam automaticamente o header `Origin`, que e necessario para o Sanctum ativar a sessao. Siga estes passos:

### Configuracao inicial

1. Certifique-se de que o **cookie storage** esta habilitado (Insomnia e Postman fazem isso por padrao)
2. Adicione o header `Origin: http://localhost` em **todas** as requisicoes

### Passo a passo para login

**1. Obter o CSRF token:**

```
GET http://localhost:8000/sanctum/csrf-cookie
Header: Origin: http://localhost
```

Apos essa requisicao, dois cookies serao salvos automaticamente:
- `XSRF-TOKEN` — o token CSRF (criptografado)
- `laravel_session` (ou nome similar) — o cookie de sessao

**2. Fazer login:**

```
POST http://localhost:8000/api/auth/login
Header: Origin: http://localhost
Header: Content-Type: application/json
Header: X-XSRF-TOKEN: <valor do cookie XSRF-TOKEN>
Body: { "credencial": "usuario@email.com", "senha": "password" }
```

> **Atencao:** O valor do cookie `XSRF-TOKEN` pode conter `%3D` (que e o caractere `=` codificado em URL). Ao copiar o valor para o header `X-XSRF-TOKEN`, **decodifique** o valor — substitua `%3D` por `=`.

**3. Acessar rotas protegidas:**

```
GET http://localhost:8000/api/auth/me
Header: Origin: http://localhost
```

Os cookies de sessao sao enviados automaticamente pelo Insomnia/Postman.

### Erros comuns no Insomnia/Postman

| Erro | Causa | Solucao |
|------|-------|---------|
| `419 CSRF token mismatch` | Header `X-XSRF-TOKEN` ausente ou incorreto | Copie o valor do cookie `XSRF-TOKEN` para o header (URL-decoded) |
| `419 CSRF token mismatch` | Cookie de sessao nao esta sendo enviado | Verifique se os cookies estao habilitados e se o header `Origin` esta presente |
| `401 Unauthenticated` | Sessao nao ativa ou expirada | Refaca o fluxo: csrf-cookie -> login -> requisicao |
| `Session store not set` | Header `Origin` ausente | Adicione `Origin: http://localhost` na requisicao |

---

## Rotas Protegidas

Todas as rotas de `/api/usuarios` exigem autenticacao (`auth:sanctum`). Requisicoes sem sessao valida recebem `401 Unauthenticated`.

---

## Configuracao de Ambiente

### Variaveis relevantes no `.env`

```env
# URL da aplicacao (usada pelo Sanctum para determinar dominios stateful)
APP_URL=http://localhost

# Driver de sessao (deve ser 'database' para persistencia)
SESSION_DRIVER=database

# Dominio do cookie de sessao (null = dominio atual)
SESSION_DOMAIN=null

# Dominios stateful do Sanctum (frontend e ferramentas de teste)
# Se nao definido, usa: localhost, localhost:3000, 127.0.0.1, 127.0.0.1:8000
# Em producao, inclua o dominio do frontend
SANCTUM_STATEFUL_DOMAINS=dominio-do-frontend.com,localhost

# Cookie seguro (HTTPS only) — false para dev local, true para producao
SESSION_SECURE_COOKIE=false
```

### Producao

Em producao, ajuste obrigatoriamente:

```env
APP_URL=https://api.seudominio.com
SESSION_DOMAIN=.seudominio.com
SESSION_SECURE_COOKIE=true
SANCTUM_STATEFUL_DOMAINS=app.seudominio.com
```

---

## Arquitetura do Codigo

```
app/
├── Actions/Auth/
│   ├── AutenticaUsuario.php     # Logica de login
│   └── DeslogaUsuario.php       # Logica de logout
├── DTO/
│   ├── Request/Auth/
│       └── LoginDTO.php         # Dados de entrada do login
├── Http/
│   ├── Controllers/Auth/
│   │   └── AuthController.php   # Controller de autenticacao
│   └── Requests/Auth/
│       └── LoginRequest.php     # Validacao do login
routes/
└── api.php                      # Rotas de autenticacao
config/
├── auth.php                     # Guard e provider (Usuario model)
└── sanctum.php                  # Dominios stateful e middlewares
```
