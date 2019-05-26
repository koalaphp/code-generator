<?php
/**
 * Created by PhpStorm.
 * User: laiconglin
 * Date: 2018/10/14
 * Time: 16:25
 */

class CustomModelTpl
{
	public static $template = <<<EOT
<?php
namespace Library\Dao\%dbNamespace%;

/**
 * Created by Koala Command Tool. Custom Model Tpl
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