<?php
require_once '../database_connection.php';
require_once '../auth.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$user_id = $input['user_id'];
$is_admin = $input['is_admin'];

if (isset($user_id) && isset($is_admin)) {
	// Проверка, чтобы пользователь не мог изменить свою собственную роль
	if ($user_id == $_SESSION['user_id']) {
		echo json_encode(['success' => false, 'error' => 'Вы не можете изменить свою собственную роль.']);
		exit();
	}

	try {
		$stmt = $pdo->prepare("UPDATE users SET is_admin = ? WHERE user_id = ?");
		$stmt->execute([$is_admin, $user_id]);
		echo json_encode(['success' => true]);
	} catch (PDOException $e) {
		echo json_encode(['success' => false, 'error' => $e->getMessage()]);
	}
} else {
	echo json_encode(['success' => false, 'error' => 'Invalid input']);
}
?>
