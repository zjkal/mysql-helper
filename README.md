<br/>
<p align="center">
    <img src="https://cdn.0x1.site/logo-mysql-helper.svg" alt="MysqlHelper" width="180" />
    <br/>
    <br/>
    中文 | <a href="https://github.com/zjkal/mysql-helper/blob/main/README_EN.md" target="_blank">English</a>
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

`MysqlHelper` 是一个便捷的`通过PHP导入和导出Mysql数据库表结构和数据`的工具,可以快速实现mysql的数据库的导入和导出. 此类库旨在提供轻量化便捷的mysql导入导出, 开发初衷是web应用安装程序和插件类应用的数据结构导入. 因此没有做数据分批, 大量数据的导入导出并不适合.

## 🧩特性

- 简单易用: 仅依赖`mysqlli`扩展,`开箱即用`
- 灵活操作: 兼容主流框架,使用更方便
- 长期维护: 作者为自由职业者,保证项目的`长期稳定`和`持续更新`

## 🚀安装

通过Composer导入类库

```bash
composer require zjkal/mysql-helper
```

## 🌈使用文档

### 1. 实例化

*方式一: 常规方法*

```php
use zjkal\MysqlHelper;

$mysql = new MysqlHelper('root', 'passwd', 'dbname', '127.0.0.1', '3306', 'utf8mb4', 'wp_');
```

*方式二: 实例化后,通过setConfig方法设置数据库配置*

```php
$mysql = new MysqlHelper();
$mysql->setConfig(['username' => 'root', 'password' => 'passwd', 'database' => 'dbname']);
```

MysqlHelper针对常用的框架做了兼容,可以直接使用框架的数据库配置, 比如`ThinkPHP`框架或`Laravel`框架

```php
$mysql = new MysqlHelper();
$config = config('database.connections.mysql');
$mysql->setConfig($config);
```

### 2. 导出数据

* 如果实例化时, 已经设置了表前缀,导出的表名可以不用带前缀

```php
//导出数据库(包含表结构和数据)
$mysql->exportSqlFile('test.sql');

//仅导出数据库表结构
$mysql->exportSqlFile('test.sql', false);

//导出指定表的结构和数据
$mysql->exportSqlFile('test.sql', true, ['table1', 'table2']);

//导出数据库所有表,并且添加禁用外键检查的SQL语句
$mysql->exportSqlFile('test.sql', true, [], true);
//php8以上可以更简洁的写法:
$mysql->exportSqlFile('test.sql', disableForeignKeyChecks: true);
```

### 3. 导入数据

* 如果需要在导入过程中自定义表前缀, 则sql文件中的表前缀需要使用`__PREFIX__`占位符代替
* 如果实例化时,已经设置了表前缀,则可以不用传入第二个参数

```php
//导入数据库
$mysql->importSqlFile('test.sql');

//导入数据库,并自动替换表前缀
$mysql->importSqlFile('test.sql', 'wp_');
```

## 📃更新日志

> v1.0.6 2024年8月27日
> * 导入时增加了判断是否存在同名表的逻辑

> v1.0.5 2024年6月14日
> * 增加了在导出时可以设置禁用外键检查的参数

> v1.0.4 2024年4月19日
> * 优化了.sql文件中注释的过滤规则

> v1.0.3 2023年12月9日
> * 实例化时如果已经设置了表前缀,导出的表名可以不包含前缀

> v1.0.2 2023年9月23日
> * 增加了数据导出的稳定性

> v1.0.1 2023年9月10日
> * 修复了在Thinkphp框架下端口识别错误的BUG
> * 增加了导入的稳定性

> v1.0.0 2023年9月2日
> * 首次发布

## 🐧QQ频道
<a href="https://pd.qq.com/s/7h2hvcuxs">
  <img src="https://cdn.0x1.site/qrcode-qqpd.png"  alt="QQ频道-世界上最好的编程语言"/>
</a>

## 📖开源协议

MysqlHelper遵循[MIT开源协议](https://github.com/zjkal/mysql-helper/blob/main/LICENSE), 意味着您无需任何授权, 即可免费将MysqlHelper应用到您的项目中
