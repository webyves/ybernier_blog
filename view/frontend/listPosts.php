<?php $title = 'Liste des posts'; ?>

<?php ob_start(); ?>
<h1>Mon super blog !</h1>
<p>Derniers billets du blog :</p>
<?php 
    foreach ($postList as $post) {
?>
<div class="news">
    <h3> <?= $post['title']; ?>
        <em><?= $post['date_fr']; ?></em>
    </h3>
    <p> 
        <?= $post['content']; ?>
    <br />
    <em><a href="#">Commentaires</a></em>
    </p>
</div>
<?php 
    }
?>
<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>