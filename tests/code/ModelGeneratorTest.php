<?php
/**
 * Created by PhpStorm.
 * User: laiconglin
 * Date: 2018/10/14
 * Time: 16:33
 */

use Koala\CodeGenerator\ModelGenerator;

class ModelGeneratorTest extends PHPUnit_Framework_TestCase
{
	public function testGenDao() {
		require "CustomModelTpl.php";
		\Koala\CodeGenerator\Template\ModelTpl::$template = CustomModelTpl::$template;

		$userModelGenerator = new \Koala\CodeGenerator\ModelGenerator();
		$userModelGenerator->setPdo($this->getPdo()); // $myMasterPDO 是连接到数据库的 PDO对象
		$userModelGenerator->setFullParentDir(OUTPUT_PATH);
		$isSucc = $userModelGenerator->genModelCodeByDbNameAndTableName("test", "user"); // test是数据库名字，user是表名
		$isSucc = $userModelGenerator->genModelCodeByDbNameAndTableName("test", "test_user"); // test是数据库名字，test_user是表名
		$this->assertTrue($isSucc);
	}

	/**
	 * 返回pdo
	 * @return PDO
	 */
	protected function getPdo() {
		global $databaseConfig;
		$attribute = [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION];
		$singleConfig = $databaseConfig;

		$curDsn = 'mysql:host=' . $singleConfig['host'] . ';port=' . $singleConfig['port'] . ';dbname=' . $singleConfig['name'] . ';charset=' . $singleConfig['charset'];
		$pdoHandler = new \PDO(
			$curDsn,
			$singleConfig['user'],
			$singleConfig['pass'],
			$attribute
		);
		return $pdoHandler;
	}
}
