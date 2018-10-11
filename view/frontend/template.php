<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title><?= $title ?></title>
        <!-- bootstrap CSS -->
        <link href="vendor/components/bootstrap/css/bootstrap.min.css" rel="stylesheet" /> 
        
        <!-- bootstrap jquery JS -->
        <script src="vendor/components/jquery/jquery.min.js"></script>
        <script src="vendor/components/bootstrap/js/bootstrap.min.js"></script>
        
        <!-- Summernote CSS JS -->
        <!-- inutile dans le frontend
        <link href="vendor/summernote/summernote/dist/summernote-bs4.css" rel="stylesheet" /> 
        <script src="vendor/summernote/summernote/dist/summernote-bs4.min.js"></script>
        <script src="vendor/summernote/summernote/dist/lang/summernote-fr-FR.min.js"></script>
        <script>
            $(document).ready(function() {
              $('#summernote').summernote({
                lang: 'fr-FR'
              });
            });
        </script>
        -->
        <!-- yBernier files -->
        <link href="public/css/main.css" rel="stylesheet" /> 
        
    </head>
        
    <body>
        <div class="container">
            <?= $content ?>
        </div>
    </body>
</html>