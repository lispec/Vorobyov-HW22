<?php

namespace gallery\controllers;

use gallery\components\Router;
use gallery\components\UserSession;
use gallery\models\MySQLDB;

class IndexController extends BaseController {
    public function execute($arguments = []) {

        if (!UserSession::getInstance()->isGuest) {
            Router::redirect('/user/' . UserSession::getInstance()->username);
        }

        if (
            isset($_POST['username']) &&
            isset($_POST['password']) &&
            !empty($_POST['username']) &&
            !empty($_POST['password'])
        ) {

            if (MySQLDB::getInstance()->findUser($_POST['username'], $_POST['password'])) {
                UserSession::getInstance()->login($_POST['username']);
                Router::redirect('/user/' . UserSession::getInstance()->username);
            } else {
                $error = "Failed: Login and password not valid.";
            }
        } else if(
            isset($_POST['username']) &&
            isset($_POST['password'])
        ) {
            $error = "Failed: All fields are required.";
        }

//        require_once 'views/parts/header.php';
//        require_once 'views/main.php';
//        require_once 'views/parts/footer.php';

        // Вместо старых вьюх добавили twig
        echo $this->render('main.twig', [
            'error' => isset($error) ? $error : false,
            'title' => 'Main Page',
            'heading' => 'Hello PHP!!!'
        ]);

        return true;
    }
}