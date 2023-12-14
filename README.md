
![](https://github.com/epaphrodites/epaphrodites/blob/master/static/img/logo.png)

[![MIT License](https://img.shields.io/badge/License-MIT-green.svg)](https://choosealicense.com/licenses/mit/)

## 👋 About Epaphrodite
Epaphrodites combines simplicity, compatibility with `Python`, support for `multiple databases`, and a commitment to `open-source` principles. It's a promising solution for web programming enthusiasts seeking a flexible, transparent, and evolving development environment. Join this passionate community and explore the opportunities that Epaphrodite-Framework can offer for your web projects.

## 🚀 Installation

```bash
composer create-project --prefer-dist epaphrodites/epaphrodites project-name
```

⚠️ Recommendation : If `MongoDB` is not installed on your machine, we recommend removing the `mongodb/mongodb` line from your `composer.json` file. This will prevent errors during the framework installation.

Continue the installation by doing :
```bash
composer update
```

```bash
composer dump-autoload
```

## ⚙️ First configuration

1. Open this file (SetDirectory.php)
```bash  
    bin\epaphrodite\define\config\SetDirectory.php
```

2. Choose your first database connexion type (accepted : sql or nosql )
```bash  
    define('_DATABASE_', 'sql');
```

3. Open this file (Config.ini)
```bash  
    bin\database\config\ini\config.ini
```

4. Choose your first configuration
```bash  
    // First DB username
    1DB_USER = root

    // First DB password
    1DB_PASSWORD = root

    // First DB port
    1DB_PORT = 8889

    // First DB name
    1DB_DATABASE = epaphroditedb

    // First DB SOCKET support
    1DB_SOCKET = false

    // First DB socket
    1DB_SOCKET_PATH = "unix_socket=/Applications/MAMP/tmp/mysql/mysql.sock"

    // First DB host
    1DB_HOST = "127.0.0.1"

    // First DB DRIVER (accepted : mysql/pgsql/sqlite/mongodb)
    1DB_DIVER = "mysql"

```

### 💾 Create database

> Run this command to create your database
```bash  
php heredia create:db epaphroditedb
```

### 🔥 Run your App
- Note: For SQL databases, you must create your database before running your application. In the case of MongoDB, the system will generate your database based on your configuration.

> Run server
```bash  
cd your_project
php heredia run:server --port=8000 --host=127.0.0.1
```

> Authentification access
```bash  
Login : admin
Password : admin
```

😎 Enjoy yourself

### Authors

- [Y'srael Aime N'dri (Lead) ](https://github.com/ysrael-aime-ndri)

### Contributing

Contributions are always welcome!