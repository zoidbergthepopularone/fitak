<?php

use Nette\Utils\Strings;


class Tags extends BaseModel
{

	/**
	 * @param string $input
	 * @return NULL[]|string[] [NULL|string $rawTags, NULL|string $content]
	 */
	public function separateMessage($input)
	{
		$match = Strings::match($input, '/^\s*(?<tag_list>\[\s*[\pL\d._-]+\s*\](?:\s*(?&tag_list))?)(?<message>.*)$/us');
		if (!$match)
		{
			return [NULL, $input];
		}
		return [$match['tag_list'], $match['message']];
	}

	/**
	 * @param string $input raw tags
	 * @return string[][] [string[] $cleanTags, string[] $originalTags]
	 */
	public function parse($input)
	{
		if (!$input)
		{
			return [[], []];
		}

		$tags = Strings::trim($input, '[] ');
		$tags = Strings::split($tags, '/\]\s*\[/');
		return [array_map('Nette\Utils\Strings::webalize', $tags), $tags];
	}

	// extract tags ([tag1][tag2]....) from the start of input
	// return array(cleanTags, originalTags)
	public function extractTags($input)
	{
		list($tags) = $this->separateMessage($input);
		return $this->parse($tags);
	}

}
