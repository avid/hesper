<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Alexander V. Solomatin
 */
namespace Hesper\Main\OpenId;

use Hesper\Main\Flow\HttpRequest;
use Hesper\Main\Flow\Model;

/**
 * @ingroup OpenId
 **/
interface OpenIdExtension {

	public function addParamsToModel(Model $model);

	public function parseResponce(HttpRequest $request, array $params);
}
