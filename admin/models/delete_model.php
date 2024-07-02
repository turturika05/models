<?php
require_once '../database_connection.php';
require_once '../auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['model_id'])) {
    $model_id = $_POST['model_id'];

    try {
        // Начать транзакцию
        $pdo->beginTransaction();

        // Удаление записей из таблицы photoshoot_models
        $stmt = $pdo->prepare("DELETE FROM photoshoot_models WHERE model_id = ?");
        $stmt->execute([$model_id]);

        // Удаление записей из таблицы contracts
        $stmt = $pdo->prepare("DELETE FROM contracts WHERE model_id = ?");
        $stmt->execute([$model_id]);

        // Удаление записей из таблицы model_skills
        $stmt = $pdo->prepare("DELETE FROM model_skills WHERE model_id = ?");
        $stmt->execute([$model_id]);

        // Удаление фотографий модели
        $stmt = $pdo->prepare("SELECT file_path FROM model_photos WHERE model_id = ?");
        $stmt->execute([$model_id]);
        $photos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($photos as $photo) {
            if (file_exists($photo['file_path'])) {
                unlink($photo['file_path']);
            }
        }

        $stmt = $pdo->prepare("DELETE FROM model_photos WHERE model_id = ?");
        $stmt->execute([$model_id]);

        // Удаление модели
        $stmt = $pdo->prepare("DELETE FROM models WHERE id = ?");
        $stmt->execute([$model_id]);

        // Завершить транзакцию
        $pdo->commit();
        header('Location: index.php?message=delete_success');
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Ошибка при удалении модели: " . $e->getMessage();
    }
} else {
    header('Location: index.php');
    exit();
}
?>
