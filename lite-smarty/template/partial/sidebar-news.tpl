{include file='partial/authorization.tpl'}

<div class="sidebar-module sidebar-module-inset">
    <h4>{translate message='RSS feed'}</h4>
    <p>{translate message='News'}: {widget widgetPath='news.item.rssLink' params=['template' => 'rssLink']}</p>
</div>
<div class="sidebar-module">
    <h4>{translate message='Rubrics'}</h4>
    {widget widgetPath='news.rubric.tree'}
</div>
<div class="sidebar-module">
    <h4>{translate message='Subjects'}</h4>
    {widget widgetPath='news.subject.list' params=['template' => 'listShort']}
</div>

{widget widgetPath='structure.infoblock.view' params=['template' => 'socialGroup', 'infoBlock' => 'commonInfoBlock']}