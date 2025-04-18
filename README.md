![Epaphrodites Logo](https://github.com/epaphrodites/epaphrodites/blob/master/static/img/logo.png)

[![MIT License](https://img.shields.io/badge/License-MIT-green.svg)](https://choosealicense.com/licenses/mit/)

---

## 👋 About Epaphrodites

**Epaphrodites** is a modern, modular, and open-source framework that combines the power of **PHP**, **Python**, and **C**.

It emphasizes:
- ✨ Simplicity
- 🛠️ Flexibility
- 🌐 Multi-database support
- 💡 Developer-friendly tools
- 🧠 Native integration of C for performance-critical components

Designed for developers who want full control of their stack, Epaphrodites bridges interpreted and compiled logic to deliver speed without sacrificing convenience.

---

## 🧩 PHP Required & Optional Extensions (PHP >= 8.2)

### ✅ Required PHP Extensions

- **OpenSSL** — SSL/TLS encryption, secure connections  
- **ZIP** — Compression/decompression of ZIP archives  
- **GD** — Image manipulation: resizing, filters, etc.  
- **intl** — Internationalization, locale/date formatting  
- **PDO** — Unified database interface  
- **JSON** — JSON data encoding/decoding  
- **XML** — Parsing and manipulating XML files  
- **Mbstring** — Multi-byte string support (e.g. UTF-8)  
- **pdo_sqlite** — PDO driver for SQLite3

---

### 🔧 Optional Extensions

- **Redis** — Caching & sessions with Redis  
- **pdo_oci** — Oracle DB support  
- **pdo_mysql** — MySQL/MariaDB support  
- **mongodb** — MongoDB (NoSQL) integration  
- **pdo_pgsql** — PostgreSQL support  
- **pdo_sqlsrv** — Microsoft SQL Server support

---

## ⚙️ System Dependencies

- **PHP Dev Tools** — Required to compile extensions (`php-dev` for Linux / `php` for macOS)  
- **Python3** — Needed for Python integration  
- **C Compiler** — Recommended for building native components and optimizing extensions

---

## 💡 Key Notes

- Required extensions provide core features (DB, encryption, image/text processing).
- Optional ones depend on your tech stack.
- System dependencies are critical for compilation (`phpize`) and C/Python interoperability.

---

## 🚀 Installation

```bash
composer create-project epaphrodites/epaphrodites your-project-name
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

#### 🐍 Install python component
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

### 👥 Authors

- [Y'srael Aime N'DRI (Lead) ](https://github.com/ysrael-aime-ndri)

### 🤝 Contributing

Contributions are welcome! Fork the repo, make changes, and submit a pull request.

Don't forget to set executable permissions if you needed to use C in your project :

```bash
chmod +x shlomo 
```