<?php

/**
 * An object that can serve as a platform for video/rich content services
 */
interface Videolist_PlatformInterface {
    /**
     * Get the type of the platform wrapper (e.g. "youtube", "oembed")
	 * @abstract
     * @return string
     */
    public function getType();

    /**
     * Attempt to parse a URL to see if this platform accepts it
	 * @abstract
     * @param string $url
     * @return array empty if this platform does not recognize the URL
     */
    public function parseUrl($url);

    /**
     * Get any remaining attribute data from the platform that was not already present from the URL parsing
	 * @abstract
     * @param array $parsed the output of parseUrl()
     * @return array must contain keys "title" and "description"
     */
    public function getData($parsed);
}
