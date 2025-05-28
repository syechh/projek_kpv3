<?php

session_start();

if(!isset($_SESSION["ssLoginPOS"])){
  header("location: ../auth/login.php");
  exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Barcode</title>
</head>
<body>
    <?php

    $jmlCetak = $_GET['jmlCetak'];
    for ($i = 1; $i <= $jmlCetak ; $i++) { ?>
        <div style="text-align: center; width: 210px; float: left; margin-right: 30px; margin-bottom: 30px;">
            <?php
            
            $barcode2 = $_GET['barcode'];

            require '../assets/barcodeGenerator/vendor/autoload.php';

            $barcode = (new Picqer\Barcode\Types\TypeCode128())->getBarcode($barcode2);
            $renderer = new Picqer\Barcode\Renderers\PngRenderer();
            echo '<img src="data:image/png;base64,' . base64_encode($renderer->render($barcode, $barcode->getWidth() * 2)) . '" width="200px">';
            ?>
            <div><?= $barcode2 ?></div>
        </div>
    <?php
    }
    ?>

    <script>
        window.print();
    </script>
</body>
</html>
