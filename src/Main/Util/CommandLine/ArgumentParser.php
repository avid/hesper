<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Denis M. Gabaidulin
 */
namespace Hesper\Main\Util\CommandLine;

use Hesper\Core\Base\Assert;
use Hesper\Core\Base\Singleton;
use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Core\Form\Form;

/**
 * Class ArgumentParser
 * @package Hesper\Main\Util\CommandLine
 */
final class ArgumentParser extends Singleton
{
	private $form = null;
	private $result = null;

	/**
	 * @return ArgumentParser
	**/
	public static function me()
	{
		return Singleton::getInstance(__CLASS__);
	}

	/**
	 * @return ArgumentParser
	**/
	public function setForm(Form $form)
	{
		$this->form = $form;

		return $this;
	}

	/**
	 * @return Form
	**/
	public function getForm()
	{
		return $this->form;
	}

	/**
	 * @return ArgumentParser
	**/
	public function parse()
	{
		Assert::isNotNull($this->form);

		$long = FormToArgumentsConverter::getLong($this->form);

		// NOTE: stupid php, see man about long params
		if (empty($long))
			$this->result = getopt(
				FormToArgumentsConverter::getShort($this->form)
			);
		else
			$this->result = getopt(
				FormToArgumentsConverter::getShort($this->form),
				$long
			);

		return $this;
	}

	/**
	 * @return ArgumentParser
	**/
	public function validate()
	{
		Assert::isNotNull($this->result);

		$this->form->import($this->result);

		if ($errors = $this->form->getErrors())
			throw new WrongArgumentException(
				"\nArguments wrong:\n"
				.print_r($errors, true)
			);

		return $this;
	}
}