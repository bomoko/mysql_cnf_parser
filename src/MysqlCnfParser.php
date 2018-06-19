<?php

namespace bomoko\MysqlCnfParser;
use Nette\Utils\Finder;

class MysqlCnfParser
{
  const FILE_TYPES = ['*.cnf', '*.ini'];

    public static function parse($filename)
    {
        if (is_file($filename) && is_readable($filename)) {
            $contents = file_get_contents($filename);
            $contentArray = explode("\n", $contents);
            //go through the file and pop any "!include/!includedir" directives
            $toParse = [];
            $includes = [];
            foreach ($contentArray as $line) {
                if (strstr(trim($line), "!include")) {
                    $includes[] = $line;
                } else {
                    $toParse[] = $line;
                }
            }

            return array_merge_recursive(
              parse_ini_string(implode("\n", $toParse), true),
              self::processIncludes($includes,
                realpath(pathinfo($filename, PATHINFO_DIRNAME))));
        }

    }

    protected static function processIncludes(Array $includes, $includePath)
    {
        $return = [];

        foreach ($includes as $include) {
            //strip any !include(s)
            $names = explode(" ", $include);
            $fileName = $names[1];
            $includeType = $names[0];

            $fileName = self::getAbsoluteDirectory($fileName, $includePath);

            $return = array_merge_recursive($return,
            $includeType == "!includedir" ? self::parseDirectory($fileName) : self::parse($fileName));

        }
        return $return;
    }

    protected static function getAbsoluteDirectory($fileName,$includePath)
    {
        if ($fileName[0] !== "/") {
            $fileName = $includePath . "/{$fileName}";
        }

        return $fileName;
    }

    protected static function parseDirectory($directoryName)
    {

      $return = [];

        foreach (Finder::findFiles(self::FILE_TYPES)->in($directoryName) as $key => $file) {
            $return = array_merge_recursive($return, self::parse($key));
        }

        return $return;
    }

}
