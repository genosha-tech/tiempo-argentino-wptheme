<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tiempo Argentino</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital@1&family=Merriweather:wght@900&family=Red+Hat+Display:wght@400;500;700;900&family=Caladea:wght@400;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="../../css/bootstrap/bootstrap.css">
    <link rel="stylesheet" href="../../css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"
        integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg=="
        crossorigin="anonymous"></script>
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="../../js/bootstrap.min.js"></script>
</head>

<body>
    <div class="ta-context portada">
        <?php include_once('../../partes/header.php');  ?>
        <?php include_once('../../partes/banner-nuevo-tiempo.php');  ?>
        <div class="container">
            <div class="ta-context blue-border mt-3">
                <?php include('../../partes/eleccion-contenidos.php');  ?>
            </div>

        </div>
        <div class="container p-0">
            <?php include_once('../../partes/misc.php');  ?>
        </div>
        <div class="container p-0">
            <?php include_once('../../partes/simple-x7.php');  ?>
        </div>
        <div class="container p-0">
            <?php include_once('../../partes/miscelanea-categoria.php');  ?>
        </div>
        <div class="container mt-3">
            <div class="ta-context blue-border newsletter-especial">
                <?php include_once('../../partes/newsletter-especial.php');  ?>
            </div>
        </div>
        <div class="container p-0">
            <?php include_once('../../partes/miscelanea-categoria2.php');  ?>
        </div>
        <div class="container mt-5">
            <div class="line-height-0">
                <div class="separator m-0"></div>
            </div>
            <div class="ta-context blue-border">
                <?php include_once('../../partes/fotogaleria.php');  ?>
            </div>
        </div>
        <div class="container-fluid container-lg p-0 ">
            <div class="ta-context yellow-border  mt-3">
                <?php include_once('../../partes/seamos-socios-fullwidth.php');  ?>
            </div>
        </div>
        <div class="ta-context dark-bg mt-3">
            <?php include_once('../../partes/audiovisual-especial.php');  ?>
        </div>
        <div class="container p-0">
            <?php include_once('../../partes/simple-x6.php');  ?>
        </div>
        <div class="ta-context light-blue-bg mt-3">
            <?php include_once('../../partes/opinion.php');  ?>
        </div>


        <div class="ta-context dark-bg">
            <?php include_once('../../partes/podcasts.php');  ?>
        </div>
        <div class="container mt-3">
            <div class="line-height-0">
                <div class="separator m-0"></div>
            </div>
            <div class="ta-context blue-border">
                <?php include_once('../../partes/fotogaleria2.php');  ?>
            </div>
        </div>
        <div class="container p-0">
            <?php include_once('../../partes/tiempo-extra.php');  ?>
        </div>
        <?php include_once('../../partes/cultura.php');  ?>
        <?php include_once('../../partes/deportes.php');  ?>
        <?php include_once('../../partes/espectaculos.php');  ?>
        <?php include_once('../../partes/historieta.php');  ?>
        <div class="container mt-3">
            <div class="line-height-0">
                <div class="separator m-0"></div>
            </div>
            <div class="ta-context blue-border">
                <?php include('../../partes/contratapa.php');  ?>
            </div>
        </div>
        <div class="container mt-3">
            <div class="line-height-0">
                <div class="separator m-0"></div>
            </div>
            <div class="ta-context blue-border talleres-especial">
                <?php include('../../partes/talleres.php');  ?>
            </div>
        </div>
        <div class="container-fluid container-lg p-0 ">
            <div class="ta-context yellow-border  mt-3">
                <?php include_once('../../partes/seamos-socios-fullwidth.php');  ?>
            </div>
        </div>
        <?php include_once('../../partes/footer.php');  ?>
    </div>
    </div>

</body>

</html>