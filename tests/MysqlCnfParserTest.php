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

}
