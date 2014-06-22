{if count($tree) > 0}
    <ul class="bottomMenu">
        {foreach $tree as $node}
            <li>
                <a href="{if $node->item->getItemUrl()}{$node->item->getItemUrl()}{else}#{/if}"
                {if $node->item->getItemType() == 'externalItem'}target="blank"{/if}>
                {$node->item->displayName}
                </a>
                {include file='customMenu.tpl' tree=$node->children}
            </li>
        {/foreach}
    </ul>
{/if}
