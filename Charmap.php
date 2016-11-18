<?php
declare(strict_types=1);

class Charmap {
	public $characters = array();

	public function __construct(string $fontPath) {
		$this->parseFontFile($fontPath);
	}

	private function parseFontFile($path) {
		$file = file_get_contents($path, true);
		// font files use UNIX line ends
		$lines = explode("\n", $file);
		$charArr = array();

		foreach($lines as $ind => $line) {
			$chars = str_split($line);
			// store charArray and create a new array for next character
			// if '&' is the first character
			if ($chars[0] == "&") {
				array_push($this->characters, $charArr);
				$charArr = array();
				// ignore everything after
				continue;
			}

			$resStr = "";
			$ignNextChar = false;

			foreach($chars as $char) {
				if (!$ignNextChar) {
					if ($char == '"') {
						break;
					} else if ($char == '\\') {
						$ignNextChar = true;
						continue;
					}
				} else {
					$ignNextChar = false;
				}
				$resStr .= $char;	
			}

			if (!empty($resStr)) {
				array_push($charArr, $resStr);
			}
		}
	}
}
