<?php
session_start();

// Verifica si se ha enviado el formulario con el código
if(isset($_POST['code'])){
    // Obtiene el código enviado por el usuario
    $code = $_POST['code'];

    // Directorio donde se guardarán los archivos temporales y logs
    $directorio = './temp/';
    $log_directorio = './temp/logs/';

    // Nombre del archivo basado en la fecha y hora actual
    $nombre_archivo_cpp = $directorio . 'temp-' . date('Ymd-His') . '.cpp';
    $nombre_ejecutable = $directorio . 'temp-' . date('Ymd-His') . '.exe'; // Cambiado para sistema Windows
    $nombre_log = $log_directorio . 'temp-' . date('Ymd-His') . '.log';

    // Intenta abrir o crear el archivo
    $archivo_cpp = fopen($nombre_archivo_cpp, 'w');

    if ($archivo_cpp) {
        // Escribe el código en el archivo
        fwrite($archivo_cpp, $code);
        fclose($archivo_cpp);

        // Compila el archivo C++
        $comando_compilar = "g++ -o \"$nombre_ejecutable\" \"$nombre_archivo_cpp\" 2>&1"; // Cambiado para sistema Windows
        exec($comando_compilar, $output_compile, $return_var_compile);

        if ($return_var_compile !== 0) {
            // Si hay errores de compilación, los guarda en el archivo de log
            file_put_contents($nombre_log, implode("\n", $output_compile));
            $_SESSION['executeWarning'] = "Se encontraron errores durante la compilación. Consulte el archivo de log: $nombre_log";
            $_SESSION['code'] = $_POST["code"];
            $_SESSION['output'] = file_get_contents($nombre_log); // Establece $_SESSION['output'] con el contenido del archivo de log
            echo '<script> location.href = "index.php" </script>';
        } else {
            // Ejecuta el archivo ejecutable
            exec("\"$nombre_ejecutable\" 2>&1", $output_execution, $return_var_execution); // Cambiado para sistema Windows

            // Verifica si hubo errores durante la ejecución
            if ($return_var_execution !== 0) {
                // Si hay errores, los guarda en el archivo de log
                file_put_contents($nombre_log, implode("\n", $output_execution));
                $_SESSION['executeWarning'] = "Se encontraron errores durante la ejecución. Consulte el archivo de log: $nombre_log";
                $_SESSION['code'] = $_POST["code"];
                $_SESSION['output'] = file_get_contents($nombre_log); // Establece $_SESSION['output'] con el contenido del archivo de log
                echo '<script> location.href = "index.php" </script>';
            } else {
                // Si no hay errores, muestra la salida
                $_SESSION['executeSuccess'] = "Se compiló de manera correcta";
                $_SESSION['code'] = $_POST["code"];
                $_SESSION['output'] = "<pre>" . implode("\n", $output_execution) . "</pre>";
                echo '<script> location.href = "index.php" </script>';
            }
        }

        // Borra los archivos temporales y de log
        unlink($nombre_archivo_cpp);
        unlink($nombre_ejecutable);
        unlink($nombre_log);

    } else {
        echo "Error al intentar abrir o crear el archivo.";
    }
}
?>
