<?php
declare(strict_types=1);
require "Libraries/CLI_Helper/CLI_Helper.php";
require "Charmap.php";
require "BannerCreator.php";

class Bannerp {
	const VALID_OPTS = "c:f:hut:";
	const VALID_COLORS = array(
		"default", "black", "white", "red", "green", 
		"yellow", "blue", "magenta", "cyan");
	const CHARMAP_DIR = "Charmaps/";
	private $options = array(
		"color" => "default",
		"file" => self::CHARMAP_DIR . "default.bpc",
	);
	private $arguments = array(
		"txt" => "Bannerp works!"
	);

	public function __construct($givenOptions, $givenArgs) {
		$this->validateArgs($givenOptions, $givenArgs);

		$charm = new Charmap($this->options["file"]);
		$bc = new BannerCreator($charm);

		$bnrStrArr = $bc->createBanner($this->arguments["txt"]);
		// padding to prompt
		print PHP_EOL;
		// use ANSI control sequences to produce colors
		switch($this->options["color"]) {
			case "black"	: print "\e[30m"; break;
			case "white"	: print "\e[97m"; break;
			case "red"		: print "\e[31m"; break;
			case "green"	: print "\e[32m"; break;
			case "yellow"	: print "\e[33m"; break;
			case "blue"		: print "\e[34m"; break;
			case "magenta"	: print "\e[35m"; break;
			case "cyan"		: print "\e[36m"; break;
		}

		foreach($bnrStrArr as $line) {
			CLI_Helper::printn($line);
		}
		// clear set colors
		print "\e[0m";
	}

	private function validateArgs(array $opts, array $args) {
		if(empty($opts) && empty($args)) return;

		foreach($opts as $opt => $optval) {
			switch($opt) {
				case "c":
					if (!in_array($optval, self::VALID_COLORS)) {
						CLI_Helper::printn("Invalid color " . $opts["c"] . ",");
						$this->printValidColors();
						die();
					}
					$this->options["color"] = $optval;
					break;
				case "f":
					$optval = str_replace("\\", "/", $optval);
					if (file_exists(__DIR__ . "/" . self::CHARMAP_DIR . $optval)) {
						$path = self::CHARMAP_DIR . $optval;
					} else if (file_exists($optval)) {
						$path = $optval;
					} else die("Can't find font file: " . $optval);

					$this->options["file"] = $path;
					break;
				case "h":
				case "u":
					$this->printUsage();
					$this->printValidColors();
					die();
					break;
				case "t":
					$this->arguments["txt"] = $optval;
					break;
			}
		}
	}

	private function printValidColors() {
		CLI_Helper::printn("valid colors are:");
		foreach(self::VALID_COLORS as $c) {
			print $c . " ";
		}
		print PHP_EOL;
	}

	private function printUsage() {
		$scriptName = basename(__FILE__);
		CLI_Helper::printn("Usage " . $scriptName . " [-c color] [-f font path] [-t bannertext]");
	}
}

new Bannerp(getopt(Bannerp::VALID_OPTS), $argv);
