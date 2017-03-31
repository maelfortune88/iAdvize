# iAdvize
Test - Développeur PHP

1. Permettre à l’aide d’une ligne de commande, d’aller chercher les 200 derniers enregistrements du site “Vie de  merde” et de les stocker. (Champs à récupérer : Contenu, Date et heure, et auteur) 
  --> "php command.php $int"
  où $int est une variable entière qui permet de dire combien d'entrées on veut récupérer (ex : 200).

Vous devez utiliser un framework PHP de votre choix --> http://silex.sensiolabs.org/

Vous avez le choix dans la méthode ou le procédé de stockage --> installation en local d'un XAMPP et stockage des données dans une base de données MySql avec Doctrine :

$app['db.options'] = array(
    'driver'   => 'pdo_mysql',
    'charset'  => 'utf8',
    'host'     => '127.0.0.1',
    'port'     => '3306',
    'dbname'   => 'vdm',
    'user'     => 'root',
    'password' => '',
);

La structure de la BDD se trouve dans "db/posts.sql".

Vous devez utiliser GIT pour versionner vos fichiers --> OK

Vous devez utiliser Composer  pour gérer vos dépendances --> OK

Vous devez tester unitairement votre code --> KO

Vous devez mettre à disposition votre code via Github --> OK

Vous ne devez pas utiliser l’API du site “Vie de Merde” pour récuperer les informations --> Utilisation d'un parser HTML afin de récupérer les entrées voulues.

La description fonctionnelle via BeHat serait un plus --> QuickStart rélisé sur http://behat.org avec la création de basket.feature, Basket.php, Shelf.php, FeatureContext.php (vendor/bin/features).

Problèmes rencontrés :

1. Lors du parsing HTML, sur une même page (ex : http://www.viedemerde.fr/?page=1), le nombre d'objets "content" récupérés n'était pas le même que celui de "date" et "author". Il en résulte des valeurs nulles par endroits.

2. Le format de date sur le site était en français "non standard" pour être reconnu tel quel par la fonction strtotime. Il aurait fallu modifier la locale de la date PHP pour qu'elle soit reconnue et ensuite convertir le timestamp dans le format "Y-m-d H:i:s".

3. Problème d'encodage de caractères par moment au niveau du contenu du post.
