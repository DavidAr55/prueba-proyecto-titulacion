<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">

    <title>Visual Studio Code | Test</title>

    <style>
        #editor {
            margin: auto;
            min-height: 500px;
            width: 95%;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.23.0/min/vs/loader.js"></script>
    <script>
        require.config({ paths: { 'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.23.0/min/vs' }});

        function initializeEditor() {
            require(['vs/editor/editor.main'], function() {
                var editor = monaco.editor.create(document.getElementById('editor'), {
                    value: [
                        '#include <iostream>',
                        'using namespace std;',
                        '',
                        'int main() {',
                        '    cout << "Hola, mundo!" << endl;',
                        '    return 0;',
                        '}'
                    ].join('\n'),
                    language: 'cpp',
                    theme: 'vs-dark'
                });

                // Función para enviar el código al backend
                function sendCodeToBackend() {
                    var code = editor.getValue();
                    fetch('backend.php', {
                        method: 'POST',
                        body: JSON.stringify({ code: code }),
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('output').innerText = data;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                }

                document.getElementById('submitButton').addEventListener('click', sendCodeToBackend);
            });
        }
    </script>
</head>
<body onload="initializeEditor()">
    <div class="container-task">
        <h2>Genera tu primera funcion</h2>
        <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Voluptas, quod. Ab libero numquam, qui adipisci impedit similique consectetur eaque perferendis. Animi accusantium qui quo iusto tempora commodi esse. Veritatis, corrupti.</p>
    </div>
    <div id="editor"></div>
    <button id="submitButton">Enviar Código</button>
    <div id="output"></div>
</body>
</html>
