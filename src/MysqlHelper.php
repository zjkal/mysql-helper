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
        $this->host = $host;
        $this->port = intval($port);
        $this->prefix = $prefix;
        $this->charset = $charset;
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
            $config['port'] ?? $config['hostport'] ?? 3306,
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
            throw new \mysqli_sql_exception("数据库连接失败: " . $conn->connect_error);
        }

        // 设置编码
        $conn->set_charset($this->charset);

        //读取.sql文件内容
        $sqlContent = file($sqlFilePath);

        $tmp = '';
        // 执行每个SQL语句
        foreach ($sqlContent as $line) {
            if (trim($line) == '' || stripos(trim($line), '--') === 0 || stripos(trim($line), '/*') === 0) {
                continue;
            }

            $tmp .= $line;
            if (substr(trim($line), -1) === ';') {
                $tmp = str_ireplace('__PREFIX__', $prefix, $tmp);
                $tmp = str_ireplace('INSERT INTO ', 'INSERT IGNORE INTO ', $tmp);
                $result = $conn->query($tmp);
                if (!$result) {
                    throw new \mysqli_sql_exception("导入失败: " . $conn->error);
                }
                $tmp = '';
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
            throw new \mysqli_sql_exception("数据库连接失败: " . $conn->connect_error);
        }

        // 设置编码
        $conn->set_charset($this->charset);

        // 获取所有表名
        $result = $conn->query("SHOW TABLES");
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
            //如果设置了表前缀,且传入的表名不包含表前缀,则补上
            if (!empty($this->prefix) && strpos($table, $this->prefix) !== 0) {
                $table = $this->prefix . $table;
            }

            // 导出表结构
            fwrite($outputFile, "-- 表结构：$table\n");
            $createTableSQL = $conn->query("SHOW CREATE TABLE $table");
            $createTableRow = $createTableSQL->fetch_row();
            fwrite($outputFile, $createTableRow[1] . ";\n");

            if ($withData) {
                // 导出表数据
                $result = $conn->query("SELECT * FROM $table");
                if (!$result) {
                    fwrite($outputFile, "/* 查询失败或" . $table . "表不存在 */\n");
                } else if ($result->num_rows == 0) {
                    fwrite($outputFile, "/* " . $table . "表没有数据 */\n");
                } else {
                    fwrite($outputFile, "-- 表数据：$table\n");
                    while ($row = $result->fetch_assoc()) {
                        $escapedValues = array_map(function ($value) use ($conn) {
                            return $conn->escape_string(strval($value));
                        }, $row);
                        $columns = implode("','", $escapedValues);
                        fwrite($outputFile, "INSERT INTO `$table` VALUES ('$columns');\n");
                    }
                    //释放结果集
                    $result->free();
                }
            }
        }

        // 关闭文件和连接
        fclose($outputFile);
        $conn->close();
    }
}