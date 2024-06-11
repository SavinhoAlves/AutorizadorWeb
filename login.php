<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <?php
                if (isset($_GET['error'])) {
                    $error_message = '';
                    switch ($_GET['error']) {
                        case 'empty_fields':
                            $error_message = 'Please fill in all fields.';
                            break;
                        case 'invalid_credentials':
                            $error_message = 'Invalid username or password.';
                            break;
                        case 'user_not_found':
                            $error_message = 'User not found.';
                            break;
                        case 'database_error':
                            $error_message = 'A database error occurred. Please try again later.';
                            break;
                    }
                    if ($error_message) {
                        echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                                $error_message
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                              </div>";
                    }
                }
                ?>
                <form action="" method="POST" class="card p-4 shadow">
                    <h2 class="mb-4 text-center">Login</h2>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username:</label>
                        <input type="text" id="username" name="username" class="form-control" value="Admin" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" id="password" name="password" class="form-control" value="admin" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
</body>
</html>

<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verificar se os campos não estão vazios
    if (empty($username) || empty($password)) {
        header("Location: login.php?error=empty_fields");
        exit();
    }

    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Verificar se o usuário existe
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verificar a senha
            if (password_verify($password, $user['password'])) {
                // Senha correta, iniciar a sessão
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Redirecionar com base no papel do usuário
                if ($user['role'] == 'admin') {
                    header("Location: index.php");
                } else if ($user['role'] == 'authorizer') {
                    header("Location: authorizer_dashboard.php");
                } else {
                    header("Location: user_dashboard.php");
                }
                exit();
            } else {
                // Senha incorreta
                header("Location: login.php?error=invalid_credentials");
                exit();
            }
        } else {
            // Usuário não encontrado
            header("Location: login.php?error=user_not_found");
            exit();
        }
    } catch (PDOException $e) {
        // Tratamento de erro
        error_log("Erro ao autenticar o usuário: " . $e->getMessage());
        header("Location: login.php?error=database_error");
        exit();
    }
}
?>
