![](https://github.com/epaphrodites/epaphrodites/blob/master/static/img/logo.png)

[![MIT License](https://img.shields.io/badge/License-MIT-green.svg)](https://choosealicense.com/licenses/mit/)

## 👋 About Epaphrodites
Epaphrodites combines simplicity, compatibility with `Python`, support for `multiple databases`, and a commitment to `open-source` principles. It's a promising solution for web programming enthusiasts seeking a flexible, transparent, and evolving development environment. Join this passionate community and explore the opportunities that Epaphrodites can offer for your web projects.

## System needs
What you should know before starting the installation. To create a new application, please first ensure that your computer meets the following requirements.

# 🧩 PHP Required & Optional Extensions (PHP >= 8.2)

## ✅ Required PHP Extensions

- **OpenSSL PHP Extension**  
  _SSL/TLS encryption, secure connections_

- **ZIP PHP Extension**  
  _Compression/decompression of ZIP archives_

- **GD Extension**  
  _Image manipulation: resizing, filters, etc._

- **intl PHP Extension**  
  _Internationalization, locale management, multilingual date formatting_

- **PDO PHP Extension**  
  _Unified interface for database access_

- **JSON PHP Extension**  
  _Encoding/decoding JSON data_

- **XML PHP Extension**  
  _Parsing and manipulating XML files_

- **Mbstring PHP Extension**  
  _Handling multi-byte character strings (UTF-8, etc.)_

- **pdo_sqlite Extension**  
  _PDO driver for SQLite3_

---

## 🔧 Optional Extensions

- **Redis Extension**  
  _Connect to Redis for caching/sessions_

- **pdo_oci Extension**  
  _PDO driver for Oracle Database_

- **pdo_mysql Extension**  
  _PDO driver for MySQL/MariaDB_

- **mongodb Extension**  
  _Connect to MongoDB (NoSQL database)_

- **pdo_pgsql Extension**  
  _PDO driver for PostgreSQL_

- **pdo_sqlsrv Extension**  
  _PDO driver for Microsoft SQL Server_

---

## ⚙️ System Dependencies

- **PHP Dev Tools**  
  _Required to compile PHP extensions → `php-dev` (Linux) / `php` (macOS)_

- **Python3**  
  _For integrating Python scripts if necessary_

---

## 💡 Key Notes

- Required extensions ensure essential features like DB access, encryption, text, and image processing.
- Optional extensions depend on your specific tech stack (e.g., Redis for caching, SQL Server for Microsoft projects).
- System dependencies are crucial for compilation (`phpize`) and interoperability (e.g., Python integration).


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

chmod +x shlomo 