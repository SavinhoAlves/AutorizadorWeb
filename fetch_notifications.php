<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "amigohmdb";

// Cria conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica conexão
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Consulta para buscar notificações não lidas
    $sql = "SELECT id, message, CASE WHEN is_read = 1 THEN 'sim' ELSE 'não' END as is_read FROM notifications WHERE user_id = 1";  // Substitua 1 pelo ID do usuário atual
    $result = $conn->query($sql);

    $notifications = [];

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $notifications[] = $row;
        }
    }

    // Retorna as notificações como JSON
    echo json_encode($notifications);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];

        $sql = "UPDATE notifications SET is_read = TRUE WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo "Notificação marcada como lida.";
        } else {
            echo "Erro ao marcar notificação como lida: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>
