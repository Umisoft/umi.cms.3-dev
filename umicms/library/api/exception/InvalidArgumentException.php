<?php
	namespace umicms\component\exception;

	/**
	 * Исключение, бросаемое при передаче неверного аргумента.
	 */
	class InvalidArgumentException extends \InvalidArgumentException implements IException {}