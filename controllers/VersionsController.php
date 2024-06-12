<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../database/db.php';

class VersionsController {

    public static function getVersions() {
        global $pdo;

        $stmt = $pdo->prepare('SELECT * FROM versions');
        $stmt->execute();

        $versions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $versions;
    }

    public static function getVersionById($version) {
        global $pdo;

        $stmt = $pdo->prepare('SELECT * FROM versions WHERE version = :version');
        $stmt->bindParam(':version', $version);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function createVersion($data) {
        global $pdo;

        $stmt = $pdo->prepare('INSERT INTO versions (module, version, hash, url, status, created_at) VALUES (:module, :version, :hash, :url, :status, :created_at)');
        $stmt->execute([
            ':module' => $data['module'],
            ':version' => $data['version'],
            ':hash' => $data['hash'],
            ':url' => $data['url'],
            ':status' => $data['status'],
            ':created_at' => $data['created_at']
        ]);

        return ['id' => $pdo->lastInsertId()];
    }

    public static function updateVersion($id, $data) {
        global $pdo;

        $stmt = $pdo->prepare('UPDATE versions SET module = :module, version = :version, hash = :hash, url = :url, status = :status, created_at = :created_at WHERE id = :id');
        $result = $stmt->execute([
            ':module' => $data['module'],
            ':version' => $data['version'],
            ':hash' => $data['hash'],
            ':url' => $data['url'],
            ':status' => $data['status'],
            ':created_at' => $data['created_at'],
            ':id' => $id
        ]);

        return ['success' => $result];
    }

    public static function deleteVersion($id) {
        global $pdo;

        $stmt = $pdo->prepare('DELETE FROM versions WHERE id = :id');
        $result = $stmt->execute([':id' => $id]);

        return ['success' => $result];
    }
}
?>
