<h2>{i18n key="kernel|dashboard.admin.title" noEscape="true"}</h2>
<div id="dash-ct">
<form id="dash-form-ct" action="{copixurl dest="kernel|dashboard|ereg"}" method="post">
    <textarea id="content_txt" name="content_txt">{$ppo->content.content}</textarea>
    <input type="hidden" value="{$ppo->content.id_zone}" name="id_zone" />
    <input type="hidden" value="{$ppo->content.type_zone}" name="type_zone" />
    <input type="hidden" value="{$ppo->content.id}" name="id" />
</form>
</div>
<br />
<div id="dash-pic">
{if !empty($ppo->content.picture)} <img src="{copixurl dest="kernel|dashboard|image" id=$ppo->content.id pic=$ppo->content.picture}" /> {/if}
      {if empty($ppo->content.picture)}
    <form action="{copixurl dest="kernel|dashboard|addPicture" id=$ppo->content.id}" enctype="multipart/form-data" method="post">
        
        <h2><label for="image">{i18n key="kernel|dashboard.admin.labelPic" noEscape="true"}</label></h2>
        <p>{i18n key="kernel|dashboard.admin.picDesc" noEscape="true"}</p>
        <input type="file" name="image" accept="image/*" />
        <input type="submit" class="button button-confirm" value=" {i18n key="kernel|dashboard.admin.add" noEscape="true"}"/>
    </form>
    {/if}
    {if !empty($ppo->content.picture)}
        <br /><a href="{copixurl dest="kernel|dashboard|deletePic" id=$ppo->content.id}" class="button button-delete">Supprimer l'illustration</a>
    {/if}
        <div style="clear:both"></div>
 </div>
<br />
<div id="dash-ereg" class="center">    
    <a href="{copixurl dest="kernel|dashboard|delete" id=$ppo->content.id}" class="button button-reload" >{i18n key="kernel|dashboard.admin.default" noEscape="true"}</a>&nbsp;&nbsp;&nbsp;
    <a id="dash-cancel" href="{copixurl dest="||"}" class="button button-cancel" >{i18n key="kernel|dashboard.admin.cancel" noEscape="true"}</a>&nbsp;
    <a id="dash-submit" href="#" class="button button-confirm" >{i18n key="kernel|dashboard.admin.save" noEscape="true"}</a>
    <div style="clear:both"></div>
</div>

{literal}
<script type="text/javascript">
    jQuery(document).ready(function($){
        $("#dash-submit").click(function(){
            $("#dash-form-ct").submit();
                return false;
        });
    });
</script>
{/literal}