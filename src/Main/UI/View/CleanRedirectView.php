<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Ivan Y. Khvostishkov
 */
namespace Hesper\Main\UI\View;

use Hesper\Main\Flow\Model;
use Hesper\Main\Net\Http\HeaderUtils;

/**
 * Class CleanRedirectView
 * @package Hesper\Main\UI\View
 */
class CleanRedirectView implements View {

	protected $url = null;

	public function __construct($url) {
		$this->url = $url;
	}

	/**
	 * @return CleanRedirectView
	 **/
	public static function create($url) {
		return new self($url);
	}

	public function render(Model $model = null) {
		HeaderUtils::redirectRaw($this->getLocationUrl($model));
	}

	public function getUrl() {
		return $this->url;
	}

	protected function getLocationUrl(Model $model = null) {
		return $this->getUrl();
	}
}
