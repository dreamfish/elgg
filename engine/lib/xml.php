<?php
	/**
	 * Elgg XML library.
	 * Contains functions for generating and parsing XML.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	/**
	 * @class XmlElement
	 * A class representing an XML element for import.
	 */
	class XmlElement 
	{
		/** The name of the element */
		public $name;
		
		/** The attributes */
		public $attributes;
		
		/** CData */
		public $content;
		
		/** Child elements */
		public $children;
	};
	
	/**
	 * This function serialises an object recursively into an XML representation.
	 * The function attempts to call $data->export() which expects a stdClass in return, otherwise it will attempt to
	 * get the object variables using get_object_vars (which will only return public variables!)
	 * @param $data object The object to serialise.
	 * @param $n int Level, only used for recursion.
	 * @return string The serialised XML output.
	 */
	function serialise_object_to_xml($data, $name = "", $n = 0)
	{
		$classname = ($name=="" ? get_class($data) : $name);
		
		$vars = method_exists($data, "export") ? get_object_vars($data->export()) : get_object_vars($data); 
		
		$output = "";
		
		if (($n==0) || ( is_object($data) && !($data instanceof stdClass))) $output = "<$classname>";

		foreach ($vars as $key => $value)
		{
			$output .= "<$key type=\"".gettype($value)."\">";
			
			if (is_object($value))
				$output .= serialise_object_to_xml($value, $key, $n+1);
			else if (is_array($value))
				$output .= serialise_array_to_xml($value, $n+1);
			else
				$output .= htmlentities($value);
			
			$output .= "</$key>\n";
		}
		
		if (($n==0) || ( is_object($data) && !($data instanceof stdClass))) $output .= "</$classname>\n";
		
		return $output;
	}

	/**
	 * Serialise an array.
	 *
	 * @param array $data
	 * @param int $n Used for recursion
	 * @return string
	 */
	function serialise_array_to_xml(array $data, $n = 0)
	{
		$output = "";
		
		if ($n==0) $output = "<array>\n";
		
		foreach ($data as $key => $value)
		{
			$item = "array_item";
			
			if (is_numeric($key))
				$output .= "<$item name=\"$key\" type=\"".gettype($value)."\">";
			else
			{
				$item = $key;
				$output .= "<$item type=\"".gettype($value)."\">";
			}
			
			if (is_object($value))
				$output .= serialise_object_to_xml($value, "", $n+1);
			else if (is_array($value))
				$output .= serialise_array_to_xml($value, $n+1);
			else
				$output .= htmlentities($value);
			
			$output .= "</$item>\n";
		}
		
		if ($n==0) $output = "</array>\n";
		
		return $output;
	}
	
	/**
	 * Parse an XML file into an object.
	 * Based on code from http://de.php.net/manual/en/function.xml-parse-into-struct.php by
	 * efredricksen at gmail dot com
	 * 
	 * @param string $xml The XML.
	 */
	function xml_2_object($xml)
	{
		$parser = xml_parser_create();
		
		// Parse $xml into a structure
		xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);	
		xml_parse_into_struct($parser, $xml, $tags);
		
 		xml_parser_free($parser);
 		
 		$elements = array(); 
  		$stack = array();
  		
  		foreach ($tags as $tag)
  		{
			$index = count($elements);
			
  			if ($tag['type'] == "complete" || $tag['type'] == "open") 
  			{
      			$elements[$index] = new XmlElement;
      			$elements[$index]->name = $tag['tag'];
      			$elements[$index]->attributes = $tag['attributes'];
      			$elements[$index]->content = $tag['value'];
      			
      			if ($tag['type'] == "open") 
      			{ 
        			$elements[$index]->children = array();
        			$stack[count($stack)] = &$elements;
        			$elements = &$elements[$index]->children;
      			}
    		}
    		
    		if ($tag['type'] == "close") 
    		{
      			$elements = &$stack[count($stack) - 1];
      			unset($stack[count($stack) - 1]);
    		}
  		}
  		
  		return $elements[0];
	}
	
?>