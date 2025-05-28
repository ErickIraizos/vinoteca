<?php

$controllers = [
    'ResenaController.php',
    'ProductoController.php',
    'PerfilController.php',
    'PedidosController.php',
    'PedidoController.php',
    'Controller.php',
    'CheckoutController.php',
    'CarritoController.php'
];

$replacements = [
    "'user_id'" => "'usuario_id'",
    "\"user_id\"" => "\"usuario_id\"",
    '$_SESSION[\'user_id\']' => '$_SESSION[\'usuario_id\']',
    '$_SESSION["user_id"]' => '$_SESSION["usuario_id"]',
    "'user_nombre'" => "'nombre'",
    "\"user_nombre\"" => "\"nombre\"",
    '$_SESSION[\'user_nombre\']' => '$_SESSION[\'nombre\']',
    '$_SESSION["user_nombre"]' => '$_SESSION["nombre"]',
    "'user_email'" => "'email'",
    "\"user_email\"" => "\"email\"",
    '$_SESSION[\'user_email\']' => '$_SESSION[\'email\']',
    '$_SESSION["user_email"]' => '$_SESSION["email"]',
    "'user_role'" => "'rol'",
    "\"user_role\"" => "\"rol\"",
    '$_SESSION[\'user_role\']' => '$_SESSION[\'rol\']',
    '$_SESSION["user_role"]' => '$_SESSION["rol"]'
];

foreach ($controllers as $controller) {
    $file = __DIR__ . '/../controllers/' . $controller;
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $newContent = str_replace(
            array_keys($replacements),
            array_values($replacements),
            $content
        );
        file_put_contents($file, $newContent);
        echo "Updated $controller\n";
    } else {
        echo "File not found: $controller\n";
    }
}

echo "Session variable names updated successfully!\n"; 