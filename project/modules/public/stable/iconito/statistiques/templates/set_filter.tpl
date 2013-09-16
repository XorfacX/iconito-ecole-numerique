<h2>Sélectionnez un contexte et une période</h2>

<form action="{copixurl dest="statistiques|default|index"}" method="post" class="edit">
    <div class="field">
        <label for="context" class="form_libelle"> Contexte </label>
        {copixzone process=statistiques|citiesGroup}
    </div>

    <div class="field">
        <label for="dateBegin" class="form_libelle"> Du </label>
        <input type="text" name="publishedBeginDate" id="date_begin" value="{$filter->publishedBeginDateHR|escape}"/> 
    </div>

    <div class="field">
        <label for="dateEnd" class="form_libelle"> Au </label>
        <input type="text" name="publishedEndDate" id="date_end" value="{$filter->publishedBeginDateHR|escape}"/> 
    </div>

    <div class="submit">
        <input type="submit" class="button button-confirm" value="{i18n key=copix:common.buttons.ok}" />
    </div>
</form>

{copixzone process=statistiques|apiRequest stat=$ppo->stat filter=$ppo->filter}

<script type="text/javascript">
    setDatePicker('#date_begin, #date_end');
</script>