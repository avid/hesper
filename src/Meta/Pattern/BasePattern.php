<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Meta\Pattern;

use Hesper\Core\Base\Singleton;
use Hesper\Meta\Builder\AutoClassBuilder;
use Hesper\Meta\Builder\AutoDaoBuilder;
use Hesper\Meta\Builder\AutoProtoClassBuilder;
use Hesper\Meta\Builder\BusinessClassBuilder;
use Hesper\Meta\Builder\DaoBuilder;
use Hesper\Meta\Builder\ProtoClassBuilder;
use Hesper\Meta\Console\Format;
use Hesper\Meta\Entity\MetaClass;
use Hesper\Meta\Entity\MetaConfiguration;
use Hesper\Meta\Helper\NamespaceUtils;

/**
 * Class BasePattern
 * @package Hesper\Meta\Pattern
 */
abstract class BasePattern extends Singleton implements GenerationPattern {

	public function tableExists() {
		return true;
	}

	public function daoExists() {
		return false;
	}

	public static function dumpFile($path, $content) {
		$content = trim($content);

		$dirname = dirname($path);
		if (!is_dir($dirname)) {
			mkdir($dirname, 0755, true);
		}

		if (is_readable($path)) {
			$pattern = ['@\/\*(.*)\*\/@sU', '@[\r\n]@sU'];

			// strip only header and svn's Id-keyword, don't skip type hints
			$old = preg_replace($pattern, null, file_get_contents($path), 2);
			$new = preg_replace($pattern, null, $content, 2);
		} else {
			$old = 1;
			$new = 2;
		}

		$out = MetaConfiguration::out();
		$className = basename($path, EXT_CLASS);

		if ($old !== $new) {
			$out->warning("\t\t" . $className . ' ');

			if ( !MetaConfiguration::me()->isDryRun() ) {
				$fp = fopen($path, 'wb');
				fwrite($fp, $content);
				fclose($fp);
			}

			$out->log('(')
			    ->remark(str_replace(getcwd() . DIRECTORY_SEPARATOR, null, $path))
			    ->logLine(')');
		} else {
			$out->infoLine("\t\t" . $className . ' ', true);
		}
	}

	public function build(MetaClass $class) {
		return $this->fullBuild($class);
	}

	/**
	 * @return BasePattern
	 **/
	protected function fullBuild(MetaClass $class) {
		return $this->buildProto($class)
		            ->buildBusiness($class)
		            ->buildDao($class);
	}

	/**
	 * @return BasePattern
	 **/
	protected function buildProto(MetaClass $class) {
		$autoFile = NamespaceUtils::getProtoPath($class, true);
		$this->dumpFile($autoFile, Format::indentize(AutoProtoClassBuilder::build($class)));

		$userFile = NamespaceUtils::getProtoPath($class, false);
		if ( MetaConfiguration::me()->isForcedGeneration() || !file_exists($userFile) ) {
			$this->dumpFile($userFile, Format::indentize(ProtoClassBuilder::build($class)));
		}

		return $this;
	}

	/**
	 * @return BasePattern
	 **/
	protected function buildBusiness(MetaClass $class) {
		$autoFile = NamespaceUtils::getBusinessPath($class, true);
		$this->dumpFile($autoFile, Format::indentize(AutoClassBuilder::build($class)));

		$userFile = NamespaceUtils::getBusinessPath($class, false);
		if ( MetaConfiguration::me()->isForcedGeneration() || !file_exists($userFile) ) {
			$this->dumpFile($userFile, Format::indentize(BusinessClassBuilder::build($class)));
		}

		return $this;
	}

	/**
	 * @return BasePattern
	 **/
	protected function buildDao(MetaClass $class) {
		$autoFile = NamespaceUtils::getDAOPath($class, true);
		$this->dumpFile($autoFile, Format::indentize(AutoDaoBuilder::build($class)));

		$userFile = NamespaceUtils::getDAOPath($class, false);
		if ( MetaConfiguration::me()->isForcedGeneration() || !file_exists($userFile) ) {
			$this->dumpFile($userFile, Format::indentize(DaoBuilder::build($class)));
		}

		return $this;
	}
}
