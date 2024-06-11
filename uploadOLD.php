<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once("config.php");

// Incluir o autoloader do Composer
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

echo "Arquivo enviado:<br>";

if (!empty($_FILES['file']['tmp_name'])) {
    var_dump($_FILES['file']); // Verifica o conteúdo de $_FILES

    try {
        // Identifica o tipo de arquivo
        $fileType = IOFactory::identify($_FILES['file']['tmp_name']);
        // Cria o leitor apropriado para o tipo de arquivo
        $reader = IOFactory::createReader($fileType);
        $spreadsheet = $reader->load($_FILES['file']['tmp_name']);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        $primeira_linha = true;

        foreach ($rows as $row) {
            if ($primeira_linha == false) {
                $matricula = $row[0];
                echo "Matricula: $matricula <br>";

                $cpf = $row[1];
                echo "CPF: $cpf <br>";

                $nome = $row[2];
                echo "Nome: $nome <br>";

                $data_nascimento = $row[3];
                echo "Data de Nascimento: $data_nascimento <br>";

                $idade = $row[4];
                echo "Idade: $idade <br>";

                $titular_dependente = $row[5];
                echo "Titular/Dependente: $titular_dependente <br>";

                $ativo = $row[6];
                echo "Ativo: $ativo <br>";

                // Inserir os dados na tabela
                $stmt = $conn->prepare("INSERT INTO beneficiarios (matricula, cpf, nome, data_nascimento, idade, titular_dependente, ativo) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssss", $matricula, $cpf, $nome, $data_nascimento, $idade, $titular_dependente, $ativo);
                $stmt->execute();
                if ($stmt->error) {
                    var_dump($stmt->error);
                }
                $stmt->close();
            }
            $primeira_linha = false;
        }
    } catch (Exception $e) {
        echo 'Erro ao processar o arquivo: ',  $e->getMessage();
    }
} else {
    echo "Nenhum arquivo enviado.";
}
?>
