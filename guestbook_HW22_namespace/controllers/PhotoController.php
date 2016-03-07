<?php

namespace gallery\controllers;

use gallery\components\UserSession;
use gallery\models\MySQLDB;

class PhotoController extends BaseController {
    public function execute($arguments = []) {

        $photo = MySQLDB::getInstance()->getPhoto(UserSession::getInstance()->username, $arguments[2]);
        $file = 'pictures/' . UserSession::getInstance()->username . '/' . $photo['photoURI'];

        if (file_exists($file)) {
            //header([])
            header('Content-Type: image/jpg');
//            header('Content-Description: File Transfer');
//            header('Content-Type: application/octet-stream');
//            header('Content-Disposition: attachment; filename='.'vasia.jpg');
//            header('Expires: 0');
//            header('Cache-Control: must-revalidate');
//            header('Pragma: public');
//            header('Content-Length: ' . filesize($file));
            readfile($file);
            exit;
        }


//        require_once 'views/parts/header.php';
//
//        require_once 'views/photo.php';
//
//        require_once 'views/parts/footer.php';


        return true;
    }
}