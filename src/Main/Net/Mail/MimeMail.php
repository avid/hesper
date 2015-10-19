<?php
/**
 * @project    Hesper Framework
 * @author     Alex Gorbylev
 * @originally onPHP Framework
 * @originator Konstantin V. Arkhipov
 */
namespace Hesper\Main\Net\Mail;

use Hesper\Core\Base\Assert;
use Hesper\Core\Exception\UnimplementedFeatureException;

/**
 * Class MimeMail
 * @package Hesper\Main\Net\Mail
 */
final class MimeMail implements MailBuilder {

	private $parts = [];

	// should be built by build()
	private $body    = null;
	private $headers = null;

	private $boundary = null;

	/**
	 * @return MimeMail
	 **/
	public function addPart(MimePart $part) {
		$this->parts[] = $part;

		return $this;
	}

	public function build() {
		if (!$this->parts) {
			throw new UnimplementedFeatureException();
		}

		if (!$this->boundary) {
			$this->boundary = '=_' . md5(microtime(true));
		}

		$mail = MimePart::create()->setContentType('multipart/mixed')->setBoundary($this->boundary);

		$this->headers = "MIME-Version: 1.0\n" . $mail->getHeaders();

		foreach ($this->parts as $part) {
			$this->body .= '--' . $this->boundary . "\n" . $part->getHeaders() . "\n\n" . $part->getEncodedBody() . "\n";
		}

		$this->body .= '--' . $this->boundary . "--" . "\n\n";
	}

	public function getEncodedBody() {
		Assert::isTrue($this->body && $this->headers);

		return $this->body;
	}

	public function getHeaders() {
		Assert::isTrue($this->body && $this->headers);

		return $this->headers;
	}

	/**
	 * @return MimeMail
	 **/
	public function setBoundary($boundary) {
		$this->boundary = $boundary;

		return $this;
	}

	public function getBoundary() {
		return $this->boundary;
	}
}
