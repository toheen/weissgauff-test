<?php

require_once ('Db.php');

$input = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRIPPED);

if (!array_filter($input)) {
	returnError('Не заполнены поля формы');
}

if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
	returnError('Не электронная почта');
}

try {
	$dbh = new Db;
	$insert = $dbh->insert($input['name'], $input['phone'], $input['email']);
} catch (PDOException $e) {
	returnError( $e->getCode() . ', ' . $e->getMessage());
}

if (isset($_FILES)) {
	$filenames = [];
	$upload_dir = __DIR__ . DIRECTORY_SEPARATOR . 'uploads';
	if (!file_exists($upload_dir)) mkdir($upload_dir, 0700);
	$target_dir = $upload_dir . DIRECTORY_SEPARATOR . $insert;
	if (!file_exists($target_dir)) mkdir($target_dir, 0700);
	foreach($_FILES as $file) {
		if ($file['error'] == UPLOAD_ERR_OK) {
			$tmp_name = $file['tmp_name'];
			$upload_result = move_uploaded_file($tmp_name, $target_dir . DIRECTORY_SEPARATOR . $file['name']);
			if ($upload_result) $filenames[] = $file['name'];
		}
	}

	if ($filenames) {
		try {
			$dbh->update($insert, $filenames);
		} catch (PDOException $e) {
			returnError( $e->getCode() . ', ' . $e->getMessage());
		}
	}
}

echo json_encode(['success' => $insert]);
