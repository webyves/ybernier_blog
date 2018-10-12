<?php $title = 'Liste des posts'; ?>

<?php ob_start(); ?>
<div class="row">
    <div class="col text-center">
        <h1>Mon super blog !</h1>
        <p>Derniers billets du blog :</p>
    </div>
</div>
<?php 
    foreach ($postList as $post) {
        require ('listPostsDetail.php');
    }
?>
<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>