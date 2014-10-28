<?php
use umi\messages\toolbox\MessagesTools;
use umi\toolkit\IToolkit;
use umicms\messages\FileTransport;

/**
 * @param IToolkit $toolkit
 */
return function (IToolkit $toolkit)
{
    /** @var MessagesTools $toolbox */
    $toolbox = $toolkit->getToolbox(MessagesTools::NAME);
    $toolbox->setTransport(new FileTransport('messages.txt'));
};