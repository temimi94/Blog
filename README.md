#Projet 5

##Création d'un blog PHP

###OpenclassRooms

-----------------

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/f54c183bd3f7417eabf0a022943b0264)](https://www.codacy.com/manual/kindertheo/Projet-5-PHP-OpenClassrooms?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=kindertheo/Projet-5-PHP-OpenClassrooms&amp;utm_campaign=Badge_Grade)

[![Maintainability](https://api.codeclimate.com/v1/badges/e15855a809aa9305f539/maintainability)](https://codeclimate.com/github/kindertheo/Projet-5-PHP-OpenClassrooms/maintainability)

-----------------

#Installation :

*   Lancer la commande ` git https://github.com/kindertheo/Projet-5-PHP-OpenClassrooms.git`
*   Lancer la commande `cd Projet-5-PHP-OpenClassrooms`
*   Lancer dans le terminal `composer install`

##Remarque

*   Mettre à jour config/database.php

Pour que vous puissiez vous connecter à votre base de données, veuillez modifier le fichier avec vos identifiants, hôte et nom de base de données
Ces informations sont trouvables chez votre hébergeur.

`<?php
 
 define('DB_DSN', 'mysql:host=localhost;dbname=p5;charset=UTF8');
 
 define('DB_USER', '');
 
 define('DB_PASS', '');
 
 define('DB_OPTIONS', array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));`
 

*   Mettre à jour config/setupMail.php

Pour que vous puissiez pleinement profiter de l'envoi d'email, veuillez ajouter dans les '' vos identifiants, ports et protocole smtp
Ces informations sont trouvables chez votre hébergeur.

`<?php

define('MAIL_SMTP', '');

define('MAIL_PORT', '');

define('MAIL_USERNAME', '');

define('MAIL_PASSWORD', '');`


*   Importer le fichier import.sql dans votre base de données
Pour que le site marche correctement il est important d'utilisé ce modèle de base de données
Il créera la base de données, les tables, les champs et inserera des données utiles au début du site (compte admin et utilisateur, premiers articles, commentaires...)
-----------------

Le site est consultable [ici](https://blog.kindertheo.net)

-----------------

Les identifiants par défaut sont :

Administrateur

>admin@admin.com
>password

Utilisateur

>user@user.com
>123