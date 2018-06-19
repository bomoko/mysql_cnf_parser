<?php

use PHPUnit\Framework\TestCase;
use bomoko\MysqlCnfParser\MysqlCnfParser;

class MysqlCnfParserTest extends TestCase
{

    public function testParseCnfWithoutIncludes()
    {
        $output = MysqlCnfParser::parse(__DIR__ . "/assets/cnfWithoutIncludes.cnf");
        $this->assertTrue(is_array($output));
        $this->assertArrayHasKey("client", $output);
    }

    public function testParseCndWithIncludeFile()
    {
        $output = MysqlCnfParser::parse(__DIR__ . "/assets/cnfWithIncludeFile.cnf");
        $this->assertArrayHasKey("included", $output);
    }

    public function testParseCndWithIncludeDirectory()
    {
        $output = MysqlCnfParser::parse(__DIR__ . "/assets/cnfIncludesDirectory.cnf");
        $this->assertArrayHasKey("included", $output);
    }

    public function testParseCndWithIncludeDirectoryWithMultipleFiles()
    {
        $output = MysqlCnfParser::parse(__DIR__ . "/assets/cnfIncludesDirectory.cnf");
        $this->assertArrayHasKey("included", $output);
        $this->assertArrayHasKey("includedmore", $output["included"]);
        $this->assertArrayHasKey("includedisini", $output["included"]);
    }

    public function testDealWithSelfReferentialIncludes()
    {
        $output = MysqlCnfParser::parse(__DIR__ . "/assets/cnfInfiniteInclude.cnf");
        $this->assertArrayHasKey("infinite", $output);
    }

}
