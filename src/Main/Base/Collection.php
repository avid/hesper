<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Denis M. Gabaidulin
 */
namespace Hesper\Main\Base;

interface Collection {

	public function add(CollectionItem $item);

	public function addAll(array /*of CollectionItem*/
	                       $items);

	public function clear();

	public function contains(CollectionItem $item);

	public function containsAll(array /*of CollectionItem*/
	                            $items);

	public function isEmpty();

	public function size();

	public function remove(CollectionItem $item);

	public function removeAll(array /*of CollectionItem*/
	                          $items);

	public function retainAll(array /*of CollectionItem*/
	                          $items);

	public function getList();

	public function getByName($name);

	public function has($name);
}
