<?php namespace Illuminate\Http;

class Response extends \Symfony\Component\HttpFoundation\Response {

	/**
	 * The original content of the response.
	 *
	 * @var mixed
	 */
	protected $originalContent;

	/**
	 * Set the contnet on the response.
	 *
	 * @param  mixed  $content
	 * @return void
	 */
	public function setContent($content)
	{
		$this->originalContent = $content;

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