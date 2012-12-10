
<h2>{$ppo->title}
{if $ppo->isEns}
<a href="https://corree.preprod.coreprim.fr/viewClasses.html"  target="_blank" class="button button-update floatright">{i18n key="rssetagere.linkCoreprim"}</a>
{/if}
</h2>

{if $ppo->items|@count}
<ul class="resource">
{foreach from=$ppo->items item=itemR}
<li class="">
    <a class="content-panel" href="{$itemR->link}" target="_blank">
        <img src="{$itemR->pic.url}" alt="{$itemR->title}" class="illustration" />
        <p>{$itemR->desc}</p>
    </a>
</li>
{/foreach}
</ul>
{else}
    {if $ppo->isEns}
        <p>{i18n key="rssetagere.emptyIdentity" noEscape=1}</p>
        <div class="center"><img src="{copixresource path="img/rssetagere/identity.png"}" alt="" /></div>
        <p>{i18n key="rssetagere.failIdentity" noEscape=1}</p>
        <div class="center"><img src="{copixresource path="img/rssetagere/fail-identity.png"}" alt="" /></div>
    {else}
        <p>{i18n key="rssetagere.empty"}</p>
{/if}
{/if}
