<?php

class Db {
	private $host = 'localhost';
	private $dbname = 'weisgauff';
	private $user = 'wpuser';
	private $password = '31415926';

	protected $pdo;

	public function __construct() {
		$this->pdo = new PDO( 'mysql:host=' . $this->host . ';dbname=' . $this->dbname,
			$this->user, $this->password,
			array( PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ) );
	}

	public function insert( $name, $phone, $email ) {
		$sql  = 'INSERT INTO `records` (`name`, `phone`, `email`) VALUES(?, ?, ?)';
		$stmt = $this->pdo->prepare( $sql );
		$stmt->execute( [ $name, $phone, $email ] );

		return $this->pdo->lastInsertId();
	}

	public function update( $id, $images_data ) {
		$images_data = json_encode($images_data);
		$sql  = 'UPDATE `records` SET `data` = ? WHERE `id` = ?';
		$stmt = $this->pdo->prepare( $sql );
		$stmt->execute( [ $images_data, $id ] );
	}

	public function list() {
		$sql  = 'SELECT * FROM records';
		$stmt = $this->pdo->prepare( $sql );
		$stmt->execute();

		return $stmt->fetchAll();
	}
}

function returnError( $text ) {
	echo json_encode( [ 'error' => $text ] );
	die();
}
