<?php session_start(); ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.2/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.2/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="style.css">
    
    <title>Prueba compilador web</title>
</head>
<body>

    <?php require_once "alertas.php"; ?>

    <div class="container-IDE">
        <form action="backend-test.php" method="POST">
            <h1>Our Visual Studio Code <select><option value="cpp">C++</option></select></h1>
            <textarea name="code" id="editor"><?php echo (isset($_SESSION['code']) ? $_SESSION['code'] : ''); ?></textarea>
            <button type="submit">Compilar codigo</button>
        </form>
    </div>
    <div class="container-TERMINAL">
        <p>prueba/temp></p>
        <p><?php echo (isset($_SESSION['output']) ? $_SESSION['output'] : '') ?></p>
    </div>

    <script src="main.js"></script>
</body>
</html>