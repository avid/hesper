<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Core\Form\Filter;

use Hesper\Core\Base\Singleton;

/**
 * Class RussianTypograph
 * @see http://www.artlebedev.ru/tools/typograf/
 * @package Hesper\Core\Form\Filter
 */
final class RussianTypograph extends BaseFilter
{
	const MAGIC_DELIMITER = '<>'; // brilliant!

	private static $symbols =
		array(
			' '		=> ' ', // bovm
			' < '	=> ' &lt; ',
			' > '	=> ' &gt; ',
			'…'		=> '&hellip;',
			'...'	=> '&hellip;',
			'™'		=> '&trade;',
			'(tm)'	=> '&trade;',
			'(TM)'	=> '&trade;',
			'©'		=> '&copy;',
			'(c)'	=> '&copy;',
			'(C)'	=> '&copy;',
			'№'		=> '&#8470;',
			'—'		=> '&mdash;',
			'–'		=> '&mdash;',
			'«'		=> '&laquo;',
			'»'		=> '&raquo;',
			'„'		=> '&bdquo;',
			'“'		=> '&ldquo;',
			'•'		=> '&bull;',
			'®'		=> '&reg;',
			'¼'		=> '&frac14;',
			'½'		=> '&frac12;',
			'¾'		=> '&frac34;',
			'±'		=> '&plusmn;',
			'+/-'	=> '&plusmn;',
			'!='	=> '&ne;',
			'<>'	=> '&ne;',

			// just to avoid regexp's
			' 1/4'	=> ' &frac14;',
			' 1/2'	=> ' &frac12;',
			' 3/4'	=> ' &frac34;',
			'1/4 '	=> '&frac14; ',
			'1/2 '	=> '&frac12; ',
			'3/4 '	=> '&frac34; '
		);

	private static $from = array(
		'~\-{2,}~',							// --
		'~([\w\pL\pP]+)\s+\-\s+~u',			// foo - bar
		'~(\s)\s*~u',						// n -> 2 whitespaces to process short strings (bar to a foo)
		'~([\s\pP]|^)([\w\pL]{1,2})\s~Uu',	// bar a foo | bar to a foo
		'~(&nbsp;|\s)\s+~u',				// compress whitespaces
		'~\"([^\s]*)\"~',					// "quote"
		'~\"([^\s]*)\s+([^\s\.]*)\"~',		// "quote quote"
	);

	private static $to = array(
		'-',
		'$1&nbsp;&#151; ',
		'$1$1',
		'$1$2&nbsp;',
		'$1',
		'&laquo;$1&raquo;',
		'&laquo;$1 $2&raquo;',
	);

	private $replaces = null;

	/**
	 * @return RussianTypograph
	**/
	public static function me() {
		return Singleton::getInstance(__CLASS__);
	}

	public function apply($value) {
		if (!$value = trim(strtr($value, self::$symbols)))
			return null;

		$list =
			preg_split(
				'~([^<>]*)(?![^<]*?>)~',
				$value,
				null,
				PREG_SPLIT_DELIM_CAPTURE
					| PREG_SPLIT_NO_EMPTY
					| PREG_SPLIT_OFFSET_CAPTURE
			);

		$tags = array();
		$text = null;

		foreach ($list as $row) {
			$string = $row[0];
			if (
				(strpos($string, '<') === false)
				&& (strpos($string, '>') === false)
			) {
				$text .= $string;
			} else {
				$tags[] = $string;
				$text .= self::MAGIC_DELIMITER;
			}
		}

		$text = $this->typographize($text);

		if ($tags) {
			$i = 0;
			$out = null;

			foreach (explode(self::MAGIC_DELIMITER, $text) as $chunk) {
				$out .= $chunk;

				if (isset($tags[$i]))
					$out .= $tags[$i++];
			}

			return $out;
		}

		return CompressWhitespaceFilter::me()->apply($text);
	}

	/**
	 * Remove using this method when php 5.3 will no more supported by onPHP
	 * @param $text
	 * @return mixed
	 */
	public function innerQuotes_php53($text) {
		return $this->innerQuotes($text);
	}

	private function typographize($text) {
		if (mb_strlen($text) < 2)
			return $text;

		$text = preg_replace(self::$from, self::$to, stripslashes($text));
		foreach ($this->getCbReplaces() as $pattern => $callback) {
			$text = preg_replace_callback($pattern, $callback, $text);
		}
		return $text;
	}

	private function innerQuotes($text) {
		return
			preg_replace(
				array(
					'~&laquo;(.*)&raquo;~U',
					'~\"(.*)\"~U',
				),
				'&#132;$1&#147;',
				stripslashes($text)
			);
	}

	private function getCbReplaces() {
		if ($this->replaces === null) {
			$self = $this;
			$this->replaces = [
				// "qu"o"te" => '\'&laquo;\'.$this->innerQuotes(\'$1\').\'&raquo;\''
				'~&laquo;(.*)&raquo;~' => function ($matches) use ($self) {
					return '&laquo;' . $self->innerQuotes_php53($matches[1]) . '&raquo;';
				},
				'~\"(.*)\"~' => function ($matches) use ($self) {
					return '&laquo;' . $self->innerQuotes_php53($matches[1]) . '&raquo;';
				},
				// rock'n'roll => 'str_replace("\'", \'&#146;\', \'$1\')'
				'~([\w\pL\']+)~u' => function ($matches) {
					return str_replace("'", '&#146;', $matches[1]);
				},
			];
		}
		return $this->replaces;
	}
}
