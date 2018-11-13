# ybernier_blog
Projet 5 : Dev PHP Blog pour le cours OpenClassRooms DA PHP/symfony

# Code Quality tests
- Sensiolabs SymfonyInsight : [![SymfonyInsight](https://insight.symfony.com/projects/3e7f60d1-cc71-470c-9fb4-965c84f0a768/small.svg)](https://insight.symfony.com/projects/3e7f60d1-cc71-470c-9fb4-965c84f0a768)
- Codacy : [![Codacy Badge](https://api.codacy.com/project/badge/Grade/8aba3650df0441139de993e3d9ea2a0a)](https://www.codacy.com/app/webyves/ybernier_blog?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=webyves/ybernier_blog&amp;utm_campaign=Badge_Grade)


# INSTALLATION NOTES

1) cloner le repository sur votre serveur
2) executer le fichier SQL sur votre base de donnée pour l'importer
3) Modifier le fichier App.php comme suit :
    - const DB_HOST = "localhost"; <- remplacer si bessoin l'addresse de connexion a votre base de donnée entre les "
    - const DB_NAME = "ybernier_blog"; <- remplacer si bessoin le nom de votre base de donnée entre les "
    - const DB_USER = "root"; <- remplacer si bessoin votre identifiant de connexion a votre base de donnée entre les "
    - const DB_USER_PWD = ""; <- inserer votre mot de passe de connexion a votre base de donnée entre les "
    - const ADMIN_EMAIL = "webyves@hotmail.com"; <- remplacer avec l'adresse mail de votre administrateur entre les "
    - const CAPTCHA_SITE_KEY = "xxxxxx"; <- remplacer avec votre site Key de captcha google v2 entre les "
    - const CAPTCHA_SECRET_KEY = "xxxx"; <- remplacer avec votre secret Key de captcha google v2 entre les "
    - const MAX_FILE_SIZE = 1048576; <- remplacer le chiffre par la taille max en octets souhaitée pour vos images
4) acceder au frontoffice par le fichier index.php
5) acceder au backoffice identifiez vous sur le frontoffice avec les identifiant suivants : 
    - mail : admin@admin.com
    - pwd : Admin_Demo
6) Conseil : 
- Créez vous un compte que vous validerez via le backoffice 
- puis bloqué le compte admin@admin.com
- pour creer votre captache google v2 rendez vous ici : https://www.google.com/recaptcha/admin


# VERSION PATCH NOTES 

v0.113 - 12/11/2018 12h
- Ajout mentions legales
- Ajout politique confidentialité
- correction upload et upload infos

v0.112 - 11/11/2018 16h
- separation des categories de post dans leur propres fichiers
- quelques corrections de style de code

v0.111 - 11/11/2018 14h45
- Nombreuses corrections pour passer Insight
- nouveau system login/logout
- suppression des variable global et $GLOBALS remplacer par une class App et de l'injection de dependance

v0.110 - 10/11/2018 17h30
- Nombreuses corrections pour passer Insight
- nombreuses corrections pour PSR2 code

v0.109 - 9/11/2018 11h30
- Add Post Works

v0.108 - 8/11/2018 19h30
- Upload Image Top
- Fin MAJ POSTS

v0.107 - 8/11/2018 16h
- Debut de Gestion des post
- affichage dans admin
- modification rapide cat & etat

v0.106 - 6/11/2018 12h
- Gcorrection d'une request (nbpost)
- Correction/MAJ views design front et back

v0.105 - 5/11/2018 16h
- Gestion des categories implementé/testé/ok
- MAJ views back et front
- MAJ controller/Manager/entities

v0.104 - 4/11/2018 18h
- Gestion des commentaires implementé/testé/ok
- MAJ views back et front
- MAJ controller/Manager/entities

v0.103 - 4/11/2018 15h45
- Modification du Captcha => config.php
- modification des instructions d'installation (ajout du captcha)
- Ajout du Captcha a l'inscription

v0.102 - 4/11/2018 14h15
- Gestion des utilisateurs implementé/testé/ok
- MAJ views back et front
- MAJ controller/Manager/entities
- Creation d'un fichier config pour gerer l'installation facilement

v0.101 - 3/11/2018 16h
- Debut de l'admin.
- Caneva de pages admin
- MAJ Views routeur et Twig pour pages admin

v0.018 - 3/11/2018 12h
- Correction possible erreur id post
- integration CAPTCHA sur formulaire
- epurage des fichiers (suppression partie et commentaires inutiles/debug)
- correction design responsive (leftbar dans menu et meilleur visuel comments).

v0.017 - 2/11/2018 12h30
- Overlay liens sur listpost
- Responses aux commentaires implementé/testé/ok

v0.016 - 31/10/2018 17h
- AddComment fonctionne (MAJ MVC comments)

v0.015 - 31/10/2018 13h15
- user cookie destroy Corigé
- Debut system Comments controller/manager/entities/view + MAJ postController
- PageController devient une classe mere pour StaticPageController
- MAJ UserController en PageController Extention
- MAJ/correction du routeur index et des vues (suppression get a) 
- MAJ Post Manager/entities avec le nbcom
- Ajout de commentaires dans le code pour chaque fonction (WIP)

v0.014 - 26/10/2018 14h30
- user cookie OK/Testé

v0.013 - 26/10/2018 12h30
- user password crypt OK/Testé
- debut fonction et bdd pour user cookie

v0.012 - 24/10/2018 12h
- Creation Utilisateur OK/Testé
- Debut des commentaires (vue et controller)

v0.011 - 23/10/2018 20h
- Design Version black 
- SmallContent dans l'hydratation

v0.010 - 23/10/2018 16h
- Formulaire de contact OK/Testé 
- Generic Function sendMail dans manager

v0.009 - 22/10/2018 18h
- system connexion
- debut system inscription

v0.008 - 22/10/2018 12h
- Creation view debug
- debut system connexion

v0.007 - 11/10/2018 19h20
- Nouvelle structure de page (menu et footer et leftsidebar)
- Creation/modification de view
- Nouveau controller pour afficher les basic view

v0.006 - 11/10/2018 12h
- modification des view avec twig
- structure cards bootstrap pour test

v0.005 - 11/10/2018 10h30
- Installation bibliotheque via composer (twig bootstrap jquery summernote)
- premiere structure de page en bootstrap

v0.004 - 09/10/2018 18h30
- passage en POO

v0.003 - 09/10/2018 16h
- base de donnée de demo installée
- premieres fonctions et requetes fonctionne bien

v0.002 - 07/10/2018 19h
- base de donnée vierge créée
- premiers models structures de fichiers php

v0.001 - 07/10/2018 16h27
- architecture de base vide

v0.00 - 07/10/2018 16h22
- Creation du Repository
- Installation de github desktop
- clone repository en local
