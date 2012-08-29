<?php

/**
 * An object that can fetch data from a URL
 */
interface Videolist_OembedInterface {
	/**
	 * @param string $itemUrl
	 * @return array
	 */
	public function fetchData($itemUrl);
}
