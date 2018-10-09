<?php $title = 'Liste des posts'; ?>

<?php ob_start(); ?>
<h1>Mon super blog !</h1>
<p>Derniers billets du blog :</p>
<div class="news">
    <h3> Titre
        <em>la date</em>
    </h3>
    <p> 
        contenu
    <br />
    <em><a href="#">Commentaires</a></em>
    </p>
</div>
<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>