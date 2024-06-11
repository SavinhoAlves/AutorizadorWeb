<?php
// Verifica se os dados do formulário foram recebidos via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se os campos obrigatórios foram preenchidos
    if (isset($_POST['title']) && isset($_POST['message'])) {
        // Captura os valores dos campos do formulário
        $title = $_POST['title'];
        $message = $_POST['message'];
        $user_id = 1; // Defina o ID do usuário corretamente
        
        // Configurações do banco de dados
        $servername = "localhost";
        $username = "root"; // Substitua pelo seu nome de usuário do MySQL
        $password = ""; // Substitua pela sua senha do MySQL
        $dbname = "amigohmdb"; // Substitua pelo nome do seu banco de dados
        
        // Cria uma conexão com o banco de dados
        $conn = new mysqli($servername, $username, $password, $dbname);
        
        // Verifica se a conexão foi estabelecida com sucesso
        if ($conn->connect_error) {
            die("Erro ao conectar ao banco de dados: " . $conn->connect_error);
        }
        
        // Prepara e executa a query SQL para inserir a notificação no banco de dados
$sql = "INSERT INTO notifications (user_id, titulo, message, is_read, created_at) VALUES (?, ?, ?, 0, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $user_id, $title, $message);
        
        if ($stmt->execute()) {
            echo "Notificação cadastrada com sucesso!";
            header("refresh: 3; URL=index.php");
        } else {
            echo "Erro ao cadastrar notificação: " . $stmt->error;
        }
        
        // Fecha a conexão com o banco de dados
        $stmt->close();
        $conn->close();
    } else {
        echo "Por favor, preencha todos os campos do formulário.";
    }
} else {
    echo "Método de requisição inválido.";
}
?>
