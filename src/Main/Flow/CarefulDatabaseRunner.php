<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\Flow;

use Hesper\Core\Base\Assert;
use Hesper\Core\Base\Prototyped;
use Hesper\Core\DB\Transaction\InnerTransaction;
use Hesper\Core\Exception\BaseException;
use Hesper\Core\Exception\DatabaseException;
use Hesper\Core\Form\Form;
use Hesper\Main\DAO\DAOConnected;

/**
 * Class CarefulDatabaseRunner
 * @package Hesper\Main\Flow
 */
final class CarefulDatabaseRunner implements CarefulCommand {

	private $command = null;
	/**
	 * @var InnerTransaction
	 */
	private $transaction = null;

	private $running = false;

	final public function __construct(EditorCommand $command) {
		$this->command = $command;
	}

	/**
	 * @throws BaseException
	 * @return ModelAndView
	 **/
	public function run(Prototyped $subject, Form $form, HttpRequest $request) {
		Assert::isFalse($this->running, 'command already running');
		Assert::isTrue($subject instanceof DAOConnected);

		$this->transaction = InnerTransaction::begin($subject->dao());

		try {
			$mav = $this->command->run($subject, $form, $request);

			$this->running = true;

			return $mav;
		} catch (BaseException $e) {
			$this->transaction->rollback();

			throw $e;
		}
	}

	/**
	 * @return CarefulDatabaseRunner
	 **/
	public function commit() {
		if ($this->running) {
			$this->transaction->commit();
			$this->running = false;
		}

		return $this;
	}

	/**
	 * @return CarefulDatabaseRunner
	 **/
	public function rollback() {
		if ($this->running) {
			try {
				$this->transaction->rollback();
			} catch (DatabaseException $e) {
				// keep silence
			}

			$this->running = false;
		}

		return $this;
	}

	public function __destruct() {
		if ($this->running) {
			$this->rollback();
		}
	}
}
