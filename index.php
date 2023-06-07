<?php
    require_once('./php/services/PageService.php');
?>
<!DOCTYPE html>
<html>

<head>
    <title>Junges Münsterschwarzach - DB2LDAP-Migrator</title>
    <meta name="author" content="Lucas 'Pad' Kinne">
    <meta charset="utf-8">
    <link rel="icon" href="favicon.png">
    <link type="text/css" rel="stylesheet" href="css/bootstrap.min.css" media="screen,projection" />
    <link type="text/css" rel="stylesheet" href="css/bootstrap-icons.min.css" media="screen,projection" />
    <link type="text/css" rel="stylesheet" href="css/stylesheet.css" media="screen,projection" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<body>
    <nav class="navbar bg-body-secondary p-1">
        <div class="container justify-content-center">
            <a class="navbar-brand p-0" href="/">
                <img src="favicon.png" alt="Logo" height="30px" class="m-0 me-2 d-inline-block align-text-top">
                <span class="text-ci-primary">Junges Münsterschwarzach</span>
                <span> - </span>
                <span>DB<span class="text-danger">2</span>LDAP</span>
            </a>
        </div>
    </nav>

    <?php PageService::render(); ?>

    <script type="text/javascript" src="js/jquery-3.7.0.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="js/scripts.js"></script>
</body>

</html>