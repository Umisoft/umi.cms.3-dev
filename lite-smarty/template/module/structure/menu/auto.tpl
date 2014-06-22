{if count($menu)}
    <ul class="nav navbar-nav">
        {foreach $menu as $pageInfo}
            <li class="{if $pageInfo['active']}active{/if}">
                <a href="{$pageInfo['page']->getPageUrl()}">{$pageInfo['page']->displayName}</a>
            </li>
        {/foreach}
    </ul>
{/if}
