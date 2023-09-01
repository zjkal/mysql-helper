<?php
declare (strict_types=1);

namespace zjkal;

use InvalidArgumentException;
use mysqli;

/**
 * 最方便的mysql操作类,可以便捷导入.sql文件和将数据库导出为.sql文件
 * Class MysqlHelper
 * @package zjkal
 */
class MysqlHelper
{
    /**
     * @var string
     */
    private $host;
    /**
     * @var string
     */
    private $port = '3306';
    /**
     * @var string
     */
    private $username;
    /**
     * @var string
     */
    private $password;
    /**
     * @var string
     */
    private $database;
    /**
     * @var string
     */
    private $charset = 'utf8mb4';
    /**
     * @var string
     */
    private $sqlFilePath;
    /**
     * @var string
     */
    private $mysqlBinPath = 'mysql';
    /**
     * @var string
     */
    private $mysqldumpBinPath = 'mysqldump';

    /**
     * MysqlHelper constructor.
     * @param string $host
     * @param string $username
     * @param string $password
     * @param string $database
     * @param string $charset
     * @param string $port
     */
    public function __construct(string $host, string $username, string $password, string $database, string $charset = 'utf8mb4', string $port = '3306')
    {
        $this->host     = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;

        if (!in_array($charset, ['utf8mb4', 'utf8', 'gbk', 'gb2312'])) {
            throw new InvalidArgumentException('charset must be utf8mb4, utf8, gbk or gb2312');
        }
        $this->charset = $charset;

        if (!is_numeric($port)) {
            throw new InvalidArgumentException('port must be numeric');
        }
        $this->port = $port;
    }

    //导入.sql文件
    public function importSqlFile(string $sqlFilePath)
    {
        $servername = "127.0.0.1";
        $username   = "root";
        $password   = "d128567ba27ff134";
        $dbname     = "buildadmin";

        // 创建MySQL连接
        $conn = new mysqli($servername, $username, $password, $dbname);

        // 检查连接是否成功
        if ($conn->connect_error) {
            die("连接失败: " . $conn->connect_error);
        }

        // 读取.sql文件内容
        $sqlFile    = 'path/to/your/sqlfile.sql';
        $sqlContent = file_get_contents($sqlFile);

        // 分号分隔.sql文件中的多个SQL语句
        $sqlStatements = explode(';', $sqlContent);

        // 执行每个SQL语句
        foreach ($sqlStatements as $sqlStatement) {
            if (trim($sqlStatement) != '') {
                $result = $conn->query($sqlStatement);
                if (!$result) {
                    echo "导入失败: " . $conn->error;
                }
            }
        }

        // 关闭连接
        $conn->close();
    }

    //将mysql数据库导出为.sql文件
    public function exportSqlFile(string $sqlFilePath)
    {
        $servername = "127.0.0.1";
        $username   = "root";
        $password   = "d128567ba27ff134";
        $dbname     = "buildadmin";

        // 创建MySQL连接
        $conn = new mysqli($servername, $username, $password, $dbname);

        // 检查连接是否成功
        if ($conn->connect_error) {
            die("连接失败: " . $conn->connect_error);
        }

        // 执行导出SQL语句
        $tableName  = "ba_test_build";
        $outputFile = 'output.sql';

        $sql    = "SELECT * INTO OUTFILE '$outputFile'
        FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"'
        LINES TERMINATED BY '\n'
        FROM $tableName";
        $result = $conn->query($sql);

        if (!$result) {
            echo "导出失败: " . $conn->error;
        }

        // 关闭连接
        $conn->close();
    }
}