<?php

namespace zjkal\tests;

use PHPUnit\Framework\TestCase;
use zjkal\MysqlHelper;

class MysqlHelperTest extends TestCase
{
    private $config = ['username' => 'root', 'password' => 'root', 'database' => 'testdatabase'];

    public function testImportSqlFile()
    {
        $this->expectOutputString('导入成功');

        try {
            $mysql = new MysqlHelper();
            $mysql->setConfig($this->config);
            $mysql->importSqlFile('import.sql', 'test_');
            print '导入成功';
        } catch (\Exception $e) {
            $this->fail('导入失败:' . $e->getMessage());
        }
    }

    public function testExportSqlFile()
    {
        $this->expectOutputString('导出成功');

        try {
            $mysql = new MysqlHelper();
            $mysql->setConfig($this->config);
            $mysql->exportSqlFile('export.sql');
            print '导出成功';
        } catch (\Exception $e) {
            $this->fail('导出失败:' . $e->getMessage());
        }
    }
}
