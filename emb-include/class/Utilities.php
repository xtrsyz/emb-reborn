<?php

/**
 * Utilities Class
 *
 * General purpose utilities pack.
 *
 * @package    EmbuhEngine
 * @author     errorisme <errorisme@gmail.com>
 * @copyright  2022 (c) EmbuhTeam
 * @license    Free
 * @version    1.1.2
 * @modified   2024-07-14
 */
class Utilities {
	const VERSION = '1.1.2';

	public static function fetch($method, $url, $fields = null, $options = []) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		switch(strtoupper($method)) {
			case 'GET' : break;
			case 'POST': curl_setopt($ch, CURLOPT_POST, true); break;
			default    : curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		}
		if($fields !== null)
			curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		foreach($options as $key => $value)
			curl_setopt($ch, $key, $value);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
	public static function slug($str, $options = []) {
		// url_slug by Sean Murphy <sean@iamseanmurphy.com>
		$defaults = ['delimiter' => '-', 'limit' => null, 'lowercase' => true, 'replacements' => []];
		$options = array_merge($defaults, $options);                                                // Merge options
		$str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());                     // Make sure string is in UTF-8 and strip invalid UTF-8 characters
		$str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);  // Make custom replacements
		$str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);                     // Replace non-alphanumeric characters with our delimiter
		$str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);  // Remove duplicate delimiters
		$str = mb_substr($str, 0, ($options['limit'] ?: mb_strlen($str, 'UTF-8')), 'UTF-8');        // Truncate slug to max. characters
		$str = trim($str, $options['delimiter']);                                                   // Remove delimiter from ends
		return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
	}
	public static function unslug($slug) {
		return str_replace('-', ' ', $slug);
	}
	public static function createURL($pattern, array $replacer = []) {
		return preg_replace_callback(
			array_map(
				function($v) {
					return '/%('.preg_quote($v).')%/';
				},
				array_keys($replacer)
			),
			function ($match) use ($replacer) {
				return isset($replacer[$match[1]]) && !is_array($replacer[$match[1]]) ? $replacer[$match[1]] : $match[1];
			},
			$pattern
		);
	}
	public static function buildURL($base_url, $params = []) {
		$url_params = http_build_query(array_filter($params));
		return $url_params ? "$base_url?$url_params" : "$base_url";
	}
	public static function seeded_shuffle(array &$items, $seed = null) {
		// https://stackoverflow.com/q/24262147/2110506
		$items = array_values($items);
		mt_srand(abs(crc32($seed)));
		for ($i = count($items) - 1; $i > 0; $i--) {
			$j = mt_rand(0, $i);
			list($items[$i], $items[$j]) = [$items[$j], $items[$i]];
		}
	}
	public static function seeded_unshuffle(array &$items, $seed = null) {
		// https://stackoverflow.com/q/24262147/2110506
		$items = array_values($items);
		mt_srand(abs(crc32($seed)));
		$indices = [];
		for ($i = count($items) - 1; $i > 0; $i--) {
			$indices[$i] = mt_rand(0, $i);
		}
		foreach (array_reverse($indices, true) as $i => $j) {
			list($items[$i], $items[$j]) = [$items[$j], $items[$i]];
		}
	}
	public static function str_seeded_shuffle($str, $seed = null) {
		$tmp_array = str_split((string) $str);
		self::seeded_shuffle($tmp_array, $seed);
		return implode('', $tmp_array);
	}
	public static function str_seeded_unshuffle($str, $seed = null) {
		$tmp_array = str_split((string) $str);
		self::seeded_unshuffle($tmp_array, $seed);
		return implode('', $tmp_array);
	}
	public static function to62($num, $base = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_') {
		return self::toBase($num, $base);
	}
	public static function toBase($num, $base = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_') {
		// https://stackoverflow.com/a/4964352
		$b = strlen($base);
		$r = (int)$num % $b;
		$res = $base[$r];
		$q = floor($num/$b);
		while ($q) {
			$r = $q % $b;
			$q = floor($q/$b);
			$res = $base[$r].$res;
		}
		return $res;
	}
	public static function to10($num, $base = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_') {
		// https://stackoverflow.com/a/4964352
		$b = strlen($base);
		$limit = strlen($num);
		$res = strpos($base, $num[0]);
		for($i=1; $i < $limit; $i++) {
			$res = $b * $res + strpos($base, $num[$i]);
		}
		return $res;
	}
	public static function encode($id, $b = 62) {
		$from = $b == 26 ? 'abcdefghijklmnopqrstuvwxyz' : substr('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_', 0, $b);
		$tmp = self::toBase($id, $from);
		$split = str_split($tmp, ceil(strlen($tmp)/2));
		$to = self::str_seeded_shuffle($from, isset($split[1]) ? $split[1] : '');
		$split[0] = strtr($split[0], $from, $to);
		return implode('', $split);
	}
	public static function decode($encoded, $b = 62) {
		$from = $b == 26 ? 'abcdefghijklmnopqrstuvwxyz' : substr('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_', 0, $b);
		$split = str_split($encoded, ceil(strlen($encoded)/2));
		$to = self::str_seeded_shuffle($from, isset($split[1]) ? $split[1] : '');
		$split[0] = strtr($split[0], $to, $from);
		$tmp = implode('', $split);
		return self::to10($tmp, $from);
	}
	public static function encrypt($id, $seed = null, $b = 62) {
		$from = $b == 26 ? 'abcdefghijklmnopqrstuvwxyz' : substr('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_', 0, $b);
		$tmp = self::toBase($id, self::str_seeded_shuffle($from, $seed));
		$split = str_split($tmp, ceil(strlen($tmp)/2));
		$to = self::str_seeded_shuffle($from, isset($split[1]) ? $split[1] : '');
		$split[0] = strtr($split[0], $from, $to);
		return implode('', $split);
	}
	public static function decrypt($encrypted, $seed = null, $b = 62) {
		$from = $b == 26 ? 'abcdefghijklmnopqrstuvwxyz' : substr('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_', 0, $b);
		$split = str_split($encrypted, ceil(strlen($encrypted)/2));
		$to = self::str_seeded_shuffle($from, isset($split[1]) ? $split[1] : '');
		$split[0] = strtr($split[0], $to, $from);
		$tmp = implode('', $split);
		return self::to10($tmp, self::str_seeded_shuffle($from, $seed));
	}
	public static function secureTransform($num, $base, $key = null) {
		return $key ? self::encrypt($num, $base, $key) : self::encode($num, $base);
	}
	public static function secureRevert($val, $base, $key = null) {
		return $key ? self::decrypt($val, $base, $key) : self::decode($val, $base);
	}
	/**
	 * Format bytes into B, KB, MB, GB, TB.
	 *
	 * @link   https://stackoverflow.com/a/2510459/2110506
	 * @since  1.0.3
	 * @param  integer $bytes     Bytes to format.
	 * @param  integer $precision (optional) Decimals.
	 * @return string
	 */
	static function formatBytes($bytes, $precision = 2) {
		$units = array('B', 'KB', 'MB', 'GB', 'TB');

		$bytes = max($bytes, 0);
		$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
		$pow = min($pow, count($units) - 1);

		// Uncomment one of the following alternatives
		$bytes /= pow(1024, $pow);
		// $bytes /= (1 << (10 * $pow));

		return round($bytes, $precision) . ' ' . $units[$pow];
	}
	/**
	 * Format number into K, M, B.
	 *
	 * @since  1.0.3
	 * @param  float   $n         Number to format.
	 * @param  integer $precision (optional) Decimals.
	 * @return string
	 */
	static function formatNumber($n, $precision = 2) {
		$n = (float) $n;
		if($n < 1000) {
			$n_format = number_format($n);
		} elseif($n < 1000000) {
			$n_format = number_format($n / 1000, $precision) . 'K';
		} else if ($n < 1000000000) {
			$n_format = number_format($n / 1000000, $precision) . 'M';
		} else {
			$n_format = number_format($n / 1000000000, $precision) . 'B';
		}
		return $n_format;
	}
	static function getRelativePath($from, $to) {
		// Remove trailing slashes from both paths
		$from = rtrim($from, DIRECTORY_SEPARATOR);
		$to = rtrim($to, DIRECTORY_SEPARATOR);
		if ($from === $to) {
			return '.';
		}
		// Split paths into arrays
		$fromArray = explode(DIRECTORY_SEPARATOR, $from);
		$toArray = explode(DIRECTORY_SEPARATOR, $to);

		// Remove common segments at the beginning of both paths
		while (count($fromArray) > 0 && count($toArray) > 0 && $fromArray[0] === $toArray[0]) {
			array_shift($fromArray);
			array_shift($toArray);
		}

		// Calculate number of remaining segments in $fromArray
		$upLevels = count($fromArray);

		// Construct relative path
		$relativePath = rtrim(str_repeat('..' . DIRECTORY_SEPARATOR, $upLevels) . implode(DIRECTORY_SEPARATOR, $toArray), DIRECTORY_SEPARATOR);

		return $relativePath;
	}

	// new method
	public static function mt_seed($seed = null) {
		mt_srand(abs(crc32($seed)));
	}
	public static function mt_shuffle(array &$items) {
		// https://stackoverflow.com/q/24262147/2110506
		$items = array_values($items);
		for ($i = count($items) - 1; $i > 0; $i--) {
			$j = mt_rand(0, $i);
			list($items[$i], $items[$j]) = [$items[$j], $items[$i]];
		}
	}
	public static function mt_unshuffle(array &$items) {
		// https://stackoverflow.com/q/24262147/2110506
		$items = array_values($items);
		$indices = [];
		for ($i = count($items) - 1; $i > 0; $i--) {
			$indices[$i] = mt_rand(0, $i);
		}
		foreach (array_reverse($indices, true) as $i => $j) {
			list($items[$i], $items[$j]) = [$items[$j], $items[$i]];
		}
	}
	public static function mt_str_shuffle($str) {
		$tmp_array = str_split((string) $str);
		self::mt_shuffle($tmp_array);
		return implode('', $tmp_array);
	}
	public static function mt_str_unshuffle($str) {
		$tmp_array = str_split((string) $str);
		self::mt_unshuffle($tmp_array);
		return implode('', $tmp_array);
	}
	/**
	* Checks if the current request is an XMLHttpRequest (XHR) or has the content type JSON.
	*
	* @since  1.1.1
	* @return bool True if the request is an XHR or has the content type JSON, false otherwise.
	*/
	public static function isXHRorJSON() {
		$isXHR  = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
		$isJSON = isset($_SERVER['CONTENT_TYPE']) && strtolower($_SERVER['CONTENT_TYPE']) === 'application/json';
		return ($isXHR || $isJSON);
	}
	public static function JSONResponse($data = [], $message = '', $error_code = null, $error_message = null, $options = []) {
		if($error_code && !$error_message) {
			switch($error_code) {
				case 400: $error_message = 'Bad request'; break;
				case 401: $error_message = 'Unauthorized'; break;
				case 403: $error_message = 'Forbidden'; break;
				case 404: $error_message = 'Not found'; break;
			}
		}
		$errors = array_filter(['code' => $error_code, 'message' => $error_message]);
		$response = ['success' => empty($errors), 'errors' => $errors, 'message' => $message, 'data' => $data];
		error_reporting(0);
		if($error_code) http_response_code($error_code);
		$headers = [
			'Cache-Control' => 'no-transform,public,max-age=300,s-maxage=900',
		];
		if(!empty($options['header']) && is_array($options['header']))
			$headers = array_merge($headers, $options['header']);
		foreach($headers as $name => $value) header("{$name}: {$value}");
		if(!empty($options['cors']))
			header('Access-Control-Allow-Origin: *');
		if(!empty($_GET['callback']) && empty($options['jsonp'])) {
			header('Content-Type: text/javascript; charset=utf-8');
			exit($_GET['callback'].'('.json_encode($response).');');
		} else {
			header('Content-Type: application/json; charset=utf-8');
			exit(json_encode($response));
		}
	}
	public static function getJSONPayload() {
		$data = json_decode(file_get_contents('php://input'), true);
		if (json_last_error() !== JSON_ERROR_NONE)
			return null;
		return $data;
	}

	/* Alias for backward compatibility */
	public static function encodeId($id) {
		return self::encode($id);
	}
	public static function decodeId($encoded) {
		return self::decode($encoded);
	}
	public static function encryptId($id, $seed = null) {
		return self::encrypt($id, $seed);
	}
	public static function decryptId($encrypted, $seed = null) {
		return self::decrypt($encrypted, $seed);
	}

}
