<?php
require 'vendor/autoload.php'; // Carga Guzzle HTTP

// Verifica si se recibió un cuerpo de solicitud válido
$postData = file_get_contents("php://input");
if(isset($postData) && !empty($postData)){
    // Decodifica los datos JSON recibidos
    $data = json_decode($postData);

    // Verifica si se recibió el código
    if(isset($data->code) && !empty($data->code)){
        // Guarda el código en un archivo temporal
        $tempFile = tempnam(sys_get_temp_dir(), "temp-cpp-") . ".cpp";
        file_put_contents($tempFile, $data->code);

        // Compila el archivo y captura la salida
        $output = shell_exec("g++ $tempFile -o $tempFile.out 2>&1 && $tempFile.out");
        
        // Si hubo errores durante la compilación o ejecución
        if (strpos($output, "error") !== false) {
            // Sugerir correcciones de código usando la API de ChatGPT
            $prompt = "Mejora este código:";
            $completion = "Corrige los errores de compilación en el código siguiente:\n\n$data->code\n\nSugerencias:";
            $API_KEY = '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';
            
            $client = new GuzzleHttp\Client();
            $response = $client->post('https://api.openai.com/v1/completions', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $API_KEY,
                ],
                'json' => [
                    'model' => 'gpt-3.5-turbo-instruct',
                    'prompt' => 'Say this is a test',
                    'max_tokens' => 7,
                    'temperature' => 0
                ]
            ]);
            $completionResponse = json_decode($response->getBody()->getContents(), true);
            $output .= "\n" . $completionResponse['choices'][0]['text'];
        }

        echo "<pre>Output:\n" . htmlspecialchars($output) . "</pre>";

        // Elimina los archivos temporales
        unlink($tempFile);
        unlink("$tempFile.out");
    } else {
        echo "No se recibió ningún código válido.";
    }
} else {
    echo "No se recibió ningún dato.";
}
?>
