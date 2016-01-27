<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Meta\Entity;

use Hesper\Core\Exception\MissingElementException;
use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Core\Exception\WrongStateException;
use Hesper\Main\Criteria\FetchStrategy;
use Hesper\Meta\Pattern\GenerationPattern;
use Hesper\Meta\Pattern\InternalClassPattern;
use Hesper\Meta\Type\IntegerType;

/**
 * Class MetaClass
 * @package Hesper\Meta\Entity
 */
class MetaClass {

	private $namespace          = null;
	private $autoNamespace      = null;
	private $autoDaoNamespace   = null;
	private $daoNamespace       = null;
	private $autoProtoNamespace = null;
	private $protoNamespace     = null;
	private $name               = null;
	private $tableName          = null;
	private $type               = null;
	private $path               = null;
	private $autoPath           = null;

	private $parent = null;

	private $properties = [];
	private $interfaces = [];
	private $references = [];

	private $pattern    = null;
	private $identifier = null;

	private $source = null;

	private $strategy = null;

	private $build = true;

	public function __construct($name, $namespace) {
		$this->name = $name;
		$this->namespace = $namespace;

		$parts = explode('\\', $namespace);
		$lastPart = end($parts);
		array_splice($parts, count($parts) - 1);
		$this->autoNamespace = implode('\\', array_merge($parts, ['Auto', $lastPart]));
		$this->autoDaoNamespace = implode('\\', array_merge($parts, ['Auto', 'DAO']));
		$this->autoProtoNamespace = implode('\\', array_merge($parts, ['Auto', 'Proto']));
		$this->daoNamespace = implode('\\', array_merge($parts, ['DAO']));
		$this->protoNamespace = implode('\\', array_merge($parts, ['Proto']));
		$this->path = PATH_CLASSES.$lastPart.DIRECTORY_SEPARATOR;
		$this->autoPath = HESPER_META_AUTO_DIR.DIRECTORY_SEPARATOR.$lastPart.DIRECTORY_SEPARATOR;

		$dumb = strtolower(preg_replace(':([A-Z]):', '_\1', $name));

		if ($dumb[0] == '_') {
			$this->tableName = substr($dumb, 1);
		} else {
			$this->tableName = $dumb;
		}
	}

	public function getNamespace() {
		return $this->namespace;
	}

	public function getName() {
		return $this->name;
	}

	public function getDaoClass($addBackslash = false) {
		if( !$this->getPattern()->daoExists() ) {
			throw new WrongStateException($this->getName().' does not support DAO');
		}
		return ($addBackslash ? '\\' : '') . $this->daoNamespace . '\\' . $this->name . 'DAO';
	}

	public function getProtoClass($addBackslash = false) {
		return ($addBackslash ? '\\' : '') . $this->protoNamespace . '\Proto' . $this->name;
	}

	public function getAutoBusinessClass($addBackslash = false) {
		if($this->getPattern() instanceof InternalClassPattern) {
			throw new WrongStateException($this->getName().' does not have Auto class');
		}
		return ($addBackslash ? '\\' : '') . $this->autoNamespace . '\Auto' . $this->name;
	}

	public function getAutoDaoClass($addBackslash = false) {
		if( !$this->getPattern()->daoExists() ) {
			throw new WrongStateException($this->getName().' does not support DAO');
		}
		return ($addBackslash ? '\\' : '') . $this->autoDaoNamespace . '\Auto' . $this->name . 'DAO';
	}

	public function getAutoProtoClass($addBackslash = false) {
		return ($addBackslash ? '\\' : '') . $this->autoProtoNamespace . '\AutoProto' . $this->name;
	}

	public function getTableName() {
		return $this->tableName;
	}

	/**
	 * @return MetaClass
	 **/
	public function setTableName($name) {
		$this->tableName = $name;

		return $this;
	}

	/**
	 * @return MetaClassType
	 **/
	public function getType() {
		return $this->type;
	}

	public function getTypeId() {
		return $this->type ? $this->type->getId() : null;
	}

	/**
	 * @return MetaClass
	 **/
	public function setType(MetaClassType $type) {
		$this->type = $type;

		return $this;
	}

	/**
	 * @return MetaClass
	 **/
	public function getParent() {
		return $this->parent;
	}

	/**
	 * @return MetaClass
	 **/
	public function getFinalParent() {
		if ($this->parent) {
			return $this->parent->getFinalParent();
		}

		return $this;
	}

	/**
	 * @return MetaClass
	 **/
	public function setParent(MetaClass $parent) {
		$this->parent = $parent;

		return $this;
	}

	public function hasBuildableParent() {
		return ($this->parent && (!$this->getParent()->getPattern() instanceof InternalClassPattern));
	}

	/**
	 * @return MetaClassProperty[]
	 */
	public function getProperties() {
		return $this->properties;
	}

	/// with parent ones
	public function getAllProperties() {
		if ($this->parent) {
			return array_merge($this->parent->getAllProperties(), $this->properties);
		}

		return $this->getProperties();
	}

