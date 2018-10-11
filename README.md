# ybernier_blog

Blog dev pour cours OC

# INSTALLATION NOTES

1) cloner le repository sur votre serveur
2) executer le fichier SQL sur votre base de donnée
3) Modifier la fonction dbConnect() le fichier model/Manager.php comme suit :
    - $dbUser = "root"; <- remplacer si bessoin votre identifiant de connexion a votre base de donnée entre les "
    - $dbUserPwd = ""; <- inserer votre mot de passe de connexion a votre base de donnée entre les "
    - $dbHost = "localhost"; <- remplacer si bessoin l'addresse de connexion a votre base de donnée entre les "
    - $dbName = "ybernier_blog"; <- remplacer si bessoin le nom de votre base de donnée entre les "
4) acceder au frontend par le fichier index.php
5) acceder au backend par le fichier admin.php avec les identifiant suivants : 
    - mail : admin@admin.com
    - pwd : Admin_Demo



# VERSION PATCH NOTES 

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