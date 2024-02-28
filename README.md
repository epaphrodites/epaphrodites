![](https://github.com/epaphrodites/epaphrodites/blob/master/static/img/logo.png)

[![MIT License](https://img.shields.io/badge/License-MIT-green.svg)](https://choosealicense.com/licenses/mit/)

## 👋 About Epaphrodites
Epaphrodites combines simplicity, compatibility with `Python`, support for `multiple databases`, and a commitment to `open-source` principles. It's a promising solution for web programming enthusiasts seeking a flexible, transparent, and evolving development environment. Join this passionate community and explore the opportunities that Epaphrodites can offer for your web projects.

## System needs
What you should know before starting the installation. To create a new application, please first ensure that your computer meets the following requirements.

- PHP >= 8.1
- OpenSSL PHP Extension
- PDO PHP Extension
- pgsql Extension (if you use PostgreSQL)
- MongoDB Extension (if you use MongoDB)
- Redis Extension (if you use Redis)
- Mbstring PHP Extension
- XML PHP Extension
- ZIP PHP Extension
- JSON PHP Extension
- Python installation (If you prefer to include elements of code written in Python)

## 🚀 Installation

```bash
composer create-project --prefer-dist epaphrodites/epaphrodites your-project-name
```

⚠️ Recommendation : If you have already installed `MongoDB` on your machine and wish to incorporate its usage into your project, please execute the following command:

```bash
composer require mongodb/mongodb
```

Continue the installation by doing :
```bash
composer update
```

```bash
composer update dump-autoload
```

## ⚙️ First configuration

1. Open this file (SetDirectory.php)
```bash  
    bin\epaphrodites\define\config\SetDirectory.php
```

2. Set database accpeted : ('mysql/postgre/sqlserver/sqlite/mongo/redis')
```bash  
    define('_FIRST_DRIVER_', 'sqlite');
```

3. Open this file (Config.ini)
```bash  
    bin\database\config\ini\config.ini
```

4. Choose your first configuration
```bash  
    // First DB username
    1DB_USER =

    // First DB password
    1DB_PASSWORD =

    // First DB port
    1DB_PORT =

    // First DB name
    1DB_DATABASE = "epaphroditesdb.sqlite"

    // First DB SOCKET support
    1DB_SOCKET = false

    // First DB socket
    1DB_SOCKET_PATH = ""

    // First DB host
    1DB_HOST = "127.0.0.1" 

    // First DB DRIVER (accepted : mysql/pgsql/sqlserver/sqlite/mongodb/redis)
    1DB_DIVER = "sqlite"

```

### 💾 Create database

> Run this command to create your database
```bash  
php heredia create:db epaphroditesdb
```

### 💾 update database driver

```bash  
php heredia update:driver
```

### 🔥 Run your App
- Note: For SQL databases, you must create your database before running your application. In the case of MongoDB, the system will generate your database based on your configuration.

> Run server
```bash  
cd your_project
php heredia run:server --port=8000
```

> Authentification access
```bash  
Login : admin
Password : admin
```

😎 Enjoy yourself

### 📗 Documentation

- [Documentation](https://epaphrodite.org/)
- [EpaphroditesBot](https://epaphrodite.org/views/chat-epaphrodites/)

### Authors

- [Y'srael Aime N'DRI (Lead) ](https://github.com/ysrael-aime-ndri)

### Contributing

Contributions are always welcome!!
