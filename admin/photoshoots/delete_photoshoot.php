<?php
include '../header.php';
require_once '../database_connection.php';
require_once '../auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['photoshoot_id'])) {
	$photoshoot_id = $_POST['photoshoot_id'];

	try {
		$pdo->beginTransaction();

		// Удаление связей с моделями (photoshoot_models)
		$stmt_delete_photoshoot_models = $pdo->prepare("DELETE FROM photoshoot_models WHERE photoshoot_id = ?");
		$stmt_delete_photoshoot_models->execute([$photoshoot_id]);

		// Удаление фотографий из базы данных и файловой системы (photoshoot_photos)
		$stmt_select_photos = $pdo->prepare("SELECT id, file_path FROM photoshoot_photos WHERE photoshoot_id = ?");
		$stmt_select_photos->execute([$photoshoot_id]);
		$photos = $stmt_select_photos->fetchAll(PDO::FETCH_ASSOC);

		$stmt_delete_photos = $pdo->prepare("DELETE FROM photoshoot_photos WHERE photoshoot_id = ?");
		$stmt_delete_photos->execute([$photoshoot_id]);

		foreach ($photos as $photo) {
			$file_path = $photo['file_path'];
			if (file_exists($file_path)) {
				unlink($file_path); // Удаление файла фотографии из файловой системы
			}
		}

		// Удаление фотосессии из основной таблицы (photoshoots)
		$stmt_delete_photoshoot = $pdo->prepare("DELETE FROM photoshoots WHERE id = ?");
		$stmt_delete_photoshoot->execute([$photoshoot_id]);

		$pdo->commit();

		header('Location: index.php?message=delete_success');
		exit();
	} catch (PDOException $e) {
		$pdo->rollBack();
		echo "Ошибка при удалении фотосессии: " . $e->getMessage();
	}
} else {
	header('Location: index.php'); // Перенаправление в случае ошибки или если данные не были отправлены методом POST
	exit();
}
?>
