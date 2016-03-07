<?php

//ПРОСТРАНСТВО ИМЕН
namespace gallery\models;

class MySQLDB
{
    //+ПОЛЯ (атрибуты)
    private $pdo;       // но можно и объявить его публичным, и сделать все по простому без SingleTona (и соответственно без методов init(), getInstance, $instance)
    private static $instance;       // переменная хранящая экземпляр класса MySQLDB

    public static function init($dbName, $host, $user, $password)
    {
        if (!self::$instance) {
            self::$instance = new self($dbName, $host, $user, $password);
        }
    }

    //+метод для получения экземпляра класса MySQLDB
    public static function getInstance()
    {
        if (!self::$instance) {
            throw new \Exception('MySQL DB not onit, use init method.');
        }
        return self::$instance;
    }

    //+КОНСТРУКТОР принимающий настройки для подключения
    private function __construct($dbName, $host, $user)   // при необходимости $password
    {
        try {
            $this->pdo = new \PDO("mysql:dbname=$dbName;host=$host", $user);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    //МЕТОДЫ КЛАССА

    //+найти пользователя
    public function findUser($username, $password)
    {
        $statement = $this->pdo->prepare('SELECT id FROM user WHERE username= :u AND password = :p');    // для возвращения результата от запроса
        $statement->bindValue(':u', $username);
        $statement->bindValue(':p', sha1($password));
        if ($statement->execute()) {
            if ($user = $statement->fetch(\PDO::FETCH_ASSOC)) {
                return $user;
            }
        }
        return false;
    }

    //+найти UserName пользователя
    public function findUserName($username)
    {
        $s = $this->pdo->prepare('SELECT id FROM user WHERE username=:u');
        $s->bindValue(':u', $username);
        if ($s->execute()) {
            if ($user = $s->fetch(\PDO::FETCH_ASSOC)) {
                return true;
            }
        }
        return false;
    }

    //+добавить пользователя
    public function addUser($username, $password)
    {
        $s = $this->pdo->prepare('INSERT INTO `user` (username, password, regDate) VALUES (:u, :p, NOW())');
        $s->bindValue(':u', $username);
        $s->bindValue(':p', sha1($password));
        $s->execute();
        return $this->pdo->lastInsertId();
    }

    //+добавить фото
    public
    function addPhoto($username, $photoURI, $description)
    {
        $s = $this->pdo->prepare('SELECT id FROM user WHERE username= :u');
        $s->bindValue(':u', $username);
        if ($s->execute()) {
            if ($user = $s->fetch(\PDO::FETCH_ASSOC)) {
                $s = $this->pdo->prepare('INSERT INTO picture (userId, uri, description, date) VALUES (:uId, :uri, :desc, NOW())');
                $s->bindValue(':uId', $user['id']);
                $s->bindValue('uri', $photoURI);
                $s->bindValue(':desc', $description);
                $s->execute();
                return $this->pdo->lastInsertId();
            }
        }
    }

    //
    public function addPhotoByUserId($userId, $photoURI, $description)
    {
        $s = $this->pdo->prepare('INSERT INTO `picture` (userId, uri, description, date) VALUES (:uId,:uri,:desc,NOW())');
        $s->bindValue(':uId', $userId);
        $s->bindValue(':uri', $photoURI);
        $s->bindValue(':desc', $description);
        $s->execute();
        return $this->pdo->lastInsertId();
    }

    //+получить фото
    public function getPhoto($username, $photoId)
    {
        return $this->getPhotoByPhotoId($photoId);
    }

    public function getPhotoByPhotoId($photoId)
    {
        $s = $this->pdo->prepare('SELECT * FROM picture WHERE id = :id');
        $s->bindValue(':id', $photoId);
        if ($s->execute()) {
            if ($pic = $s->fetch(\PDO::FETCH_ASSOC)) {
                return $pic;
            }
        }
        return false;
    }


    // [!!!проверить]   получить все фото (пока без пагинации для этого добавить  $page, $perPage в getPhotos()   )
    public function getPhotos($username, $page, $perPage) {
        $offset = ($page - 1) * $perPage;

        $s = $this->pdo->prepare('SELECT picture.id as id, picture.userId, description, picture.uri as photoURI, picture.date FROM picture INNER JOIN `user` ON picture.userId = user.id WHERE user.username = :user ORDER BY `date` DESC LIMIT :limit OFFSET :offset ');

        $s->bindValue(':user', $username);
        $s->bindValue(':limit', $perPage, \PDO::PARAM_INT);
        $s->bindValue(':offset', $offset, \PDO::PARAM_INT);

        if ($s->execute()) {
            if ($photos = $s->fetchAll(\PDO::FETCH_ASSOC)) {
                return $photos;
            }
        } else {
            var_dump($s->queryString);
            var_dump($s->errorInfo());
            die;
        }

        return [];
    }


    // [!!!проверить]
    public function getPhotosByUserId($userId, $page, $perPage)
    {
        $offset = $page - 1 * $perPage;

        $s = $this->pdo->prepare('SELECT * FROM picture WHERE userId = :u LIMIT :l OFFSET :o');
        $s->bindValue(':l', $perPage);
        $s->bindValue(':o', $offset);
        $s->bindValue(':u', $userId);
        if ($s->execute()) {
            if ($photos = $s->fetchAll(\PDO::FETCH_ASSOC)) {
                return $photos;
            }
        }

        return [];
    }


    //+удаление фотографий
    public function deletePhoto($username, $id)
    {
        return $this->deletePhotoById($id);
    }

    public function deletePhotoById($id)
    {
        $s = $this->pdo->prepare('DELETE FROM picture WHERE id = :id');
        $s->bindValue(':id', $id);
        if (!$s->execute()) {
            var_dump($s->errorInfo());
            die;
        }

        return true;
    }


    //PostCount
    public function PostCount($username)
    {

        $s = $this->pdo->prepare('SELECT count(*) as c FROM picture INNER JOIN user ON user.id = picture.userId WHERE user.username = :u');
        $s->bindValue(':u', $username);
        if ($s->execute()) {
            if ($c = $s->fetch(\PDO::FETCH_ASSOC)) {
                return intval($c['c']);
            }
        }

        return false;
    }

}

// --------- ТЕСТИРУЕМ наш класс MySQLDB.php на предмет его работы, обычное создание -----------

//$db = new MySQLDB('gallary2', '127.0.0.1', 'root');   // обычное создание

//MySQLDB::init('gallary2', '127.0.0.1', 'root');         // через SingleTon

//var_dump(MySQLDB::getInstance()->findUserName('Michael'));
//var_dump(MySQLDB::getInstance()->findUser('Andrey', '123'));
//MySQLDB::getInstance()->addUser('Miron', '123');
//MySQLDB::getInstance()->addPhoto('Andrey', 'D:\1.jpg', 'Photo Description');
//MySQLDB::getInstance()->addPhotoByUserId('11', 'C:\2.jpg', 'bla bla');
//var_dump(MySQLDB::getInstance()->getPhoto('Andrey', 4));
//!! var_dump(MySQLDB::getInstance()->getPhotos('Michael', 1, 2));
//!! var_dump(MySQLDB::getInstance()->getPhotosByUserId(10, 1, 1));
//MySQLDB::getInstance()->deletePhoto('Andrey', 4);
//var_dump(MySQLDB::getInstance()->PostCount('Makar'));

