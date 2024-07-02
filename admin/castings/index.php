<?php
include '../header.php';
require_once '../database_connection.php';
require_once '../auth.php';

// Получение списка кастингов с именами клиентов
try {
	$stmt = $pdo->prepare("
        SELECT castings.*, clients.name 
        FROM castings 
        LEFT JOIN clients ON castings.client_id = clients.id
    ");
	$stmt->execute();
	$castings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
	echo "Ошибка при получении списка кастингов: " . $e->getMessage();
}

// Получение заявок для каждого кастинга
try {
	$stmt_applications = $pdo->prepare("
        SELECT *
        FROM casting_applications
        WHERE casting_id = :casting_id
    ");
} catch (PDOException $e) {
	echo "Ошибка при получении списка заявок: " . $e->getMessage();
}
?>

<body class="bg-dark text-light">
<div class="container">
	<!-- Сообщения о состоянии операций -->
	<?php
	if (isset($_GET['message'])) {
		$message = $_GET['message'];

		if ($message === 'create_success') {
			echo '<div class="alert alert-success mt-3" role="alert">Кастинг успешно добавлен.</div>';
		} elseif ($message === 'edit_success') {
			echo '<div class="alert alert-success mt-3" role="alert">Кастинг успешно обновлен.</div>';
		} elseif ($message === 'delete_success') {
			echo '<div class="alert alert-success mt-3" role="alert">Кастинг успешно удален.</div>';
		} elseif ($message === 'delete_error') {
			echo '<div class="alert alert-danger mt-3" role="alert">Ошибка удаления кастинга.</div>';
		}
	}
	?>
	<h3 class="text-light my-3">Список кастингов</h3>
	<a href="create_casting.php" class="btn btn-success mb-3">Добавить кастинг</a>
	<table class="table table-striped table-dark">
		<thead>
		<tr>
			<th>Название</th>
			<th>Описание</th>
			<th>Дата кастинга</th>
			<th>Локация</th>
			<th>Клиент</th>
			<th>Действия</th>
		</tr>
		</thead>
		<tbody>
	<?php foreach ($castings as $casting): ?>
			<tr>
				<td><?php echo htmlspecialchars($casting['casting_name']); ?></td>
				<td><?php echo htmlspecialchars($casting['description']); ?></td>
				<td><?php echo htmlspecialchars($casting['casting_date']); ?></td>
				<td><?php echo htmlspecialchars($casting['location']); ?></td>
				<td><?php echo htmlspecialchars($casting['name'] ?? 'Нет данных'); ?></td>
				<td>
					<div class="btn-group" role="group">
						<form method="GET" action="edit_casting.php" class="mx-2">
							<input type="hidden" name="casting_id" value="<?php echo $casting['casting_id']; ?>">
							<input type="submit" class="btn btn-primary" value="Редактировать">
						</form>
						<form method="POST" action="delete_casting.php" onsubmit="return confirm('Вы уверены, что хотите удалить этот кастинг?');">
							<input type="hidden" name="casting_id" value="<?php echo $casting['casting_id']; ?>">
							<input type="submit" class="btn btn-danger" value="Удалить">
						</form>

						<button class="btn btn-info mx-2 show-applications-btn" data-casting-id="<?php echo $casting['casting_id']; ?>">
							Отобразить
						</button>

					</div>
				</td>
			</tr>
			<tr class="applications-row" id="applications_<?php echo $casting['casting_id']; ?>" style="display: none;">
				<td colspan="6">
					<table class="table table-striped">
						<thead>
						<tr>
							<th>Имя</th>
							<th>Телефон</th>
							<th>E-mail</th>
							<th>Сообщение</th>
							<th>Дата подачи</th>
						</tr>
						</thead>
						<tbody class="applications-table" id="applications_table_<?php echo $casting['casting_id']; ?>">
						<!-- Сюда будут динамически добавляться заявки -->
						</tbody>
					</table>
				</td>
			</tr>
	<?php endforeach; ?>
		</tbody>
	</table>
</div>

<script>
    // JavaScript для показа и скрытия заявок при клике на кнопку "Отобразить"
    document.addEventListener('DOMContentLoaded', function () {
        const showApplicationsBtns = document.querySelectorAll('.show-applications-btn');

        showApplicationsBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                const castingId = this.getAttribute('data-casting-id');
                const applicationsRow = document.getElementById('applications_' + castingId);

                if (applicationsRow.style.display === 'none') {
                    applicationsRow.style.display = 'table-row';
                    loadApplications(castingId);
                } else {
                    applicationsRow.style.display = 'none';
                }
            });
        });

        function loadApplications(castingId) {
            const applicationsTable = document.getElementById('applications_table_' + castingId);

            fetch('get_applications.php?casting_id=' + castingId)
                .then(response => response.json())
                .then(applications => {
                    applicationsTable.innerHTML = ''; // Очищаем таблицу перед добавлением новых данных

                    applications.forEach(application => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${application.full_name ?? 'Нет данных'}</td>
                            <td>${application.phone_number ?? 'Нет данных'}</td>
                            <td>${application.email ?? 'Нет данных'}</td>
                            <td>${application.message}</td>
                            <td>${application.application_date}</td>
                        `;
                        applicationsTable.appendChild(row);
                    });
                })
                .catch(error => console.error('Ошибка загрузки заявок:', error));
        }
    });
</script>

</body>
</html>