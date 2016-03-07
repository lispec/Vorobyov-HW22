<?php
namespace gallery;

use gallery\components;
use gallery\models\MySQLDB;

// 1 подключаем файлы

require_once 'vendor/autoload.php';         // подключаем автолодер (который будет вызываться при обращении к не найденному классу)

require_once 'components/Router.php';
require_once 'components/UserSession.php';
require_once 'controllers/BaseController.php';
require_once 'components/Pagination.php';

require_once 'models/Picture.php';
require_once 'models/FileDB.php';
require_once 'models/MySQLDB.php';
require_once 'components/SocialLinks.php';

// NEW - функция для подключения файлов.

// Вызываеться в том случае когда файлы не были найдены среди уже подключенных
// Предполагаем что все файлы классы подлючаемые лежат в папке classes

// 1) создаем функцию подключения файлов
// (раньше использовалась, а сейчас используеться 2 вариант с регистрацией
// поскольку он позволяет использовать регистрировать много и ставить их в очередь при обработке)
//function my_autoloader($class)
//{
//    require_once 'classes/' . $class . '.php';
//}

// ИЛИ

// 2) регистрируем функцию (это для того чтобы если
// таких будет несколько, чтобы они выстраивались в очередь)
//spl_autoload_register(function ($class) {
//    var_dump($class);
//    require_once 'classes/' . $class . '.php';
//    die;
//});
// 3) Вызываем не существующий класс
//$class = new MySuperClass();

//*NEW


// 2 подключаем БД
$mysqlConf = require_once 'mysql.php';
MySQLDB::init($mysqlConf['dbName'], $mysqlConf['host'], $mysqlConf['user'], $mysqlConf['password']);


// 3 подключаем маршрутизатор
$router = new components\Router($_SERVER['REQUEST_URI']);

if (!$router->handle()) {
    echo 'Path not found.';
}