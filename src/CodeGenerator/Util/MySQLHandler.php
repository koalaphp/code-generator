<?php
/**
 * Created by PhpStorm.
 * User: laiconglin
 * Date: 2018/10/8
 * Time: 00:08
 */

namespace Koala\CodeGenerator\Util;

/**
 * 用于处理MySQL连接相关的功能，主要获取表的信息，如字段和注释信息等等
 *
 * Class MySQLHandler
 * @package Koala\CodeGenerator
 */
class MySQLHandler
{
	public function __construct()
	{
	}

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

	/**
	 *
	 *
	 *
	 * @param $dbName
	 * @param $tableName
	 * @return array
	 */
	public function getTableInfo($dbName, $tableName) {
		$curStatement = $this->curPdo->prepare("show full columns from " . $tableName);
		$curStatement->execute();
		/**
		 * 返回结果：
		 * {
		 * 		// ....
		 *     {
		 * 			"Field": "name",
		 * 			"Type": "varchar(32)",
		 * 			"Collation": "utf8mb4_general_ci",
		 * 			"Null": "NO",
		 * 			"Key": "UNI",
		 * 			"Default": "",
		 * 			"Extra": "",
		 * 			"Privileges": "select,insert,update,references",
		 * 			"Comment": "用户昵称"
		 * 		},
		 * }
		 */
		$fullFields = $curStatement->fetchAll(\PDO::FETCH_ASSOC);
		$tableComment = $this->getTableComment($dbName, $tableName);
		$tableInfo = [
			"db" => $dbName,
			"name" => $tableName,
			"nameComment" => $tableComment,
			"primaryKey" => "",
			"fields" => [],
		];
		foreach ($fullFields as $tmpFields) {
			if (strtolower($tmpFields["Key"]) == "pri") {
				$tableInfo["primaryKey"] = $tmpFields["Field"];
			}
			$tableInfo["fields"][] = [
				"name" => $tmpFields["Field"],
				"type" => $tmpFields["Type"],
				"comment" => $tmpFields["Comment"],
			];
		}

		return $tableInfo;
	}

	/**
	 * 获取表的注释
	 *
	 * @param $dbName
	 * @param $tableName
	 * @return string
	 */
	public function getTableComment($dbName, $tableName) {
		$curStatement = $this->curPdo->prepare("
SELECT table_comment 
FROM INFORMATION_SCHEMA.TABLES 
WHERE table_schema = :dbName AND 
table_name = :tableName");
		$curStatement->bindValue(":dbName", $dbName, \PDO::PARAM_STR);
		$curStatement->bindValue(":tableName", $tableName, \PDO::PARAM_STR);
		$curStatement->execute();
		$tableComment = $curStatement->fetch(\PDO::FETCH_ASSOC);
		return isset($tableComment['table_comment']) ? $tableComment['table_comment'] : '';
	}
}