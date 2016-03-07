<?php

namespace gallery\models;

class Picture {
    public static function formatDate($dateStr) {
        $date = new \DateTime($dateStr);
        $date->add(new \DateInterval("PT2H"));
        return $date->format('d.m.Y H:i');

        //d.m.Y H:i:s
        //Y-m-d H:i:s
    }

    //NEW1
    // Работа с изображениями (пережатие и другое)
    public static function thumbnailPhoto($photoPath) {
        $img = new \Imagick($photoPath);

        // 2 Для масштабирования с сохранением пропорций
        $w1 = $img->getImageWidth();
        $h1 = $img->getImageHeight();

        $w2 = 512;
        $h2 = ($h1 * $w2) / $w1;

        $img->thumbnailImage($w2, $h2);         // не сохраняет масштаб
        $img->setImageCompressionQuality(70);
        //$img->adaptiveResizeImage(512, 512);
        //$img->thumbnailImage(512, 512);
        //$img->cropThumbnailImage(512, 512);   // сохраняет масштаб но обрезает

        $img->writeImage($photoPath);   // записать на фото
    }
    //*NEW1

    public static function uploadFile($tmpPath, $fileName, $username) {
        $pictureDir = __DIR__ . '/../pictures';
        $usernameDir = $pictureDir . '/' . $username;

        if(!file_exists($usernameDir)) {
            mkdir($usernameDir);
        }

        $pahInfo = pathinfo($fileName);
        $ext = isset($pahInfo['extension']) ? $pahInfo['extension'] : 'jpg';
        $time = time();
        $fileName = $time . '.' . $ext;
        $imageNewPath = $usernameDir . '/' . $fileName;
        move_uploaded_file($tmpPath, $imageNewPath);

        //NEW2
//        Picture::thumbnailPhoto($imageNewPath);
        // или
//        self::thumbnailPhoto($imageNewPath);
        //*NEW2


        return $fileName;


    }
}