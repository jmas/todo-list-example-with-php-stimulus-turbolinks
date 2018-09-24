<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

session_start();

if (empty($_SESSION['todos'])) {
    $_SESSION['todos'] = [];
}

$app = new \Slim\App();

// Get container
$container = $app->getContainer();

// Register component on container
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig( dirname(__FILE__) . DIRECTORY_SEPARATOR . 'templates');
    $basePath = rtrim(str_ireplace('index.php', '', $container->get('request')->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container->get('router'), $basePath));
    return $view;
};

$app->get('/', function (Request $req,  Response $res, $args = []) {
    return $this->view->render($res, 'todo_app.html', [
        'todos' => $_SESSION['todos'],
    ]);
})->setName('todosHome');

$app->get('/todos', function (Request $req,  Response $res, $args = []) {
    return $res->withStatus(200)->withJson($_SESSION['todos']);
});

$app->post('/todos', function (Request $req,  Response $res, $args = []) {
    $_SESSION['todos'][] = $req->getParsedBody() + [ 'id' => uniqid() ];
    return $res->withRedirect($this->get('router')->pathFor('todosHome'), 301);
})->setName('addTodo');

$app->post('/todos/{id}', function (Request $req,  Response $res, $args = []) {
    $_SESSION['todos'] = array_filter($_SESSION['todos'], function($item) use ($args) {
        return $item['id'] !== $args['id'];
    });
    return $res->withRedirect($this->get('router')->pathFor('todosHome'), 301);
})->setName('deleteTodo');

$app->run();
