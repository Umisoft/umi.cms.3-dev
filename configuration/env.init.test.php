<?php
use umi\messages\toolbox\MessagesTools;
use umi\toolkit\IToolkit;
//use umicms\messages\FileTransport;

/**
 * @param IToolkit $toolkit
 */
function toolkit_initializer(IToolkit $toolkit)
{
    /** @var MessagesTools $toolbox */
    $toolbox = $toolkit->getToolbox(MessagesTools::NAME);
//    $toolbox->setTransport(new FileTransport());
}