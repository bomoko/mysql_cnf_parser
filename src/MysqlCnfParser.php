<?php

namespace bomoko\MysqlCnfParser;

class MysqlCnfParser {
	
	public static function parse($filename)
  {
		if(is_file($filename) && is_readable($filename)) {
			$contents = file_get_contents($filename);
			$contentArray = explode("\n", $contents);
			//go through the file and pop any "!include/!includedir" directives
			$toParse = [];
			$includes = [];
			foreach($contentArray as $line) {
				if(strstr(trim($line), "!include")) {
					$includes[] = $line;
				} else {
					$toParse[] = $line;
				}
			}
			
		return parse_ini_string(implode("\n", $toParse), TRUE);

    }

  }

}
