<?php

interface Videolist_OembedInterface {
	/**
	 * @param string $itemUrl
	 * @return array
	 */
	public function fetchData($itemUrl);
}
