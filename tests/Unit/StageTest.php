<?php

namespace tests\Unit;

//use PHPUnit\Framework\TestCase;
use tests\TestCase;

class StageTest extends TestCase
{
    /** A basic unit test example.*/
     
    public function testExample()
    {
    	$response = $this->get('/org/stages');

        $response->assertStatus(200);
       // $this->assertTrue(true);
    }
}
