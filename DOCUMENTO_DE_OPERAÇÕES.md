# Documento de Operação

**Sistema de Gestão de uma Empresa de Veículos Autônomos de Entrega**

Este documento descreve a compilação, a instalação e a configuração do sistema no Windows, incluindo o servidor web (Nginx com PHP) e o SGBD (PostgreSQL), além das credenciais de acesso de cada perfil e das rotinas de operação.

Os comandos são para o PowerShell executado como administrador, salvo indicação em contrário.

## 1. Visão geral

O sistema é uma aplicação web em Laravel, com dados em PostgreSQL. O acesso ao banco é feito com SQL puro pela fachada DB, sem ORM. As regras críticas ficam em procedimentos armazenados.

Componentes:

- Aplicação: Laravel, com PHP 8.2 ou superior
- Servidor web: Nginx for Windows, com PHP em modo FastCGI (php-cgi)
- Banco de dados: PostgreSQL 14 ou superior, instalado pelo instalador oficial
- Scripts de banco: CREATE_BD.sql, INSERT.sql e PROC_ARMZ_CASOS_DE_USO.sql

## 2. Requisitos de ambiente

- Windows 10 ou Windows 11, ou Windows Server 2019 ou superior
- PHP 8.2 ou superior, versão Thread Safe (x64), baixada em windows.php.net
- Extensões PHP: pdo_pgsql, pgsql, mbstring, openssl, fileinfo, curl, xml, zip, bcmath, tokenizer
- Composer 2.x, pelo instalador Composer-Setup.exe (getcomposer.org)
- PostgreSQL 14 ou superior, pelo instalador da EDB (postgresql.org/download/windows)
- Nginx for Windows, baixado em nginx.org

## 3. Compilação

O sistema não exige compilação, porque o Laravel é interpretado pelo PHP. A etapa de preparo consiste em instalar as dependências e gerar os caches de produção:

```powershell
cd C:\frota
composer install --no-dev --optimize-autoloader
Copy-Item .env.example .env
php artisan key:generate
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Ao alterar o `.env` ou as rotas, limpe o cache antes com `php artisan config:clear`, `route:clear` ou `view:clear`, e gere-o novamente.

## 4. Instalação

### 4.1 PHP

1. Extraia o PHP em `C:\php`.
2. Copie `php.ini-production` para `php.ini` dentro da mesma pasta.
3. Abra o `php.ini` e remova o `;` do início das linhas abaixo:

```ini
extension_dir = "ext"
extension=curl
extension=fileinfo
extension=mbstring
extension=openssl
extension=pdo_pgsql
extension=pgsql
extension=xml
extension=zip
extension=bcmath
extension=tokenizer
```

4. Adicione `C:\php` à variável de ambiente `Path` do sistema.
5. Confira a instalação com `php -v` e `php -m`. Os módulos `pdo_pgsql` e `pgsql` devem aparecer na lista.

### 4.2 Composer

Execute o instalador Composer-Setup.exe e confirme o caminho do `php.exe` durante a instalação. Depois verifique com `composer -V`.

### 4.3 Nginx

1. Extraia o Nginx em `C:\nginx`.
2. Confira que `C:\nginx\nginx.exe` existe.

### 4.4 Código da aplicação

Copie o projeto para a pasta desejada, por cópia de pasta ou arquivo compactado, e depois execute o `composer install` da seção 3.

### 4.5 Permissões

O PHP precisa escrever em `storage` e em `bootstrap\cache`. Conceda permissão de modificação ao usuário que executa o PHP:

```powershell
icacls "C:\pastadoprojeto\storage" /grant "Users:(OI)(CI)M"
icacls "C:\pastadoprojeto\bootstrap\cache" /grant "Users:(OI)(CI)M"
```

Em ambiente de produção, prefira conceder a permissão apenas a uma conta de serviço dedicada, no lugar do grupo Users.

## 5. Configuração do SGBD (PostgreSQL)

### 5.1 Instalação e serviço

Durante a instalação pelo instalador da EDB, anote a senha do superusuário `postgres` e mantenha a porta 5432. Ao final, o serviço fica registrado como `postgresql-x64-16` (o número varia com a versão). Confira com:

```powershell
Get-Service postgresql*
```

Adicione a pasta de binários à variável `Path`, para usar `psql` e `pg_dump` em qualquer lugar:

```
C:\Program Files\PostgreSQL\16\bin
```

### 5.2 Usuário e banco

O banco usado neste documento se chama `svga`. Ajuste o nome se necessário.

```powershell
psql -U postgres -h 127.0.0.1
```

### 5.3 Acesso local por senha

O arquivo de configuração fica em `C:\Program Files\PostgreSQL\16\data`.

Em `postgresql.conf`, mantenha a escuta apenas local:

```
listen_addresses = 'localhost'
```

Em `pg_hba.conf`, inclua a regra de acesso local com senha, antes das demais regras de `host`:

```
host    svga    frota_app    127.0.0.1/32    scram-sha-256
```

Reinicie o serviço:

```powershell
Restart-Service postgresql-x64-16
```

### 5.4 Estruturas e carga de dados

A ordem de execução é obrigatória. Execute a partir da pasta onde estão os arquivos SQL:

```powershell
cd C:\frota\sql
psql -h 127.0.0.1 -U frota_app -d svga -f CREATE_BD.sql
psql -h 127.0.0.1 -U frota_app -d svga -f INSERT.sql
psql -h 127.0.0.1 -U frota_app -d svga -f procedimentos_casos_de_uso_postgres.sql
```

1. `CREATE_BD.sql` cria as tabelas, as restrições e as chaves estrangeiras.
2. `INSERT.sql` popula perfis, usuários, restaurantes, alimentos, veículos, cofres, viagens, pedidos e entregas. Ele cria a extensão pgcrypto, usada no hash de senha do Joao.
3. `PROC_ARMZA_CASOS_DE_USO.sql` cria a função `fn_tem_perfil` e os procedimentos armazenados.

Depois execute novamente o `INSERT.sql`.

### 5.5 Senhas de acesso

Os hashes fictícios do `INSERT.sql` não permitem login. Para os perfis de teste, foi definido a senha `12345678`:

## 6. Configuração do servidor web (Nginx com PHP)

### 6.1 Iniciar o PHP em modo FastCGI

O Nginx não executa PHP sozinho. Inicie o `php-cgi` na porta 9000, em uma janela do PowerShell que deve ficar aberta:

```powershell
C:\php\php-cgi.exe -b 127.0.0.1:9000
```

Para que ele inicie automaticamente, crie uma tarefa agendada que execute esse comando na inicialização do Windows, ou use um gerenciador de serviços como NSSM.

### 6.2 Configuração do Nginx

Edite `C:\nginx\conf\nginx.conf` e substitua o bloco `server` padrão por:

```nginx
server {
    listen 80;
    server_name frota.local;
    root C:/frota/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known) {
        deny all;
    }
}
```

Observe que o caminho usa barras normais (`C:/pastadoprojeto/public`), que o Nginx aceita no Windows.

Para que o nome `pastadoprojeto.local` funcione, adicione a linha abaixo ao arquivo `C:\Windows\System32\drivers\etc\hosts`, com o editor aberto como administrador:

```
127.0.0.1    pastadoprojeto.local
```

Teste a configuração e inicie o Nginx:

```powershell
cd C:\nginx
.\nginx.exe -t
Start-Process .\nginx.exe
```

Para recarregar após alterações, use `.\nginx.exe -s reload`. Para encerrar, use `.\nginx.exe -s stop`.

### 6.3 HTTPS

Para atender ao requisito RNF006 (comunicação segura), publique o sistema com HTTPS. Em produção, use um certificado de autoridade reconhecida. Em ambiente de homologação, um certificado autoassinado gerado com o OpenSSL é suficiente, desde que o navegador seja configurado para aceitá-lo.

## 7. Configuração da aplicação

Edite `C:\frota\.env`:

```env
APP_NAME=FrotaAutonoma
APP_ENV=production
APP_DEBUG=false
APP_URL=http://frota.local

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=svga
DB_USERNAME=frota_app
DB_PASSWORD=troque_esta_senha

