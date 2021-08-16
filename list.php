<?php

require_once ('Db.php');

try {
	$dbh = new Db;
	$list = $dbh->list();
} catch (PDOException $e) {
	returnError( $e->getCode() . ', ' . $e->getMessage());
}

$records = '';

foreach ($list as $item) {
	$attach_data = 'Отсутствуют';
	if (!empty($item['data'])) {
		$attachements = json_decode($item['data']);
		if (json_last_error() === JSON_ERROR_NONE && count($attachements)) {
			$attach_data = '<ul>';
			$attach_dir = '/uploads/' . $item['id'] . '/';
			foreach($attachements as $attachement) {
				$link = $attach_dir . $attachement;
				$attach_data .= sprintf('<li><a href="%s" download="">%s</a></li>', $link, $attachement);
			}
			$attach_data .= '</ul>';
		}
	}
	$records .= sprintf('<tr><th>%d</th><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>',
		$item['id'], $item['name'], $item['email'], $item['phone'], $attach_data);
}

echo '<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Список записей</title>
    <link rel="stylesheet" href="/src/bulma.min.css">
    <link rel="stylesheet" href="/src/style.css">
</head>
<body>
<div class="section">

    <div class="block">
        <div class="buttons">
        	<a class="button is-light" href="/">
                Добавить запись
            </a>
            <a class="button is-primary" href="/list.php">
                <strong>Список записей</strong>
            </a>
        </div>
    </div>

    <div class="container">
        <table class="table is-fullwidth is-striped is-hoverable">
            <thead>
            <tr>
                <th>#</th>
                <th>Имя</th>
                <th>Email</th>
                <th>Телефон</th>
                <th>Вложения</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th>#</th>
                <th>Имя</th>
                <th>Email</th>
                <th>Телефон</th>
                <th>Вложения</th>
            </tr>
            </tfoot>
            <tbody>';

echo $records;

echo '</tbody>
        </table>
    </div>
</div>

</body>
</html>';
