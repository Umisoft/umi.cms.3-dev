{if $code eq '404'}
    <h1>A 404 error occurred</h1>

    <h2>{$error->getMessage()}</h2>

{elseif $code eq '403'}
    <h1>Access denied</h1>

    <h2>{$error->getMessage()}</h2>
{else}

    <div>
        <h1>An error occurred</h1>

        <h2>{$error->getMessage()}</h2>

        <hr/>
        <h2>Additional information:</h2>

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
                    {$error = $error->getPrevious()}
                {/while}
            </ul>
        {/if}
    </div>

{/if}