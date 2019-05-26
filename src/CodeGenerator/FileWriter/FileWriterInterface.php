<?php
/**
 * Created by PhpStorm.
 * User: laiconglin
 * Date: 2018/10/7
 * Time: 23:26
 */
namespace Koala\CodeGenerator\FileWriter;

interface FileWriterInterface
{
	/**
	 * 获取配置的模板（推荐使用heredoc来配置）
	 * @return mixed
	 */
	function genTemplate();

	/**
	 * 生成替换规则
	 * @return mixed
	 */
	function genReplaceRule();

	/**
	 * 渲染将模板经过替换规则替换之后的内容
	 * @return mixed
	 */
	function renderContent();

	/**
	 * 获取需要将代码保存到的绝对路径（全路径）
	 * @return mixed
	 */
	function getFullSaveFilePath();

	/**
	 * 将生成的代码内容保存到指定的路径
	 * @return mixed
	 */
	function writeContentToFile();

	/**
	 * 打印输出日志信息，方便调试
	 * @param $str
	 * @return mixed
	 */
	function printDebugMessage($str);
}
