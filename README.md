![](https://github.com/epaphrodites/epaphrodites/blob/master/static/img/logo.png)

[![MIT License](https://img.shields.io/badge/License-MIT-green.svg)](https://choosealicense.com/licenses/mit/)

## 👋 About Epaphrodites
Epaphrodites combines simplicity, compatibility with `Python`, support for `multiple databases`, and a commitment to `open-source` principles. It's a promising solution for web programming enthusiasts seeking a flexible, transparent, and evolving development environment. Join this passionate community and explore the opportunities that Epaphrodites can offer for your web projects.

## System needs
What you should know before starting the installation. To create a new application, please first ensure that your computer meets the following requirements.

- PHP >= 8.2
- OpenSSL PHP Extension
- ZIP PHP Extension
- gd Extension
- intl PHP Extension
- PDO PHP Extension
- JSON PHP Extension
- XML PHP Extension
- Mbstring PHP Extension
- pdo_sqlite Extension for sqlite3
- Redis Extension (if you use Redis)
- pdo_oci Extension (if you use oracle)
- pdo_mysql Extension (if you use Mysql)
- mongodb Extension (if you use MongoDB)
- pdo_pgsql Extension (if you use PostgreSQL)
- pdo_sqlsrv Extension (if you use sqlServer)
- Python3 installation (If you prefer to include elements of code written in Python)

## 🚀 Installation

```bash
composer create-project epaphrodites/epaphrodites your-project-name
```

⚠️ Caution : If, after executing the previous command, the installation does not proceed as expected or if you encounter any issues, try running the following command :

```bash
cd your-project-name
php epaphrodites install component
```

⚠️ Recommendation : If you have already installed `MongoDB` on your machine and wish to incorporate its usage into your project, please execute the following command:

```bash
composer require mongodb/mongodb
```

### Continue the installation :
Execute the following command to update all dependencies :

```bash
composer update
```

To execute "dump-autoload," run the command :
```bash
composer dump-autoload
```

## ⚙️ First configuration

1. Open this file (SetDirectory.php)
```bash  
    bin\config\SetDirectory.php
```

2. Set database accpeted : ('mysql/oracle/pgsql/sqlserver/sqlite/mongodb/redis')
```bash  
    define('_FIRST_DRIVER_', 'sqlite');
```

3. Open this file (Config.ini)
```bash  
    bin\config\config.ini
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

    // First DB DRIVER - accepted : mysql/oracle/pgsql/sqlserver/sqlite/mongodb/redis
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

#### Install python component
- Note: This command is intended for users who have previously installed Python on their machine. It simplifies the installation of specific Python libraries essential for the optimal functioning of the Epaphrodites framework. Furthermore, you have the freedom, at your level, to integrate other libraries according to the requirements of your projects :

```bash  
php heredia pip:component
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

### Authors

- [Y'srael Aime N'DRI (Lead) ](https://github.com/ysrael-aime-ndri)

### Contributing

Contributions are always welcome!!
