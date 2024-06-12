<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../database/db.php';

class LogUpdateController {

    public static function getLogUpdates() {
        global $pdo;

        $stmt = $pdo->prepare('SELECT * FROM log_update');
        $stmt->execute();

        $logUpdates = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $logUpdates;
    }

    public static function getLogUpdateById($id) {
        global $pdo;

        $stmt = $pdo->prepare('SELECT * FROM log_update WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function createLogUpdate($data) {
        global $pdo;

        // Consultar tabela estabelecimento para obter cod_estabelecimento
        $stmt = $pdo->prepare('SELECT cod_estabelecimento FROM estabelecimento WHERE cnpj = :cnpj AND hash = :hash');
        $stmt->bindParam(':cnpj', $data['cnpj']);
        $stmt->bindParam(':hash', $data['hash']);
        $stmt->execute();

        $estabelecimento = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($estabelecimento) {
            $cod_estabelecimento = $estabelecimento['cod_estabelecimento'];

            // Inserir log no banco de dados
            $stmt = $pdo->prepare('INSERT INTO log_update (estabelecimento, tipo, content, created_at) VALUES (:estabelecimento, :tipo, :content, :created_at)');
            $stmt->execute([
                ':estabelecimento' => $cod_estabelecimento,
                ':tipo' => $data['tipo'],
                ':content' => $data['content'],
                ':created_at' => $data['created_at']
            ]);

            return ['id' => $pdo->lastInsertId()];
        } else {
            http_response_code(404);
            return ['error' => 'Estabelecimento não encontrado'];
        }
    }

    public static function updateLogUpdate($id, $data) {
        global $pdo;

        $stmt = $pdo->prepare('UPDATE log_update SET estabelecimento = :estabelecimento, tipo = :tipo, content = :content, created_at = :created_at WHERE id = :id');
        $result = $stmt->execute([
            ':estabelecimento' => $data['estabelecimento'],
            ':tipo' => $data['tipo'],
            ':content' => $data['content'],
            ':created_at' => $data['created_at'],
            ':id' => $id
        ]);

        return ['success' => $result];
    }

    public static function deleteLogUpdate($id) {
        global $pdo;

        $stmt = $pdo->prepare('DELETE FROM log_update WHERE id = :id');
        $result = $stmt->execute([':id' => $id]);

        return ['success' => $result];
    }
}
?>
