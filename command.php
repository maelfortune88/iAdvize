<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

require_once __DIR__.'/vendor/autoload.php';

$app = new Silex\Application();

require __DIR__.'/app/config/dev.php';
require __DIR__.'/app/app.php';
require __DIR__.'/app/routes.php';

use PHPHtmlParser\Dom;

if ($argc != 2) {
    die("Format : php command.php \$int");
}

$count = $argv[1];
$x = 1;
$page = 1;

$app['dao.post']->truncate();

while ($x < $count) {
    $dom = new Dom;
    $dom->load('http://www.viedemerde.fr/?page='.$page, ['whitespaceTextNode' => false, 'enforceEncoing' => 'UTF-8']);
    $page++;

    $contents = $dom->find('.panel-content p.block a');
    $authorAndDates = $dom->find('.panel-body .text-center');

    for ($i = 0; $i < count($contents); $i++) {
        $post = new \SilexApi\Post();
        $post->setContent($contents[$i]->text);
        $post->setDate(getDateAndHour($authorAndDates[$i]->text));
        $post->setAuthor(getAuthor($authorAndDates[$i]->text));
        $app['dao.post']->save($post);

        echo $x."/".$count."\n";
        $x++;

        if ($x > $count) {
            break;
        }
    }
}

function getAuthor ($text)
{
    return substr(substr(explode("/", $text)[0], 4), 1, -4);
}

function getDateAndHour ($text)
{
//    $timestamp = strtotime(substr(substr(explode("/", $text)[1], 1), 0, -1));
//    return date('Y-m-d H:i:s', $timestamp);
    return substr(substr(explode("/", $text)[1], 1), 0, -1);
}