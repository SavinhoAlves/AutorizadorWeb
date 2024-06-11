<?php
include_once("config.php");

// Incluir o autoloader do Composer
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

session_start();

if (!empty($_FILES['arquivo']['tmp_name'])) {
    $fileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($_FILES['arquivo']['tmp_name']);
    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($fileType);
    $spreadsheet = $reader->load($_FILES['arquivo']['tmp_name']);
    $worksheet = $spreadsheet->getActiveSheet();
    $rows = $worksheet->toArray();

    $primeira_linha = true;
    $output = "";

    foreach ($rows as $row) {
        if (!$primeira_linha) {
            $matricula = $row[0];
            $output .= "Matricula: $matricula <br>";

            $cpf = $row[1];
            $output .= "CPF: $cpf <br>";

            $nome = $row[2];
            $output .= "Nome: $nome <br>";

            $data_nascimento = $row[3];
            $output .= "Data de Nascimento: $data_nascimento <br>";

            $idade = $row[4];
            $output .= "Idade: $idade <br>";

            $titular_dependente = $row[5];
            $output .= "Titular/Dependente: $titular_dependente <br>";

            $ativo = $row[6];
            $output .= "Ativo: $ativo <br>";

            $output .= "<hr>";

            $date = new DateTime($data_nascimento);
            $dateNascimento = $date->format('Y-m-d');

            // Inserir os dados na tabela
            $stmt = $conn->prepare("INSERT INTO beneficiarios (matricula, cpf, nome, data_nascimento, idade, titular_dependente, ativo) VALUES (:matricula, :cpf, :nome, :data_nascimento, :idade, :titular_dependente, :ativo)");
            $stmt->bindValue(':matricula', $matricula);
            $stmt->bindValue(':cpf', $cpf);
            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':data_nascimento', $dateNascimento);
            $stmt->bindValue(':idade', $idade);
            $stmt->bindValue(':titular_dependente', $titular_dependente);
            $stmt->bindValue(':ativo', $ativo);
            $stmt->execute();
            $stmt->closeCursor(); // Liberar os recursos associados à declaração
        }
        $primeira_linha = false;
    }
    
    // Armazenar a mensagem na sessão
    $_SESSION['import_message'] = "Importação concluída com sucesso. Redirecionando para a página inicial.";

    // Redirecionar para a página index.php após a importação
    header("Location: index.php");
    exit();
}
?>
