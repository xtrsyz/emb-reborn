<?php

/**
 * PDO Wrapper
 *
 * Simple PDO Wrapper
 * Based on PDO Wrapper by PHP Delusions
 * https://phpdelusions.net/pdo/pdo_wrapper
 *
 * @package    PDO Wrapper
 * @author     errorisme <errorisme@gmail.com>
 * @copyright  2022 (c) EmbuhTeam
 * @license    Free
 * @version    1.0.2
 * @modified   2023-09-19
 */
class Database {
	public $pdo, $stmt;

	function __construct($dsn, $dbuser = null, $dbpassword = null, $options = []) {
		$default_options = [
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES => false,
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		];
		$options = array_replace($default_options, $options);
		try {
			$this->pdo = new PDO($dsn, $dbuser, $dbpassword, $options);
		} catch (PDOException $e) {
			die("Database error: $dsn");
		}
	}
	public function run($sql, $args = null) {
		if (!$args)
			return $this->pdo->query($sql);
		$this->stmt = $this->pdo->prepare($sql);
		$this->stmt->execute($args);
		return $this->stmt;
	}
	public function rows($sql, $args = null) {
		return $this->run($sql, $args)->fetchAll();
	}
	public function row($sql, $args = null) {
		return $this->run($sql, $args)->fetch();
	}
	public function col($sql, $args = null) {
		return $this->run($sql, $args)->fetchColumn();
	}
	public function exec($sql) {
		return $this->pdo->exec($sql);
	}
}
