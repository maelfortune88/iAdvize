<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

require_once __DIR__.'/vendor/autoload.php';

$app = new Silex\Application();

require __DIR__.'/app/config/dev.php';
require __DIR__.'/app/app.php';
require __DIR__.'/app/routes.php';

use PHPHtmlParser\Dom;

/*
 * Si le nombre d'arguments passés à la commande n'est pas correct, on sors
 */
if ($argc != 2) {
    die("Format : php command.php \$int");
}

// Nombre de posts à récupérer
$count = $argv[1];

// Compteur comparé au nombre de pots à récupérer pour stopper la boucle
$x = 1;

// On va d'abord chercher la page 1 de viedemerde, qui est la plus à jour
$page = 1;

// On vide la table "posts"
$app['dao.post']->truncate();

while ($x < $count) {
    $dom = new Dom;
    $dom->load('http://www.viedemerde.fr/?page='.$page, ['whitespaceTextNode' => false, 'enforceEncoing' => 'UTF-8']);
    $page++;

    // Récuération des différents contenus
    $contents = $dom->find('.panel-content p.block a');

    // Récupération des blocks date, heure et auteur
    $authorAndDates = $dom->find('.panel-body .text-center');

    for ($i = 0; $i < count($contents); $i++) {

        /*
         * Création d'un nouveau modèle Post
         */
        $post = new \SilexApi\Post();
        $post->setContent($contents[$i]->text);
        $post->setDate(getDateAndHour($authorAndDates[$i]->text));
        $post->setAuthor(getAuthor($authorAndDates[$i]->text));

        // Enregistrement d'un nouveau tuple dans la table "posts"
        $app['dao.post']->save($post);

        // Affichage dans la console pour suivre l'avancement
        echo $x."/".$count."\n";
        $x++;

        if ($x > $count) {
            break;
        }
    }
}

/*
 * Permet de formater le champ spécifique date, heure et auteur pour renvoyer uniquement l'auteur
 * @param string $text
 * @return string l'auteur du post
 */
function getAuthor ($text)
{
    return substr(substr(explode("/", $text)[0], 4), 1, -4);
}

/*
 * Permet de formater le champ spécifique date, heure et auteur pour renvoyer uniquement date et heure
 * @param string $text
 * @return string la date et l'heure du post
 */
function getDateAndHour ($text)
{
//    $date = substr(substr(explode("/", $text)[1], 1), 0, -1);
//    $format = "%A %d %B %Y %H:%M";
//
//    $parsedDate = strptime($date, $format);
//    $timestamp = mktime($parsedDate['tm_hour'], $parsedDate['tm_min'], $parsedDate['tm_sec'], $parsedDate['tm_mon'] + 1,
//        $parsedDate['tm_mday'], $parsedDate['tm_year'] + 1900);
//
//    $dateTime = date('Y-m-d H:i:s', $timestamp);
    return substr(substr(explode("/", $text)[1], 1), 0, -1);
}