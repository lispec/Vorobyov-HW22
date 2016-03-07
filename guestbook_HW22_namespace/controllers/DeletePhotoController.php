<?php

namespace gallery\controllers;

use gallery\components\Router;
use gallery\components\UserSession;
use gallery\models\MySQLDB;

class DeletePhotoController extends BaseController{

    public function execute($arguments = [])
    {

//        var_dump($arguments);

        if(UserSession::getInstance()->isGuest) {
            Router::redirect('/');
        }


        MySQLDB::getInstance()->deletePhoto(UserSession::getInstance()->username, $arguments[1]);
        Router::redirect('/user/' . UserSession::getInstance()->username);


//        if($$arguments[1] == 'All'){
//            $fdb= new FileDB(__DIR__ . '/../db');
//            $fdb->
//        }

//        require_once 'views/parts/header.php';
//
//        require_once 'views/photo.php';
//
//        require_once 'views/parts/footer.php';





    }

}