<?php

class Videolist_OembedPlatform implements Videolist_PlatformInterface
{
	protected $subtype;
	protected $pattern;
	protected $oembedService;
	protected $data = array();

	/**
	 * @param string $oembedSubtype
	 * @param string $parseUrlPattern parseUrl()'s argument must match this pattern to try the oembed service
	 * @param Videolist_OembedInterface $oembedService
	 * @throws InvalidArgumentException
	 */
	public function __construct($oembedSubtype, $parseUrlPattern, Videolist_OembedInterface $oembedService) {
		if (!is_string($oembedSubtype) || !preg_match('~^[a-zA-Z0-9_]+$~', $oembedSubtype)) {
			throw new InvalidArgumentException('$type must be a string using [a-zA-Z0-9_]');
		}
		$this->subtype = $oembedSubtype;
		if (!is_string($parseUrlPattern)) {
			throw new InvalidArgumentException('$pattern must be a PCRE regular expression');
		}
		$this->pattern = $parseUrlPattern;
		$this->oembedService = $oembedService;
	}

	public function getType()
	{
		return "oembed";
	}

	public function parseUrl($url)
	{
		if (preg_match($this->pattern, $url)) {
			// we have to test the oembed svc immediately
			if ($data = $this->fetchData($url)) {
				$this->data = $data;
				return $data;
			}
		}
		return false;
	}

	/**
	 * @param string $url
	 * @return array
	 */
	protected function fetchData($url) {
		$arr = $this->oembedService->fetchData($url);
		if (empty($arr['type'])
				|| !preg_match('~^(video|rich)$~', $arr['type'])
				|| empty($arr['html'])) {
			return array();
		}
		$ret = array(
			'video_id' => $url,
			'videotype' => 'oembed',
			'title' => '',
			'description' => '',
			'embed_html' => $arr['html'],
			'oembed_subtype' => $this->subtype,
		);
		if (!empty($arr['title'])) {
			$ret['title'] = $arr['title'];
		}
		if (!empty($arr['thumbnail_url'])) {
			$ret['thumbnail'] = $arr['thumbnail_url'];
		}
		if (!empty($arr['provider_name'])) {
			$ret['provider_name'] = $arr['provider_name'];
		}
		return $ret;
	}

	public function getData($parsed)
	{
		return $this->data;
	}
}
