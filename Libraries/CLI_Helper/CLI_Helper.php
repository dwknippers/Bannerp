<?php
declare(strict_types=1);

class CLI_Helper {
	public static function addnl(string $string): string {
		return $string . PHP_EOL;
	}
	
	public static function printn(string $string) {
		print self::addnl($string);
	}
}

