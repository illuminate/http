<?php

use Mockery as m;
use Illuminate\Support\JsonableInterface;

class ResponseTest extends PHPUnit_Framework_TestCase {

	public function tearDown()
	{
		m::close();
	}


	public function testJsonResponsesAreConvertedAndHeadersAreSet()
	{
		$response = new Illuminate\Http\Response(new JsonableStub);
		$this->assertEquals('foo', $response->getContent());
		$this->assertEquals('application/json', $response->headers->get('Content-Type'));
	}


	public function testRenderablesAreRendered()
	{
		$mock = m::mock('Illuminate\Support\RenderableInterface');
		$mock->shouldReceive('render')->once()->andReturn('foo');
		$response = new Illuminate\Http\Response($mock);
		$this->assertEquals('foo', $response->getContent());		
	}

}

class JsonableStub implements JsonableInterface {
	public function toJson() { return 'foo'; }
}