SESSION_DRIVER=file
SESSION_LIFETIME=120
```

Com `APP_DEBUG=true`, os erros expõem código e configuração ao usuário, por isso o valor deve ser `false` em produção. Depois de alterar o `.env`, rode:

```powershell
php artisan config:clear
php artisan config:cache
```

## 8. Credenciais de acesso

O login é feito pelo e-mail cadastrado, e não pelo primeiro nome.

| Perfil | E-mail de acesso | Senha de teste | Acesso principal |
|---|---|---|---|
| Administrador | pedro.almeida@empresa.com | 12345678 | Frota, pedidos, monitoramento e restaurantes |
| Operador | ana.ferreira@empresa.com | 12345678 | Monitoramento de rotas e pedidos |
| Cliente | joao.silva@email.com | 12345678 | Meus pedidos e restaurantes |

As senhas são apenas para homologação e devem ser trocadas antes de qualquer uso real.

## 9. Verificação pós-instalação

1. Acessar `http://frota.local/login` exibe o formulário de login.
2. Login com `pedro.almeida@empresa.com` exibe o painel com o menu de administrador.
3. Login com `joao.silva@email.com` acessa `/meus-pedidos` e recebe 403 em `/frota`.
4. Login com `rafael.souza@email.com` é recusado.
5. `SELECT COUNT(*) FROM usuario;` retorna 8, e `SELECT COUNT(*) FROM alimento;` retorna 9.

## 10. Rotinas de operação

### 10.1 Serviços

| Serviço | Como verificar | Como reiniciar |
|---|---|---|
| PostgreSQL | `Get-Service postgresql*` | `Restart-Service postgresql-x64-16` |
| PHP (php-cgi) | Verifique a janela ou a tarefa agendada | Encerre o processo `php-cgi` e inicie novamente |
| Nginx | `Get-Process nginx` | `cd C:\nginx; .\nginx.exe -s reload` |

# Execução
Execute o comando do Laravel:
```bash
# Logo após este comando,  exclua a tabela session caso ela exista ou foi criada, na pasta /migrations
php artisan migrate


# Depois, rode o servidor
php artisan serve
```