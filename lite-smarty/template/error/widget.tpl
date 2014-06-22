{if isset($code) and $code eq '403'}
    <pre>{$error->getMessage()}</pre>
{else}

    <div>
        <h1>An error occurred</h1>

        <h2>{$error->getMessage()}</h2>

        <hr/>
        <h2>Additional information:</h2>

        <h3>{get_class($error)}</h3>
        <dl>
            <dt>File:</dt>
            <dd>
                <pre>{$error->getFile()}:{$error->getLine()}</pre>
            </dd>
            <dt>Message:</dt>
            <dd>
                <pre>{$error->getMessage()}</pre>
            </dd>
            <dt>Stack trace:</dt>
            <dd>
                <pre>{$error->getTraceAsString()}</pre>
            </dd>
        </dl>
        <?php
        {$error = $error->getPrevious()}

        {if $error}
            <hr/>
            <h2>Previous exceptions:</h2>
            <ul>
                {while $error}
                    <li>
                        <dl>
                            <dt>File:</dt>
                            <dd>
                                <pre>{$error->getFile()}:{$error->getLine()}</pre>
                            </dd>
                            <dt>Message:</dt>
                            <dd>
                                <pre>{$error->getMessage()}</pre>
                            </dd>
                            <dt>Stack trace:</dt>
                            <dd>
                                <pre>{$error->getTraceAsString()}</pre>
                            </dd>
                        </dl>
                    </li>
                    <?php
                    {$error = $error->getPrevious()}
                {/while}
            </ul>
        {/if}
    </div>

{/if}