<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Solomatin Alexandr
 */
namespace Hesper\Main\DAO\Worker;

use Hesper\Core\Base\Assert;
use Hesper\Core\Base\Identifiable;
use Hesper\Core\Base\IdentifiableObject;
use Hesper\Core\Cache\Cache;
use Hesper\Core\Exception\ObjectNotFoundException;
use Hesper\Core\OSQL\SelectQuery;
use Hesper\Main\DAO\Uncacher\UncacherBase;
use Hesper\Main\DAO\Uncacher\UncacherTaggableDaoWorker;

/**
 * For uncaching with more than one key
 * @package Hesper\Main\DAO\Worker
 */
final class TaggableDaoWorker extends TransparentDaoWorker {

	const MAX_RANDOM_ID = 134217728;
	const TAG_VERSIONS  = 'tag_versions';
	const KEY_POSTFIX   = 'taggable';
	const LOCK_TIMEOUT  = 1600;        // msec
	const LOCK_PREFIX   = 'lock_';

	/**
	 * @var TaggableHandler|null
	 */
	private static $handler = null;

	private static $customTags = null;

	public static function setHandler($handler) {
		Assert::classExists($handler);

		self::$handler = new $handler();
	}

	public static function setCustomTags($tags) {
		Assert::isArray($tags);

		self::$customTags = $tags;
	}

	public static function dropCustomTags() {
		self::$customTags = null;
	}

	public function expireTags($tags) {
		Assert::isArray($tags);

		$this->updateTags($tags);

		return $this;
	}

	/// cachers
	//@{
	protected function cacheByQuery(SelectQuery $query, /* Identifiable */
	                                $object, $expires = Cache::EXPIRES_FOREVER) {
		$key = $this->makeQueryKey($query, self::SUFFIX_QUERY);

		Cache::me()
		     ->mark($this->className)
		     ->set($key, ['tags' => $this->getTagsForQuery($query), 'data' => $object,], $expires);

//			SemaphorePool::me()->free(self::LOCK_PREFIX.$key);

		return $object;
	}

	protected function cacheById(Identifiable $object, $expires = Cache::EXPIRES_FOREVER) {
		if ($expires !== Cache::DO_NOT_CACHE) {

			Cache::me()
			     ->mark($this->className)
			     ->set($this->makeIdKey($object->getId()), ['tags' => $this->getTagsForObject($object), 'data' => $object,], $expires);
		}

		return $object;
	}

	protected function cacheListByQuery(SelectQuery $query, /* array || Cache::NOT_FOUND */
	                                    $array, $expires = Cache::EXPIRES_FOREVER) {
		if ($array !== Cache::NOT_FOUND) {
			Assert::isArray($array);
			Assert::isTrue(current($array) instanceof Identifiable);
		}

		$key = $this->makeQueryKey($query, self::SUFFIX_LIST);

		Cache::me()
		     ->mark($this->className)
		     ->set($key, ['tags' => $this->getTagsForQuery($query), 'data' => $array,], $expires);

//			SemaphorePool::me()->free(self::LOCK_PREFIX.$key);

		return $array;
	}

	protected function cacheNullById($id, $expires = Cache::EXPIRES_FOREVER) {
		return Cache::me()
		            ->mark($this->className)
		            ->add($this->makeIdKey($id), ['tags' => $this->getTagsForNullObject($id), 'data' => Cache::NOT_FOUND,], $expires);
	}
	//@}

	/// getters
	//@{
	public function getCachedById($id) {
		$result = Cache::me()
		               ->mark($this->className)
		               ->get($this->makeIdKey($id));

		if ($this->checkValid($result)) {
			return $result['data'];
		}

		return null;
	}

	public function getListByIds(array $ids, $expires = Cache::EXPIRES_FOREVER) {
		$list = [];
		$toFetch = [];
		$prefixed = [];

		$proto = $this->dao->getProtoClass();

		$proto->beginPrefetch();

		// dupes, if any, will be resolved later @ ArrayUtils::regularizeList
		$ids = array_unique($ids);

		foreach ($ids as $id) {
			$prefixed[$id] = $this->makeIdKey($id);
		}

		if ($cachedList = Cache::me()
		                       ->mark($this->className)
		                       ->getList($prefixed)
		) {
			foreach ($cachedList as $cached) {
				if ($this->checkValid($cached)) {
					$cached = $cached['data'];
					if ($cached && ($cached !== Cache::NOT_FOUND)) {
						$list[] = $this->dao->completeObject($cached);

						unset($prefixed[$cached->getId()]);
					}
				}
			}
		}

		$toFetch += array_keys($prefixed);

		if ($toFetch) {
			$remainList = [];

			foreach ($toFetch as $id) {
				try {
					$remainList[] = $this->getById($id);
				} catch (ObjectNotFoundException $e) {/*_*/
				}
			}

			$list = array_merge($list, $remainList);
		}

		$proto->endPrefetch($list);

		return $list;
	}
	//@}

	/// uncachers
	//@{
	public function uncacheById($id) {
		return $this->registerUncacher($this->getUncacherById($id));
	}

