<?php
include '../header.php';
require_once '../database_connection.php';
require_once '../auth.php';

// Получение списка навыков
try {
    $stmt = $pdo->prepare("SELECT * FROM skills");
    $stmt->execute();
    $skills = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Ошибка при получении списка навыков: " . $e->getMessage();
}

// Получение количества моделей для каждого навыка
try {
    $skill_counts = [];
    foreach ($skills as $skill) {
        $stmt = $pdo->prepare("SELECT COUNT(model_id) AS model_count FROM model_skills WHERE skill_id = ?");
        $stmt->execute([$skill['id']]);
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        $skill_counts[$skill['id']] = $count['model_count'];
    }
} catch (PDOException $e) {
    echo "Ошибка при получении количества моделей для навыков: " . $e->getMessage();
}
?>

<body class="bg-dark text-light">
<div class="container">
    <h3 class="text-light my-3">Список навыков</h3>
    <?php if (isset($_GET['message'])) {
        $message = $_GET['message'];
        if ($message === 'create_success') {
            echo '<div class="alert alert-success mt-3" role="alert">Навык успешно добавлен.</div>';
        } elseif ($message === 'edit_success') {
            echo '<div class="alert alert-success mt-3" role="alert">Навык успешно обновлен.</div>';
        } elseif ($message === 'delete_success') {
            echo '<div class="alert alert-success mt-3" role="alert">Навык успешно удален.</div>';
        } elseif ($message === 'delete_error') {
            echo '<div class="alert alert-danger mt-3" role="alert">Ошибка при удалении навыка.</div>';
        }
    } elseif (isset($_GET['error'])) {
        $error = $_GET['error'];
        if ($error === 'delete_error') {
            echo '<div class="alert alert-danger mt-3" role="alert">Ошибка: Нельзя удалить навык, так как существуют связанные записи в других таблицах.</div>';
        }
    } ?>
    <a href="create_skill.php" class="btn btn-success mb-3">Добавить навык</a>
    <table class="table table-striped table-dark">
        <thead>
        <tr>
            <th>Название навыка</th>
            <th>Количество моделей</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
    <?php foreach ($skills as $skill): ?>
            <tr>
                <td><?php echo $skill['skill_name']; ?></td>
                <td><?php echo isset($skill_counts[$skill['id']]) ? $skill_counts[$skill['id']] : 0; ?></td>
                <td>
                    <div class="btn-group" role="group">
                        <form method="GET" action="edit_skill.php" class="mx-2">
                            <input type="hidden" name="skill_id" value="<?php echo $skill['id']; ?>">
                            <button type="submit" class="btn btn-primary">Редактировать</button>
                        </form>
                        <form method="POST" action="delete_skill.php" onsubmit="return confirm('Вы уверены, что хотите удалить этот навык?');">
                            <input type="hidden" name="skill_id" value="<?php echo $skill['id']; ?>">
                            <button type="submit" class="btn btn-danger">Удалить</button>
                        </form>
                    </div>
                </td>
            </tr>
    <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
