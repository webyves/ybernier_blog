<?php $title = 'Liste des posts'; ?>

<?php ob_start(); ?>
<h1>Mon super blog !</h1>
<p>Derniers billets du blog :</p>
<?php 
    foreach ($postList as $post) {
        require ('listPostsDetail.php');
    }
?>
<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>