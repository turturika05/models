<?php
require_once '../database_connection.php'; // Подключение к базе данных

if (isset($_GET['casting_id'])) {
	$casting_id = $_GET['casting_id'];

	try {
		// Подготовка SQL запроса для получения заявок с данными пользователей
		$stmt = $pdo->prepare("
            SELECT ca.*, u.email, u.phone_number, u.full_name
            FROM casting_applications ca
            LEFT JOIN users u ON ca.user_id = u.user_id
            WHERE ca.casting_id = :casting_id
        ");
		$stmt->bindParam(':casting_id', $casting_id, PDO::PARAM_INT);
		$stmt->execute();
		$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

		// Возвращаем заявки в формате JSON
		header('Content-Type: application/json');
		echo json_encode($applications);
	} catch (PDOException $e) {
		echo json_encode(['error' => 'Ошибка при загрузке заявок: ' . $e->getMessage()]);
	}
} else {
	echo json_encode(['error' => 'Не указан casting_id']);
}
?>
