<?php

use Illuminate\Support\JsonableInterface;

class ResponseTest extends PHPUnit_Framework_TestCase {

	public function testJsonResponsesAreConvertedAndHeadersAreSet()
	{
		$response = new Illuminate\Http\Response(new JsonableStub);
		$this->assertEquals('foo', $response->getContent());
		$this->assertEquals('application/json', $response->headers->get('Content-Type'));
	}	

}

class JsonableStub implements JsonableInterface {
	public function toJson() { return 'foo'; }
}