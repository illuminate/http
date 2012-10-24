<?php

use Mockery as m;
use Illuminate\Http\Request;

class RequestTest extends PHPUnit_Framework_TestCase {

	public function tearDown()
	{
		m::close();
	}


	public function testIpMethod()
	{
		$request = Request::create('/', 'GET', array(), array(), array(), array('REMOTE_ADDR', '127.0.0.2'));
		$this->assertEquals('127.0.0.2', $request->ip('1.2.3.4'));
	}


	public function testHasMethod()
	{
		$request = Request::create('/', 'GET', array('name' => 'Taylor'));
		$this->assertTrue($request->has('name'));
		$this->assertFalse($request->has('foo'));
	}


	public function testInputMethod()
	{
		$request = Request::create('/', 'GET', array('name' => 'Taylor'));
		$this->assertEquals('Taylor', $request->input('name'));
		$this->assertEquals('Bob', $request->input('foo', 'Bob'));
	}


	public function testOnlyMethod()
	{
		$request = Request::create('/', 'GET', array('name' => 'Taylor', 'age' => 25));
		$this->assertEquals(array('age' => 25), $request->only('age'));
		$this->assertEquals(array('name' => 'Taylor', 'age' => 25), $request->only('name', 'age'));
	}


	public function testExceptMethod()
	{
		$request = Request::create('/', 'GET', array('name' => 'Taylor', 'age' => 25));
		$this->assertEquals(array('name' => 'Taylor'), $request->except('age'));
		$this->assertEquals(array(), $request->except('age', 'name'));
	}


	public function testQueryMethod()
	{
		$request = Request::create('/', 'GET', array('name' => 'Taylor'));
		$this->assertEquals('Taylor', $request->query('name'));
		$this->assertEquals('Bob', $request->query('foo', 'Bob'));
	}


	public function testCookieMethod()
	{
		$request = Request::create('/', 'GET', array(), array('name' => 'Taylor'));
		$this->assertEquals('Taylor', $request->cookie('name'));
		$this->assertEquals('Bob', $request->cookie('foo', 'Bob'));
	}


	public function testFileMethod()
	{
		$files = array(
			'foo' => array(
				'size' => 500,
				'name' => 'foo.jpg',
				'tmp_name' => __FILE__,
				'type' => 'blah',
				'error' => null,
			),
		);
		$request = Request::create('/', 'GET', array(), array(), $files);
		$this->assertInstanceOf('Symfony\Component\HttpFoundation\File\UploadedFile', $request->file('foo'));
	}


	public function testHasFileMethod()
	{
		$request = Request::create('/', 'GET', array(), array(), array());
		$this->assertFalse($request->hasFile('foo'));

		$files = array(
			'foo' => array(
				'size' => 500,
				'name' => 'foo.jpg',
				'tmp_name' => __FILE__,
				'type' => 'blah',
				'error' => null,
			),
		);
		$request = Request::create('/', 'GET', array(), array(), $files);
		$this->assertTrue($request->hasFile('foo'));
	}


	public function testServerMethod()
	{
		$request = Request::create('/', 'GET', array(), array(), array(), array('foo' => 'bar'));
		$this->assertEquals('bar', $request->server('foo'));
		$this->assertEquals('bar', $request->server('foo.doesnt.exist', 'bar'));
	}


	public function testMergeMethod()
	{
		$request = Request::create('/', 'GET', array('name' => 'Taylor'));
		$merge = array('buddy' => 'Dayle');
		$request->merge($merge);
		$this->assertEquals('Taylor', $request->input('name'));
		$this->assertEquals('Dayle', $request->input('buddy'));
	}


	public function testReplaceMethod()
	{
		$request = Request::create('/', 'GET', array('name' => 'Taylor'));
		$replace = array('buddy' => 'Dayle');
		$request->replace($replace);
		$this->assertNull($request->input('name'));
		$this->assertEquals('Dayle', $request->input('buddy'));
	}


	public function testHeaderMethod()
	{
		$request = Request::create('/', 'GET', array(), array(), array(), array('HTTP_DO_THIS' => 'foo'));
		$this->assertEquals('foo', $request->header('do-this'));
	}


	public function testJSONMethod()
	{
		$request = Request::create('/', 'GET', array(), array(), array(), array(), json_encode(array('taylor' => 'name')));
		$json = $request->json();
		$this->assertEquals('name', $json->taylor);
	}



	public function testAllInputReturnsInputAndFiles()
	{
		$file = $this->getMock('Symfony\Component\HttpFoundation\File\UploadedFile', null, array(__FILE__, 'photo.jpg'));
		$request = Request::create('/', 'GET', array('foo' => 'bar'), array(), array('baz' => $file));
		$this->assertEquals(array('foo' => 'bar', 'baz' => $file), $request->everything());
	}


	public function testOldMethodCallsSession()
	{
		$request = Request::create('/', 'GET');
		$session = m::mock('Illuminate\Session\Store');
		$session->shouldReceive('getOldInput')->once()->with('foo', 'bar')->andReturn('boom');
		$request->setSessionStore($session);
		$this->assertEquals('boom', $request->old('foo', 'bar'));
	}

}