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
     * @var string 服务器地址
     */
    private $host = '127.0.0.1';
    /**
     * @var int 端口号
     */
    private $port = 3306;
    /**
     * @var string 用户名
     */
    private $username;
    /**
     * @var string 密码
     */
    private $password;
    /**
     * @var string 数据库名
     */
    private $database;
    /**
     * @var string 字符集
     */
    private $charset = 'utf8mb4';

    /**
     * @var string 表前缀
     */
    private $prefix = '';

    /**
     * 构造函数
     * @param string|null $username 用户名
     * @param string|null $password 密码
     * @param string|null $database 数据库名
     * @param string      $host     服务器地址(默认为127.0.0.1)
     * @param string|int  $port     端口号(默认为3306)
     * @param string      $prefix   表前缀(默认为空)
     * @param string      $charset  字符集(默认为utf8mb4)
     */
    public function __construct(string $username = null, string $password = null, string $database = null, string $host = '127.0.0.1', $port = 3306, string $prefix = '', string $charset = 'utf8mb4')
    {
        if (!in_array($charset, ['utf8mb4', 'utf8', 'gbk', 'gb2312'])) {
            throw new InvalidArgumentException('字符集只能是 utf8mb4, utf8, gbk 或 gb2312');
        }
        if (!is_numeric($port)) {
            throw new InvalidArgumentException('端口号必须是数字');
        }

        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
        $this->host     = $host;
        $this->port     = intval($port);
        $this->prefix   = $prefix;
        $this->charset  = $charset;
    }

    /**
     * @param array $config 设置参数
     * @return void
     */
    public function setConfig(array $config = [])
    {
        if (empty($config)) {
            throw new InvalidArgumentException('配置数组不能为空');
        }
        if (empty($config['username']) || empty($config['password']) || empty($config['database'])) {
            throw new InvalidArgumentException('配置数据必须包含用户名,密码和数据库名');
        }
        $this->__construct(
            $config['username'],
            $config['password'],
            $config['database'],
            $config['host'] ?? $config['hostname'] ?? '127.0.0.1',
            $config['host'] ?? $config['hostname'] ?? 3306,
            $config['prefix'] ?? '',
            $config['charset'] ?? 'utf8mb4'
        );
    }

    /**
     * 将.sql文件导入到mysql数据库
     * @param string $sqlFilePath .sql文件路径
     * @param string $prefix      表前缀(优先级高于构造函数中的表前缀,默认为空)
     * @return void
     */
    public function importSqlFile(string $sqlFilePath, string $prefix = '')
    {

        if (!file_exists($sqlFilePath)) {
            throw new InvalidArgumentException('sql文件不存在');
        }

        $prefix = $prefix ?: $this->prefix;

        // 创建MySQL连接
        $conn = new mysqli($this->host, $this->username, $this->password, $this->database, $this->port);

        // 检查连接是否成功
        if ($conn->connect_error) {
            throw new \PDOException("数据库连接失败: " . $conn->connect_error);
        }

        // 设置编码
        $conn->set_charset($this->charset);

        //读取.sql文件内容
        $sqlContent = file_get_contents($sqlFilePath);
        // 分号分隔.sql文件中的多个SQL语句
        $sqlStatements = explode(';', $sqlContent);

        // 执行每个SQL语句
        foreach ($sqlStatements as $sqlStatement) {
            if (trim($sqlStatement) == '' || stripos(trim($sqlStatement), '--') === 0 || stripos(trim($sqlStatement), '/*') === 0) {
                continue;
            }

            $sqlStatement = str_ireplace('__PREFIX__', $prefix, $sqlStatement);
            $sqlStatement = str_ireplace('INSERT INTO ', 'INSERT IGNORE INTO ', $sqlStatement);

            $result = $conn->query($sqlStatement);
            if (!$result) {
                throw new \PDOException("导入失败: " . $conn->error);
            }
        }

        // 关闭连接
        $conn->close();
    }

    /**
     * 将mysql数据库表结构和数据导出为.sql文件
     * @param string $sqlFilePath 导出的.sql文件路径
     * @param bool   $withData    是否导出表数据(默认为true)
     * @param array  $tables      要导出的表名数组(默认为空，即导出所有表)
     * @return void
     */
    public function exportSqlFile(string $sqlFilePath, bool $withData = true, array $tables = [])
    {
        // 创建MySQL连接
        $conn = new mysqli($this->host, $this->username, $this->password, $this->database, $this->port);

        // 检查连接是否成功
        if ($conn->connect_error) {
            throw new \PDOException("数据库连接失败: " . $conn->connect_error);
        }

        // 设置编码
        $conn->set_charset($this->charset);

        // 获取所有表名
        $result     = $conn->query("SHOW TABLES");
        $all_tables = [];

        while ($row = $result->fetch_row()) {
            $all_tables[] = $row[0];
        }

        // 打开输出文件
        $outputFile = fopen($sqlFilePath, 'w');

        // 循环每个表，导出结构和数据
        foreach ($all_tables as $table) {
            if (!empty($tables) && !in_array($table, $tables)) {
                continue;
            }
            // 导出表结构
            fwrite($outputFile, "-- 表结构：$table\n");
            $createTableSQL = $conn->query("SHOW CREATE TABLE $table");
            $createTableRow = $createTableSQL->fetch_row();
            fwrite($outputFile, $createTableRow[1] . ";\n");

            if ($withData) {
                // 导出表数据
                fwrite($outputFile, "-- 表数据：$table\n");
                $result = $conn->query("SELECT * FROM $table");
                while ($row = $result->fetch_assoc()) {
                    $columns = implode("','", array_map([$conn, 'real_escape_string'], array_values($row)));
                    fwrite($outputFile, "INSERT INTO $table VALUES ('$columns');\n");
                }
                if (empty($row)) {
                    fwrite($outputFile, "/* " . $table . "表没有数据 */\n");
                }
            }
        }

        // 关闭文件和连接
        fclose($outputFile);
        $conn->close();
    }
}