<?php

use Symfony\Component\HttpFoundation\Request;

// Get all posts
$app->get('/api/posts', function (Request $request) use ($app) {

    if ($request->query->get('author')) {
        $posts = $app['dao.post']->findAllByAuthor($request->query->get('author'));
    } else {
        $posts = $app['dao.post']->findAll();
    }

    $responseData = array();
    foreach ($posts as $post) {
        $responseData["posts"][] = array(
            'id' => $post->getId(),
            'content' => $post->getContent(),
            'date' => $post->getDate(),
            'author' => $post->getAuthor()
        );
    }

    $responseData["count"] = count($posts);

    return $app->json($responseData);
})->bind('api_posts');

// Get on post
$app->get('/api/posts/{id}', function ($id, Request $request) use ($app) {
    $post = $app['dao.post']->find($id);
    if (!isset($post)) {
        $app->abort(404, 'Post not exist');
    }

    $responseData[] = array(
        'id' => $post->getId(),
        'content' => $post->getContent(),
        'date' => $post->getDate(),
        'author' => $post->getAuthor()
    );

    return $app->json($responseData);
})->bind('api_post');