	/**
	 * @return UncacherBase
	 */
	public function getUncacherById($id) {
		$className = $this->className;
		$idKey = $this->makeIdKey($id);

		try {
			$object = $this->dao->getById($id);
			$tags = self::$handler->getUncacheObjectTags($object, $className);
		} catch (ObjectNotFoundException $e) {
			$tags = [];
		}

		return UncacherTaggableDaoWorker::create($className, $idKey, $tags);
	}

	public function uncacheByIds($ids) {
		if (empty($ids)) {
			return;
		}

		$uncacher = $this->getUncacherById(array_shift($ids));

		foreach ($ids as $id) {
			$uncacher->merge($this->getUncacherById($id));
		}

		return $this->registerUncacher($uncacher);
	}

	public function uncacheLists() {
		$tags = self::$handler->getDefaultTags($this->className);

		return $this->registerUncacher(UncacherTaggableDaoWorkerTags::create($this->className, $tags));
	}

	//@}

	/// internal helpers
	//@{
	protected function gentlyGetByKey($key) {
		$result = Cache::me()
		               ->mark($this->className)
		               ->get($key);

		if ($this->checkValid($result)) {
			return $result['data'];
		}

//			$pool = SemaphorePool::me();
//
//			if (!$pool->get(self::LOCK_PREFIX.$key)) {
//				if ($result && isset($result['data'])) {
//					return $result['data'];
//				} else {
//					for ($msec = 0; $msec <= self::LOCK_TIMEOUT; $msec += 200) {
//						usleep(200*1000);
//						if ($pool->get(self::LOCK_PREFIX.$key)) {
//							$result =
//								Cache::me()->mark($this->className)->get($key);
//
//							$pool->free(self::LOCK_PREFIX.$key);
//
//							if ($this->checkValid($result)) {
//								return $result['data'];
//							} else {
//								// лока уже нет, а кэш не перестроился
//								continue;
//							}
//						}
//					}
//					// не дождались снятия лока
//					throw new DeadLockException(
//						"Cache deadlock. {$this->className} QueryKey={$key}"
//					);
//				}
//			}

		return null;
	}

	protected function checkValid($item) {
		return $item && isset($item['data']) && isset($item['tags']) && $this->checkTagVersions($item['tags']);
	}

	/**
	 * узнает список тегов которые используются в запросе,
	 */
	protected function getTagsForQuery(SelectQuery $query) {
		if (self::$customTags) {
			$tags = self::$customTags;
		} else {
			$tags = self::$handler->getQueryTags($query, $this->className);
		}

		$tagList = [];
		foreach ($tags as $tag) {
			$tagList[$tag] = 0;
		}

		return $this->getTagVersions($tagList);
	}

	protected function getTagsForNullObject($id) {
		$tags = self::$handler->getNullObjectTags($id, $this->className);
		$tagList = [];
		foreach ($tags as $tag) {
			$tagList[$tag] = 0;
		}

		return $this->getTagVersions($tagList);
	}

	protected function getTagVersions(/*array*/
		$tags) {
		$time = microtime(true);
		$tagsToFetch = array_keys($tags);

		if (!$result = Cache::me()
		                    ->mark(self::TAG_VERSIONS)
		                    ->getList($tagsToFetch)
		) {
			$result = [];
		}

		$fetchedTags = [];
		foreach ($tagsToFetch as $tag) {
			$fetched = false;
			foreach ($result as $key => $value) {
				if (strpos($key, $tag) !== false) {
					$fetched = true;
					$fetchedTags[$tag] = $value;
				}
			}
			if (!$fetched) {
				Cache::me()
				     ->mark(self::TAG_VERSIONS)
				     ->set($tag, $time, Cache::EXPIRES_FOREVER);

				$fetchedTags[$tag] = $time;
			}
		}

		return $fetchedTags;
	}

	/**
	 * проверяет версии тегов
	 */
	protected function checkTagVersions(/*array*/
		$tags) {
		$tagVersions = $this->getTagVersions($tags);
		if ($tagVersions == $tags) {
			return true;
		}

		return false;
	}

	protected function updateTagVersions(IdentifiableObject $object) {
		$tags = self::$handler->getUncacheObjectTags($object, $this->className);

		$this->updateTags($tags);

		return true;
	}

	protected function getTagsForObject(IdentifiableObject $object) {
		$tags = self::$handler->getCacheObjectTags($object, $this->className);
		$tagList = [];
		foreach ($tags as $tag) {
			$tagList[$tag] = 0;
		}

		return $this->getTagVersions($tagList);
	}

	protected function updateTags($tags) {
		$time = microtime(true);
		foreach ($tags as $tag) {
			Cache::me()
			     ->mark(self::TAG_VERSIONS)
			     ->set($tag, $time, Cache::EXPIRES_FOREVER);
		}

		return true;
	}

	protected function makeIdKey($id) {
		return parent::makeIdKey($id) . self::KEY_POSTFIX;
	}

	protected function makeQueryKey(SelectQuery $query, $suffix) {
		return parent::makeQueryKey($query, $suffix) . self::KEY_POSTFIX;
	}
	//@}
}
