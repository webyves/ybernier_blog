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