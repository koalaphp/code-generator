# KoalaPHP Code Generator Component

**MySQL的Dao和Model代码生成器**

通过命令行的方式，根据配置的模板，连接MySQL的数据库的表，获取表结构信息，并生成对应的Dao文件和Model文件。


## 1. 快速开始

进行基本配置

```
define('OUTPUT_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Dao' . DIRECTORY_SEPARATOR . "%dbNamespace%");
```


根据默认的模板生成Dao文件

```
$userDaoGenerator = new \Koala\CodeGenerator\DaoGenerator();
$userDaoGenerator->setPdo($myMasterPDO); // $myMasterPDO 是连接到数据库的 PDO对象
$userDaoGenerator->setFullParentDir(OUTPUT_PATH);
$isSucc = $userDaoGenerator->genDaoCodeByDbNameAndTableName("test", "user"); // test是数据库名字，user是表名
$isSucc = $userDaoGenerator->genDaoCodeByDbNameAndTableName("test", "test_user"); // test是数据库名字，test_user是表名
```

根据默认的模板生成Model文件

```
$userModelGenerator = new \Koala\CodeGenerator\ModelGenerator();
$userModelGenerator->setPdo($myMasterPDO); // $myMasterPDO 是连接到数据库的 PDO对象
$userModelGenerator->setFullParentDir(OUTPUT_PATH);
$isSucc = $userModelGenerator->genModelCodeByDbNameAndTableName("test", "user"); // test是数据库名字，user是表名
$isSucc = $userModelGenerator->genModelCodeByDbNameAndTableName("test", "test_user"); // test是数据库名字，test_user是表名
```

## 2. 个性化配置Dao模板和Model模板


### 2.1 个性化配置Dao模板

建立 `CustomDaoTpl.php` 文件

```
<?php
class CustomDaoTpl
{
    public static $template = <<<EOT
<?php
namespace Library\Dao\%dbNamespace%;
use Koala\Database\SimpleDao;
/**
 * Created by Koala Command Tool. Custom Dao Tpl
 * Author: %author%
 * Date: %date%
 * 
 * @method %tableModelName% findOne(\$conditions = [], \$sort = "id desc")
 * @method %tableModelName%[] findAllRecordCore(\$conditions = [],  \$sort = "id desc", \$offset = 0, \$limit = 20, \$fieldList = [])
 * @method \\Generator|%tableModelName%[]|%tableModelName%[][] createGenerator(\$conditions = [], \$numPerTime = 100, \$isBatch = false)
 * 
 * %tableComment% Dao 类，提供基本的增删改查功能
 */
class %tableDaoName% extends SimpleDao
{
    // 连接的数据库
    protected \$database = '%dbName%';
    // 表名
    protected \$table = '%tableName%';
    // 主键字段名
    protected \$primaryKey = '%primaryKey%';
    
    // select查询的时候是否使用master，默认select也是查询master
    protected \$isMaster = true;
    
    // select查询出来的结果映射的Model类
    protected \$modelClass = %tableModelName%::class;
    
%fieldList%
}
EOT;
}
```

配置个性化Dao模板，生成对应的Dao文件

```
// 配置个性化模板
\Koala\CodeGenerator\Template\DaoTpl::$template = CustomDaoTpl::$template;
$userDaoGenerator = new \Koala\CodeGenerator\DaoGenerator();
$userDaoGenerator->setPdo($myMasterPDO); // $myMasterPDO 是连接到数据库的 PDO对象
$userDaoGenerator->setFullParentDir(OUTPUT_PATH);
$isSucc = $userDaoGenerator->genDaoCodeByDbNameAndTableName("test", "user"); // test是数据库名字，user是表名
$isSucc = $userDaoGenerator->genDaoCodeByDbNameAndTableName("test", "test_user"); // test是数据库名字，test_user是表名
```

### 2.2 个性化配置Model模板

建立 `CustomModelTpl.php` 文件

```
<?php
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
```

配置个性化Model模板，生成对应的Model文件

```
\Koala\CodeGenerator\Template\ModelTpl::$template = CustomModelTpl::$template;
$userModelGenerator = new \Koala\CodeGenerator\ModelGenerator();
$userModelGenerator->setPdo($myMasterPDO); // $myMasterPDO 是连接到数据库的 PDO对象
$userModelGenerator->setFullParentDir(OUTPUT_PATH);
$isSucc = $userModelGenerator->genModelCodeByDbNameAndTableName("test", "user"); // test是数据库名字，user是表名
$isSucc = $userModelGenerator->genModelCodeByDbNameAndTableName("test", "test_user"); // test是数据库名字，test_user是表名
```
