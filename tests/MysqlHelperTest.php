<?php

namespace zjkal\tests;

use PHPUnit\Framework\TestCase;
use zjkal\MysqlHelper;

class MysqlHelperTest extends TestCase
{
    private $config = ['username' => 'root', 'password' => 'root', 'database' => 'testdatabase'];

    public function testExportSqlFile()
    {
        $mysql = new MysqlHelper();
        $mysql->setConfig($this->config);
        $mysql->exportSqlFile('test.sql');
    }

    public function testImportSqlFile()
    {
        $mysql = new MysqlHelper();
        $mysql->setConfig($this->config);
        $mysql->importSqlFile('test.sql', 'test_');
    }
}
