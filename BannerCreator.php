<?php
declare(strict_types=1);

class BannerCreator {
	const OFFSET = 32;
	private $charm;

	public function __construct(Charmap $charm) {
		$this->charm = $charm;
	}

	public function createBanner(string $txt) {
		$charcodes = array();
		foreach(str_split($txt) as $c) {
			// -32 offset because font files start after ASCII control characters
			array_push($charcodes, ord($c) - self::OFFSET);
		}
		// determine font height and check if all characters in $txt are supported by font
		$highest = 0;
		$lastCharIndex = count($this->charm->characters) - 1;
		foreach($charcodes as $cc) {
			if ($cc < 0 || $cc > $lastCharIndex) {
				die("Given text contains character: " . chr($cc + self::OFFSET) . " which is not supported by used font");
			}
			$height = count($this->charm->characters[$cc]);
			if ($height > $highest) {
				$highest = $height;
			}
					}
		$sentenceArr = array();

		for ($i = 0; $i < $highest; $i++) {
			foreach($charcodes as $cc) {
				// characters can have variable height
				if (array_key_exists($i, $this->charm->characters[$cc])) {
					// ignore illegal offset warnings
					@$sentenceArr[$i] .= $this->charm->characters[$cc][$i];
				}
			}
		}

		return $sentenceArr;
	}
}
