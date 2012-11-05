<?php namespace Illuminate\Http;

use Illuminate\Support\JsonableInterface;

class Response extends \Symfony\Component\HttpFoundation\Response {

	/**
	 * The original content of the response.
	 *
	 * @var mixed
	 */
	protected $originalContent;

	/**
	 * Set the content on the response.
	 *
	 * @param  mixed  $content
	 * @return void
	 */
	public function setContent($content)
	{
		$this->originalContent = $content;

		// If the content is "JSONable" we will set the appropriate header and convert
		// the content to JSON. This is useful when returning something like models
		// from routes that will be automatically transformed to their JSON form.
		if ($content instanceof JsonableInterface)
		{
			$this->headers->set('Content-Type', 'application/json');

			$content = $content->toJson();
		}

		return parent::setContent($content);
	}

	/**
	 * Get the original response content.
	 *
	 * @return mixed
	 */
	public function getOriginalContent()
	{
		return $this->originalContent;
	}

}