<?php
/**
 * Created by PhpStorm.
 * User: Frédéric
 * Date: 01/12/2016
 * Time: 11:56
 */
require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_mysql',
        'host'      => 'localhost',
        'dbname'    => 'silex',
        'user'      => 'root',
        'password'  => 'simplon974',
        'charset'   => 'utf8mb4',
        'path'     => __DIR__.'/config.db',

    ),
));
// Création route
$app->get('/' , function() use($app){
    $content = "Hello World";
    return $content;
});

$app->get('/blog',function() use($app){
    $sql = "SELECT * FROM posts";
    $posts = $app['db']->fetchAll($sql);
//   var_dump($posts);
    $titre = "";
    foreach ($posts as $post){
       $titre .= $post['title'] . "<br>";
    }
    return "<h2> Liste des titres </h2><h3>{$titre}<h3>";
});

$app->get('/blog/{id}' , function($id) use($app) {
    $sql = "SELECT * FROM posts WHERE id = ? ";
    $post = $app['db']->fetchAssoc($sql,array((int) $id));
//    var_dump($post);
    return "<h2>Post : </h2> " .
           "<ul>" .
            "<li>Titre :   {$post['title']}</li>".
            "<li>Contenu : {$post['body']}</li>".
            "<li>Crée le : {$post['createdAt']}</li>" .
            "<li>Par : {$post['author']}</li>" .
            "</ul>";
})->assert('id', '\d+');

$app->get('/blog/insert' , function() use($app){
    $app['db']->insert('posts', array(
            'title' => 'Kalash',
            'body' => 'Allo baby allo',
            'createdAt' => '2016-12-01',
            'author' => 'Moi',
        )
    );
    return 'Inseré';
});

$app->run();