<?php

class Videolist_OembedService implements Videolist_OembedInterface {

	protected $endpointUrl;
	protected $requestVars = array();
	protected $httpService;
	const URL_PATTERN = '~^https?\\://[^/]+/~';

	/**
	 * @param string $endpointUrl
	 * @param callable $httpService
	 */
	public function __construct($endpointUrl, $httpService = null) {
		$this->setEndpoint($endpointUrl);
		$this->setHttpService($httpService);
	}

	/**
	 * @param string|array $key
	 * @param string $value
	 * @return self
	 */
	public function addRequestVar($key, $value = '') {
		if (is_array($key)) {
			foreach ($key as $k => $v) {
				$this->addRequestVar($k, $v);
			}
		} else {
			$this->requestVars[$key] = (string) $value;
		}
		return $this;
	}

	/**
	 * @param string $url
	 * @return Videolist_OembedService
	 * @throws self
	 */
	public function setEndpoint($url) {
		if (!is_string($url) || !preg_match(self::URL_PATTERN, $url)) {
			throw new InvalidArgumentException('$url must be a URL');
		}
		$this->endpointUrl = $url;
		return $this;
	}

	/**
	 * Inject callable to handle HTTP requests (for unit testing)
	 * @param callable $httpService
	 * @return self
	 * @throws InvalidArgumentException
	 */
	public function setHttpService($httpService = null) {
		if (!$httpService) {
			$httpService = array('Videolist_OembedService', 'urlToArray');
		}
		if (!is_callable($httpService)) {
			throw new InvalidArgumentException('$func must be callable');
		}
		$this->httpService = $httpService;
		return $this;
	}

	/**
	 * @static
	 * @param string $url
	 * @return array
	 */
	static public function urlToArray($url) {
		if (($resp = file_get_contents($url)) && ($arr = json_decode($resp, true))) {
			return $arr;
		}
		return array();
	}

	/**
	 * @param string $itemUrl
	 * @return array
	 * @throws InvalidArgumentException
	 */
	public function fetchData($itemUrl) {
		if (!is_string($itemUrl) || !preg_match(self::URL_PATTERN, $itemUrl)) {
			throw new InvalidArgumentException('$itemUrl must be a URL');
		}
		$requestVars = array_merge($this->requestVars, array(
			'url' => $itemUrl,
			'format' => 'json',
		));
		$apiUrl = $this->endpointUrl;
		if (false === strpos($this->endpointUrl, '?')) {
			$apiUrl .= '?' . http_build_query($requestVars);
		} else {
			$apiUrl .= '&' . http_build_query($requestVars);
		}
		return call_user_func($this->httpService, $apiUrl);
	}
}
