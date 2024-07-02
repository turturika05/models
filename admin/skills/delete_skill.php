<?php
require_once '../database_connection.php';
require_once '../auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['skill_id'])) {
    $skill_id = $_POST['skill_id'];

    try {
        // Проверяем, есть ли связанные записи в таблице model_skills
        $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM model_skills WHERE skill_id = ?");
        $stmt_check->execute([$skill_id]);
        $count = $stmt_check->fetchColumn();

        if ($count > 0) {
            // Если есть связанные записи, передаем сообщение об ошибке через параметр в URL
            header('Location: index.php?error=delete_error');
            exit();
        }

        // Иначе, удаляем запись из таблицы skills
        $stmt = $pdo->prepare("DELETE FROM skills WHERE id = ?");
        $stmt->execute([$skill_id]);
        header('Location: index.php?message=delete_success');
        exit();
    } catch (PDOException $e) {
        // При ошибке выводим сообщение об ошибке на текущей странице, а не делаем редирект
        echo "Ошибка при удалении навыка: " . $e->getMessage();
        exit();
    }
} else {
    // Если это не POST запрос, делаем редирект на index.php
    header('Location: index.php');
    exit();
}
?>
