<br/>
<p align="center">
    <img src="https://github.com/zjkal/mysql-helper/raw/main/logo.svg" alt="MysqlHelper" width="180" />
    <br/>
    <br/>
    <a href="https://github.com/zjkal/mysql-helper/blob/main/README.md" target="_blank">中文文档</a> | English Document
</p>
<p align="center">
    <a href="https://github.com/zjkal/mysql-helper/blob/main/LICENSE" target="_blank">
        <img src="https://poser.pugx.org/zjkal/mysql-helper/license" alt="License">
    </a>
    <a href="https://github.com/zjkal/mysql-helper" target="_blank">
        <img src="https://poser.pugx.org/zjkal/mysql-helper/require/php" alt="PHP Version Require">
    </a>
    <a href="https://github.com/zjkal/mysql-helper" target="_blank">
        <img src="https://poser.pugx.org/zjkal/mysql-helper/v" alt="Latest Stable Version">
    </a>
    <a href="https://packagist.org/packages/zjkal/mysql-helper" target="_blank">
        <img src="https://poser.pugx.org/zjkal/mysql-helper/downloads" alt="Total Downloads">
    </a>
    <a href="https://github.com/zjkal/mysql-helper" target="_blank">
        <img src="https://img.shields.io/github/actions/workflow/status/zjkal/mysql-helper/.github/workflows/php.yml?branch=main" alt="GitHub Workflow Status">
    </a>
</p>

`MysqlHelper` is a convenient tool to `import and export Mysql database table structure and data with PHP`, which can quickly realize the import and export of mysql database.

## 🧩Features

- Easy to use: only depends on `mysqlli` extension, `out of the box`
- Flexible operation: Compatible with mainstream frameworks, more convenient to use
- Long-term maintenance: The author is a freelancer committed to ensuring the project's `long-term stability` and `continuous updates`.

## 🚀Installation

Install via Composer.

```bash
composer require zjkal/mysql-helper
```

## 🌈Usage

### 1. instantiate

*Method 1: Conventional method*

```php
use zjkal\MysqlHelper;

$mysql = new MysqlHelper('root', 'root', 'testdatabase', '127.0.0.1', '3306', 'utf8mb4', 'wp_');
```

*Method 2: After instantiation, set the database configuration through the setConfig method*

```php
$mysql = new MysqlHelper();
$mysql->setConfig(['username' => 'root', 'password' => 'root', 'database' => 'testdatabase']);
```

MysqlHelper is compatible with commonly used frameworks, you can directly use the database configuration of the framework, such as `ThinkPHP` framework or `Laravel` framework

```php
$mysql = new MysqlHelper();
$config = config('database.connections.mysql');
$mysql->setConfig($config);
```

### 2. export

```php
//Export database (including table structure and data)
$mysql->exportSqlFile('test.sql');

//Export only the database table structure
$mysql->exportSqlFile('test.sql', false);

//Export the structure and data of the specified table
$mysql->exportSqlFile('test.sql', true, ['table1', 'table2']);
```

### 3. Import

* The table prefix in the sql file needs to be replaced by `__PREFIX__` placeholder
* If the database prefix has been set during instantiation, you do not need to pass in the second parameter

```php
import database
$mysql->importSqlFile('test.sql');

//Import the database and automatically replace the table prefix
$mysql->importSqlFile('test.sql', 'wp_');
```

## 📃Changelog

> v1.0.2 Sep 23, 2023
> * Increased export stability

> v1.0.1 Sep 10, 2023
> * Fixed the bug of incorrect port recognition under the Thinkphp framework
> * Increased import stability

> v1.0.0 Sep 2, 2023
> * Initial Release

## 💖sponsor me

Your recognition is the motivation to move on, if you think `MysqlHelper` is helpful to you, please [🙏support me](https://zjkal.cn/sponsor), thank you!

## 📖License

The MIT License (MIT). Please see [License File](https://github.com/zjkal/mysql-helper/blob/main/LICENSE) for more information.
