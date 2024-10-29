<?php
/*
Plugin Name: List of references
Plugin URI: http://32leaves.net
Description: Generates a customizable list of references. Simply put [lor] where you want your list of references and title attributes on your links
Version: 1.0
Author: Christian Weichel
Author URI: http://32leaves.net
*/

$FORMAT = <<<EOF
	<li><a name="{uid}{id}"></a>[{id}] {title}: <a href="{href}">{href}</a></li>
EOF;

class ListOfReferencesGenerator {
	
	private $_text;
	private $_id;
	
	public function __construct($text) {
		$this->_text = $text;
		$this->_id = rand();
	}
	
	/**
	 * losely taken from: http://snippets.dzone.com/posts/show/222
	 */
	private function buildLinkRe() {
		# Zero or more whitespace characters
		$S0 = '\s*';
		# One or more whitespace characters
		$S1 = '\s+';
		# Anchor tag start
		$anch1 = '<a' . $S1;
		# href= pattern
		$href1 = 'href' . $S0 . '=' . $S0;
		# title= pattern
		$title1 = 'title' . $S0 . '=' . $S0;
		# quoted strings, with selection
		$q1 = "'[^']'";
		$q2 = '"[^"]*"';
		$q = "($q1|$q2)";
		# full link pattern
		return "#$anch1$href1$q$S1$title1$q$S0>\s*(.*?)</a>#i";
	}		

	private function parseLinks() {
		$link_RE = $this->buildLinkRe();	  
		preg_match_all($link_RE, $this->_text, $matches);
		return $matches; // returns an array	
	}

	private function prepareReferencesList() {
		global $FORMAT;
		$list_of_urls = array();
		$parsed_links = $this->parseLinks();
		$id = 0;
		for($i = 0; $i < count($parsed_links[0]); $i += 1) {
			$id += 1;
			$href = str_replace('"', '', $parsed_links[1][$i]);
			$title = str_replace('"', '', $parsed_links[2][$i]);
			$list_of_urls[$href] = array($title, $id);
		}
		$this->list_of_urls = $list_of_urls;
		
		$result = "<ul>";
		foreach($list_of_urls as $href => $e) {
			$title = $e[0];
			$id = $e[1];
			$result .= str_replace("{uid}", strval($this->_id + $id),
					   str_replace("{id}", $id,
					   str_replace("{title}", $title,
					   str_replace("{href}", $href, $FORMAT))));
		}
		$result .= "</ul>";
		
		return $result;
	}

	public function replace_link_callback($matches) {
		$e = $this->list_of_urls[str_replace('"', '', $matches[1])];
		return "$matches[0] <a href=\"#".($this->_id + $e[1])."\">[".$e[1]."]</a>";
	}

	public function process() {
		if(!$this->isEnabled()) return $this->_text;

		$list = $this->prepareReferencesList();
		$link_RE = $this->buildLinkRe();
		$result = preg_replace_callback($link_RE, array(&$this, 'replace_link_callback'), $this->_text);
		$result = str_replace("[lor]", $list, $result);
		
		return $result;
	}
	
	private function isEnabled() {
		return strpos($this->_text, '[lor]') !== false;
	}
	
}

function filter_list_of_references($text) {
	$processor = new ListOfReferencesGenerator($text);
	return $processor->process();
}

add_filter('the_content', 'filter_list_of_references');
