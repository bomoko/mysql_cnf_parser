<?php

namespace bomoko\MysqlCnfParser;

class MysqlCnfParser
{

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
            $name = $names[1];
            if ($name[0] !== "/") {
                $name = $includePath . "/{$name}";
            }
            if (file_exists($name) && file_exists($name)) {
                $return = array_merge_recursive($return, self::parse($name));
            }
        }
        return $return;
    }

}