	/// with internal class' properties, if any
	public function getWithInternalProperties() {
		if ($this->parent) {
			$out = $this->properties;

			$class = $this;

			while ($parent = $class->getParent()) {
				if ($parent->getPattern() instanceof InternalClassPattern) {
					$out = array_merge($parent->getProperties(), $out);
				}

				$class = $parent;
			}

			return $out;
		}

		return $this->getProperties();
	}

	/// only parents
	public function getAllParentsProperties() {
		$out = [];

		$class = $this;

		while ($parent = $class->getParent()) {
			$out = array_merge($out, $parent->getProperties());
			$class = $parent;
		}

		return $out;
	}

	/**
	 * @return MetaClass
	 **/
	public function addProperty(MetaClassProperty $property) {
		$name = $property->getName();

		if (!isset($this->properties[$name])) {
			$this->properties[$name] = $property;
		} else {
			throw new WrongArgumentException("property '{$name}' already exist");
		}

		if ($property->isIdentifier()) {
			$this->identifier = $property;
		}

		return $this;
	}

	/**
	 * @return MetaClassProperty
	 * @throws MissingElementException
	 **/
	public function getPropertyByName($name) {
		if (isset($this->properties[$name])) {
			return $this->properties[$name];
		}

		throw new MissingElementException("unknown property '{$name}'");
	}

	public function hasProperty($name) {
		return isset($this->properties[$name]);
	}

	/**
	 * @return MetaClass
	 **/
	public function dropProperty($name) {
		if (isset($this->properties[$name])) {

			if ($this->properties[$name]->isIdentifier()) {
				unset($this->identifier);
			}

			unset($this->properties[$name]);

		} else {
			throw new MissingElementException("property '{$name}' does not exist");
		}

		return $this;
	}

	public function getInterfaces() {
		return $this->interfaces;
	}

	/**
	 * @return MetaClass
	 **/
	public function addInterface($name) {
		$this->interfaces[] = $name;

		return $this;
	}

	/**
	 * @return GenerationPattern
	 **/
	public function getPattern() {
		return $this->pattern;
	}

	/**
	 * @return MetaClass
	 **/
	public function setPattern(GenerationPattern $pattern) {
		$this->pattern = $pattern;

		return $this;
	}

	/**
	 * @return MetaClassProperty
	 **/
	public function getIdentifier() {
		// return parent's identifier, if we're child
		if (!$this->identifier && $this->parent) {
			return $this->parent->getIdentifier();
		}

		return $this->identifier;
	}

	/**
	 * @return MetaClass
	 **/
	public function setSourceLink($link) {
		$this->source = $link;

		return $this;
	}

	public function getSourceLink() {
		return $this->source;
	}

	/**
	 * @return MetaClass
	 **/
	public function setReferencingClass($className) {
		$this->references[$className] = true;

		return $this;
	}

	public function getReferencingClasses() {
		return array_keys($this->references);
	}

	/**
	 * @return MetaClass
	 **/
	public function setFetchStrategy(FetchStrategy $strategy) {
		$this->strategy = $strategy;

		return $this;
	}

	/**
	 * @return FetchStrategy
	 **/
	public function getFetchStrategy() {
		return $this->strategy;
	}

	public function getFetchStrategyId() {
		if ($this->strategy) {
			return $this->strategy->getId();
		}

		return null;
	}

	public function hasChilds() {
		foreach (MetaConfiguration::me()->getClassList() as $class) {
			if ($class->getParent() && $class->getParent()->getName() == $this->getName()) {
				return true;
			}
		}

		return false;
	}

	public function dump() {
		if ($this->doBuild()) {
			return $this->pattern->build($this);
		}

		return $this->pattern;
	}

	public function doBuild() {
		return $this->build;
	}

	/**
	 * @return MetaClass
	 **/
	public function setBuild($do) {
		$this->build = $do;

		return $this;
	}

	/**
	 * @return MetaClassProperty
	 **/
	public function isRedefinedProperty($name) {
		$parent = $this;

		while ($parent = $parent->getParent()) {
			if ($parent->hasProperty($name)) {
				return $parent->getPropertyByName($name);
			}
		}

		return false;
	}

	/**
	 * @return bool
	 */
	public function isSequenceless() {
		return !($this->getIdentifier()->getType() instanceof IntegerType);
	}

	/**
	 * @return null|string
	 */
	public function getAutoNamespace() {
		return $this->autoNamespace;
	}

	/**
	 * @return null|string
	 */
	public function getAutoDaoNamespace()
	{
		return $this->autoDaoNamespace;
	}

	/**
	 * @return null|string
	 */
	public function getAutoProtoNamespace()
	{
		return $this->autoProtoNamespace;
	}

	/**
	 * @return null|string
	 */
	public function getDaoNamespace()
	{
		return $this->daoNamespace;
	}

	/**
	 * @return null|string
	 */
	public function getProtoNamespace()
	{
		return $this->protoNamespace;
	}

	/**
	 * @return null|string
	 */
	public function getPath()
	{
		return $this->path;
	}

	/**
	 * @return null|string
	 */
	public function getAutoPath()
	{
		return $this->autoPath;
	}
}
