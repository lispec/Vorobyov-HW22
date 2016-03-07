<?php

namespace gallery\controllers;

abstract class BaseController
{
    abstract public function execute($arguments = []);


    // для работы twig

    private $twig;

    public function __construct()
    {
        // Инициализируем twig в конструкторе
        $loader = new \Twig_Loader_Filesystem(__DIR__ . '/../views');
        $this->twig = new \Twig_Environment($loader, [
            // 'cache'=>__DIR__.'/../runtime'
            'cache' => false,       // наверное отключение кэширование при разработке
        ]);
    }

    public function render($view, $params = []){
        return $this->twig->render($view, $params);
    }

}