<?php
// Configuración de la base de datos
$host = 'localhost';
$dbname = 'preguntas_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener preguntas frecuentes
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $stmt = $pdo->query("SELECT pregunta, respuesta FROM preguntas_frecuentes");
        $preguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($preguntas);
        exit();
    }

    // Agregar una pregunta no frecuente
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['pregunta'])) {
            $pregunta = $data['pregunta'];
            $stmt = $pdo->prepare("INSERT INTO preguntas_no_frecuentes (pregunta) VALUES (:pregunta)");
            $stmt->bindParam(':pregunta', $pregunta);
            $stmt->execute();
            echo json_encode(["message" => "Pregunta enviada para revisión"]);
            exit();
        }
    }
} catch (PDOException $e) {
    echo json_encode(["error" => "Error en la conexión: " . $e->getMessage()]);
}
?>
