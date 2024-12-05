<br/>
<p align="center">
    <img src="https://cdn.0x1.site/logo-mysql-helper.svg" alt="MysqlHelper" width="180" />
    <br/>
    <br/>
    <a href="https://github.com/zjkal/mysql-helper/blob/main/README.md" target="_blank">中文</a> | English
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

'MysqlHelper' is a convenient tool for 'importing and exporting Mysql database table structure and data via PHP', which can quickly import and export mysql databases. These libraries are designed to provide lightweight and convenient MySQL import and export, and are developed to import data structures from web application installers and plug-in applications. Therefore, there is no data batching, and the import and export of a large amount of data is not suitable.

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

$mysql = new MysqlHelper('root', 'passwd', 'dbname', '127.0.0.1', '3306', 'utf8mb4', 'wp_');
```

*Method 2: After instantiation, set the database configuration through the setConfig method*

```php
$mysql = new MysqlHelper();
$mysql->setConfig(['username' => 'root', 'password' => 'passwd', 'database' => 'dbname']);
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

//Export all tables in the database and add an SQL statement that disables foreign key checking
$mysql->exportSqlFile('test.sql', true, [], true);
//PHP8 or above can be written more concisely:
$mysql->exportSqlFile('test.sql', disableForeignKeyChecks: true);
```

### 3. Import

* If you need to customize the table prefix during the import process, the table prefix in the SQL file needs to be replaced with a '__PREFIX__' placeholder
* If the database prefix has been set during instantiation, you do not need to pass in the second parameter

```php
import database
$mysql->importSqlFile('test.sql');

//Import the database and automatically replace the table prefix
$mysql->importSqlFile('test.sql', 'wp_');

//Import the database, do not replace the prefix, and delete the table first if it already exists
$mysql->importSqlFile('test.sql', '', true);
//PHP8 or above can be written more concisely:
$mysql->importSqlFile('test.sql', dropTableIfExists: true);
```

## 📃Changelog

> v1.0.9 December 5, 2024
> * Added parameters for data import to set whether to delete existing tables

> v1.0.8 November 23, 2024
> * Fixed the bug that the ignore statement was repeatedly replaced when importing a table

> v1.0.7 Oct 4, 2024
> * Improved the stability of imports: filter blank rows

> v1.0.6 Aug 27, 2024
> * Added logic to determine whether a table with the same name exists

> v1.0.5 June 14, 2024
> * Added the ability to set a parameter to disable foreign key checking during export

<details><summary>点击查看更多</summary>

> v1.0.4 Apr 19, 2024
> * Optimized the filtering rules for comments in .sql files

> v1.0.3 Dec 9, 2023
> * If a table prefix is set during instantiation, the exported table name can not contain the prefix

> v1.0.2 Sep 23, 2023
> * Increased export stability

> v1.0.1 Sep 10, 2023
> * Fixed the bug of incorrect port recognition under the Thinkphp framework
> * Increased import stability

> v1.0.0 Sep 2, 2023
> * Initial Release
</details>

## 😎Contributors

<!-- readme: contributors -start -->
<table>
	<tbody>
		<tr>
            <td align="center">
                <a href="https://github.com/zjkal">
                    <img src="https://avatars.githubusercontent.com/u/15082976?v=4" width="100;" alt="zjkal"/>
                    <br />
                    <sub><b>zjkal</b></sub>
                </a>
            </td>
            <td align="center">
                <a href="https://github.com/fedsin">
                    <img src="https://avatars.githubusercontent.com/u/179591768?v=4" width="100;" alt="fedsin"/>
                    <br />
                    <sub><b>fedsin</b></sub>
                </a>
            </td>
		</tr>
	<tbody>
</table>
<!-- readme: contributors -end -->

## 📖License

The MIT License (MIT). Please see [License File](https://github.com/zjkal/mysql-helper/blob/main/LICENSE) for more information.
