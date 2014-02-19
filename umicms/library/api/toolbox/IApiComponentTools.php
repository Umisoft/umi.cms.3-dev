<?php
	namespace umicms\component\toolbox;

	use umi\toolkit\IToolbox;
	use umicms\component\BaseApiComponent;

	/**
	 * Инструментарий для работы с модулями UMI.CMS.
	 *
	 * API компонент - это модель, предоставляющая публичный API для доступа. Он должен
	 * быть как можно более гибким, весь конкретный функционал реализуется в моделях
	 * модулей. API компонент <b>не зависит</b> от моделей модулей (внутренних моделей), но может
	 * иметь зависимости от других API компонентов.
	 */
	interface IApiComponentTools extends IToolbox {
		/**
		 * Короткий alias для доступа.
		 */
		const ALIAS = 'cmsComponent';

		/**
		 * Возвращает экземпляр api-компонента.
		 * @param string $name имя компонента
		 * @return BaseApiComponent
		 */
		public function getComponent($name);
	}
