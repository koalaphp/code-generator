<?php
/**
 * Created by PhpStorm.
 * User: laiconglin
 * Date: 2018/10/13
 * Time: 16:57
 */

namespace Koala\CodeGenerator\FileWriter;

/**
 * 代码生成器抽象类
 *
 * Class FileWriter
 * @package Koala\CodeGenerator
 */
abstract class FileWriter implements FileWriterInterface
{
	protected $template = ""; // 模板内容
	protected $replacePattern = []; // 对模板进行替换的规则
	protected $fullParentDir = "./Dao/%dbNamespace%"; // 保存代码的完整路径
	protected $author = "laiconglin3@126.com"; // 作者
	protected $date = "1970-01-01"; // 代码生成的日期

	public function __construct()
	{
	}

	/**
	 * 获取配置的模板（推荐使用heredoc来配置）
	 * @return string
	 */
	public function genTemplate()
	{
		return $this->template;
	}

	/**
	 * 生成替换规则
	 * @return array
	 */
	public function genReplaceRule()
	{
		// 需要程序自行实现
		return [];
	}

	/**
	 * 渲染将模板经过替换规则替换之后的内容
	 * @return string
	 */
	public function renderContent()
	{
		$replacePattern = $this->genReplaceRule();
		$tplStr = $this->genTemplate();
		$outputStr = str_replace(array_keys($replacePattern), array_values($replacePattern), $tplStr);
		return $outputStr;
	}

	/**
	 * 获取需要将代码保存到的绝对路径（全路径）
	 * @return string
	 */
	public function getFullSaveFilePath()
	{
		return $this->fullParentDir;
	}

	/**
	 * 将生成的代码内容保存到指定的路径
	 * @return bool
	 */
	public function writeContentToFile()
	{
		$fullPath = $this->getFullSaveFilePath();
		$outputParentDir = dirname($fullPath);
		if (!is_dir($outputParentDir)) {
			mkdir($outputParentDir, 0755, true);
		}

		file_put_contents($fullPath, $this->renderContent());
		// 打印日志信息
		$this->printDebugMessage(__METHOD__ . " writeContentToFile success!" . PHP_EOL);

		return true;
	}

	/**
	 * 打印输出日志信息，方便调试
	 * @param $str
	 * @return mixed
	 */
	public function printDebugMessage($str)
	{
		echo $str;
	}
}