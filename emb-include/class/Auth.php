<?php

class Auth {
	protected $db, $session_key, $table_name, $userid_column_name, $username_column_name, $password_column_name;
	public $userid, $username;

	function __construct(Database $db, $options = []) {
		if(session_status() === PHP_SESSION_NONE) session_start();
		$this->session_key = isset($options['session_key']) ? $options['session_key'] : 'auth';
		$this->table_name  = isset($options['table_name'])  ? $options['table_name']  : 'user';
		$this->userid_column_name   = isset($options['userid_column_name'])   ? $options['userid_column_name'] : 'id';
		$this->username_column_name = isset($options['username_column_name']) ? $options['username_column_name'] : 'username';
		$this->password_column_name = isset($options['password_column_name']) ? $options['password_column_name'] : 'password';
		$this->userid   = !empty($_SESSION["{$this->session_key}_userid"])    ? $_SESSION["{$this->session_key}_userid"] : '';
		$this->username = !empty($_SESSION["{$this->session_key}_username"])  ? $_SESSION["{$this->session_key}_username"] : '';
		$this->db = $db;
	}
	public function check() {
		if(isset($_SESSION["{$this->session_key}_userid"]) && isset($_SESSION["{$this->session_key}_username"]) && $this->db->col("SELECT COUNT(*) FROM {$this->table_name} WHERE {$this->userid_column_name}=? AND {$this->username_column_name}=?", [(int)$_SESSION["{$this->session_key}_userid"], $_SESSION["{$this->session_key}_username"]]))
			return true;
		return false;
	}
	public function login($username, $password) {
		$user = $this->db->row("SELECT {$this->userid_column_name}, {$this->password_column_name} FROM {$this->table_name} WHERE {$this->username_column_name}=?", [$username]);
		if(empty($user) || !password_verify($password, $user[$this->password_column_name]))
			return false;
		$_SESSION["{$this->session_key}_userid"] = $this->userid = $user[$this->userid_column_name];
		$_SESSION["{$this->session_key}_username"] = $this->username = $username;
		return true;
	}
	public function logout() {
		unset($_SESSION["{$this->session_key}_userid"]);
		unset($_SESSION["{$this->session_key}_username"]);
	}
}
