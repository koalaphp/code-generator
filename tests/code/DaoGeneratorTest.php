<?php
/**
 * Created by PhpStorm.
 * User: laiconglin
 * Date: 2018/10/14
 * Time: 15:34
 */
use Koala\CodeGenerator\DaoGenerator;

class DaoGeneratorTest extends PHPUnit_Framework_TestCase
{
	public function testGenDao() {
		require "CustomDaoTpl.php";
		// 修改模板
		\Koala\CodeGenerator\Template\DaoTpl::$template = CustomDaoTpl::$template;

		\Koala\CodeGenerator\Template\DaoTpl::$template = CustomDaoTpl::$template;
		$userDaoGenerator = new \Koala\CodeGenerator\DaoGenerator();
		$userDaoGenerator->setPdo($this->getPdo()); // $myMasterPDO 是连接到数据库的 PDO对象
		$userDaoGenerator->setFullParentDir(OUTPUT_PATH);
		$isSucc = $userDaoGenerator->genDaoCodeByDbNameAndTableName("test", "user"); // test是数据库名字，user是表名
		$isSucc = $userDaoGenerator->genDaoCodeByDbNameAndTableName("test", "test_user"); // test是数据库名字，test_user是表名

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
