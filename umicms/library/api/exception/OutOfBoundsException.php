<?php
	namespace umicms\component\exception;

	/**
	 * Создается исключение, если значение не является действительным ключем.
	 * Это соответствует ошибкам, которые не могут быть обнаружены во время компиляции.
	 */
	class OutOfBoundsException extends \OutOfBoundsException implements IException {}