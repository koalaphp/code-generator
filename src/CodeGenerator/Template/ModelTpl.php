<?php
/**
 * Created by PhpStorm.
 * User: laiconglin
 * Date: 2018/10/14
 * Time: 16:25
 */
namespace Koala\CodeGenerator\Template;

class ModelTpl
{
	public static $template = <<<EOT
<?php
namespace Library\Dao\%dbNamespace%;

/**
 * Created by Koala Command Tool.
 * Author: %author%
 * Date: %date%
 * 
 * %tableComment% Model 模型类
 */
class %tableModelName%
{
%fieldList%
}
EOT;

}