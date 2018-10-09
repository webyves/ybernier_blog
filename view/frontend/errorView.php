<?php $title = 'ERREUR'; ?>

<?php ob_start(); ?>
<h1>Mon super blog !</h1>
<div class="erreur">
    <h3> Une erreur c'est produite </h3>
    <p> 
        <?= $errorMessage; ?>
    </p>
</div>
<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>