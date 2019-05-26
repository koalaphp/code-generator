<?php
/**
 * Created by PhpStorm.
 * User: laiconglin
 * Date: 2018/10/7
 * Time: 23:31
 */

namespace Koala\CodeGenerator;

use Koala\CodeGenerator\FileWriter\FileWriter;
use Koala\CodeGenerator\Template\DaoTpl;
use Koala\CodeGenerator\Util\MySQLHandler;

/**
 * 基于Koala Database的定制化Dao代码生成器，如有其他定制化需求可继承本基类，进行扩展。
 *
 * Class DaoGenerator
 * @package Koala\CodeGenerator
 */
class DaoGenerator extends FileWriter
{
	protected $fullParentDir = "./Dao/%dbNamespace%"; // 保存代码的目录的路径
	protected $author = "laiconglin3@126.com"; // 作者
	protected $date = "1970-01-01"; // 代码生成的日期
	protected $template; // 模板内容

	/**
	 * 对模板进行替换的规则
	 * @var array
	 */
	protected $replacePattern = [
		"%dbNamespace%" => "",
		"%tableDaoName%" => "",
		"%tableModelName%" => "",
		"%tableComment%" => "",
		"%dbName%" => "",
		"%tableName%" => "",
		"%primaryKey%" => "",
		"%fieldList%" => "",
		"%author%" => "",
		"%date%" => "",
	];

	/**
	 * @var \PDO | null
	 */
	protected $curPdo = null;
	/**
	 * 设置PDO连接对象
	 * @param $pdo
	 */
	public function setPdo($pdo) {
		$this->curPdo = $pdo;
	}

	protected $dbName = ""; // 数据库的名字

	protected $tableName = ""; // 数据库的表的名字

	protected $tableInfo = []; // 查询数据库得到的表的结构的信息
	public function __construct()
	{
		// 更新时间
		$this->date = date("Y-m-d H:i:s");
	}

	/**
	 * 根据数据库名和表名，生成Dao文件的代码
	 *
	 * @param $dbName
	 * @param $tableName
	 * @return bool
	 */
	public function genDaoCodeByDbNameAndTableName($dbName, $tableName) {
		$this->initDbNameAndTableName($dbName, $tableName);
		return $this->writeContentToFile();
	}

	public function initDbNameAndTableName($dbName, $tableName) {
		$this->dbName = $dbName;
		$this->tableName = $tableName;
		$this->tableInfo = $this->getCurTableInfo();
	}

	/**
	 * 获取配置的模板（推荐使用heredoc来配置）
	 * @return string
	 */
	public function genTemplate()
	{
		$this->template = DaoTpl::$template;
		return $this->template;
	}

	/**
	 * 生成替换规则
	 * @return array
	 */
	public function genReplaceRule()
	{
		$tableInfo = $this->tableInfo;

		$tmpTableName = explode("_", $tableInfo["name"]);
		$camelTableName = implode("", array_map("ucfirst", $tmpTableName));
		$tmpDaoName = $camelTableName . "Dao";
		$tmpModelName = $camelTableName . "Model";
		$tmpDBFolder = ucfirst($tableInfo["db"]);

		// 只需要输出数组就行了
		$fieldListStr = "    protected \$fieldList = [" . PHP_EOL;
		if (isset($tableInfo['fields']) && is_array($tableInfo['fields'])) {
			foreach ($tableInfo['fields'] as $tmpFieldInfo) {
				$fieldListStr .= sprintf("        '%s' => '%s', // %s", $tmpFieldInfo['name'], $tmpFieldInfo['type'], $tmpFieldInfo['comment']) . PHP_EOL;
			}
		}
		$fieldListStr .= "    ];";

		/**
		 * 生成替换规则
		 */
		$this->replacePattern = [
			"%dbNamespace%" => $tmpDBFolder,
			"%tableDaoName%" => $tmpDaoName,
			"%tableModelName%" => $tmpModelName,
			"%tableComment%" => $tableInfo['nameComment'],
			"%dbName%" => $tableInfo["db"],
			"%tableName%" => $tableInfo["name"],
			"%primaryKey%" => $tableInfo["primaryKey"],
			"%fieldList%" => $fieldListStr,
			"%author%" => $this->author,
			"%date%" => date("Y-m-d H:i:s"),
		];

		// 打印日志信息
		$replacePatternStr = PHP_EOL . json_encode($this->replacePattern, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
		$this->printDebugMessage(__METHOD__ . " replacePattern: $replacePatternStr" . PHP_EOL);

		return $this->replacePattern;
	}

	/**
	 * 获取需要将代码保存到的绝对路径（全路径）
	 * @return string
	 */
	public function getFullSaveFilePath()
	{
		$tableInfo = $this->tableInfo;
		$tmpTableName = explode("_", $tableInfo["name"]);
		$camelTableName = implode("", array_map("ucfirst", $tmpTableName));
		$tmpDaoName = $camelTableName . "Dao";
		$tmpDBFolder = ucfirst($tableInfo["db"]);
		$outputParentDir = rtrim(str_replace("%dbNamespace%", $tmpDBFolder, $this->fullParentDir), DIRECTORY_SEPARATOR);
		$outputDaoFile = $outputParentDir . DIRECTORY_SEPARATOR . $tmpDaoName . ".php";

		// 打印日志信息
		$this->printDebugMessage(__METHOD__ . " outputDaoFile: $outputDaoFile" . PHP_EOL);

		return $outputDaoFile;
	}

	/**
	 * 获取数据库表的详细信息
	 * @return array
	 */
	protected function getCurTableInfo() {
		$mysqlHandler = new MySQLHandler();
		$mysqlHandler->setPdo($this->curPdo);
		$tableInfo = $mysqlHandler->getTableInfo($this->dbName, $this->tableName);

		// 打印日志信息
		$tableInfoStr = PHP_EOL . json_encode($tableInfo, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
		$this->printDebugMessage(__METHOD__ . " tableInfo: $tableInfoStr" . PHP_EOL);

		return $tableInfo;
	}

	/**
	 * @return string
	 */
	public function getFullParentDir()
	{
		return $this->fullParentDir;
	}

	/**
	 * @param string $fullParentDir
	 */
	public function setFullParentDir($fullParentDir)
	{
		$this->fullParentDir = $fullParentDir;
	}

	/**
	 * @return string
	 */
	public function getAuthor()
	{
		return $this->author;
	}

	/**
	 * @param string $author
	 */
	public function setAuthor($author)
	{
		$this->author = $author;
	}

	/**
	 * @return false|string
	 */
	public function getDate()
	{
		return $this->date;
	}

	/**
	 * @param false|string $date
	 */
	public function setDate($date)
	{
		$this->date = $date;
	}
}
