<?php
include '../header.php';
// Проверка аутентификации
require_once '../auth.php';
// Подключение к базе данных
require_once '../database_connection.php';


// Получение списка пользователей
try {
	$stmt = $pdo->prepare("SELECT * FROM users");
	$stmt->execute();
	$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
	echo "Ошибка при получении списка пользователей: " . $e->getMessage();
}
?>

<body class="bg-dark text-light">
<div class="container">
	<?php
	if (isset($_GET['message'])) {
		$message = $_GET['message'];

		if ($message === 'edit_success') {
			echo '<div class="alert alert-success mt-3" role="alert">Пользователь успешно обновлен.</div>';
		}

		if ($message === 'delete_success') {
			echo '<div class="alert alert-success mt-3" role="alert">Пользователь успешно удален.</div>';
		}

		if ($message === 'delete_error') {
			echo '<div class="alert alert-danger mt-3" role="alert">Ошибка удаления пользователя.</div>';
		}
	}
	?>
	<h3 class="text-light my-3">Список пользователей</h3>
	<table class="table table-striped table-dark">
		<thead>
		<tr>
			<th>Email</th>
			<th>Имя</th>
			<th>Дата рождения</th>
			<th>Номер телефона</th>
			<th>Дата регистрации</th>
			<th>Администратор</th>
			<th>Действия</th>
		</tr>
		</thead>
		<tbody>
	<?php foreach ($users as $user): ?>
			<tr>
				<td><?php echo $user['email']; ?></td>
				<td><?php echo $user['full_name']; ?></td>
				<td><?php echo $user['date_of_birth']; ?></td>
				<td><?php echo $user['phone_number']; ?></td>
				<td><?php echo $user['registration_date']; ?></td>
				<td>
			<?php if ($user['user_id'] !== $_SESSION['user_id']): ?>
							<div class="form-check form-switch">
								<input class="form-check-input" type="checkbox" id="adminSwitch<?php echo $user['user_id']; ?>" <?php echo $user['is_admin'] ? 'checked' : ''; ?> onchange="toggleAdmin(<?php echo $user['user_id']; ?>, this.checked)">
							</div>
			<?php else: ?>
				<?php echo $user['is_admin'] ? 'Администратор' : 'Не администратор'; ?>
			<?php endif; ?>
				</td>
				<td>
			<?php if ($user['user_id'] !== $_SESSION['user_id']): ?>
							<div class="btn-group" role="group">
								<!-- Форма удаления пользователя -->
								<form method="POST" action="delete_user.php" onsubmit="return confirm('Вы уверены, что хотите удалить этого пользователя?');">
									<input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
									<input type="submit" class="btn btn-danger" value="Удалить">
								</form>
							</div>
			<?php endif; ?>
				</td>
			</tr>
	<?php endforeach; ?>
		</tbody>
	</table>
</div>

<script>
    function toggleAdmin(userId, isAdmin) {
        fetch('toggle_admin.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                user_id: userId,
                is_admin: isAdmin ? 1 : 0
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Роль пользователя успешно обновлена.');
                } else {
                    alert('Ошибка при обновлении роли пользователя.');
                }
            })
            .catch(error => console.error('Ошибка:', error));
    }
</script>
</body>
</html>
