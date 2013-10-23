<h2>{i18n key="quiz.msg.import"}</h2>

{if $ppo->successMessage}
    <p class="mesgSuccess">{$ppo->successMessage}</p>
{/if}

<div class="field">
    <form name="search_form" id="search-form" method="post">
        <label for="grade" class="form_libelle"> Année scolaire :</label>
        <select class="form" name="grade" id="grade">
            {html_options values=$ppo->gradesIds output=$ppo->gradesNames selected=$ppo->selectedGrade}
        </select>
        <input type="submit" class="button button-search" value="Voir" id="search-button" />
    </form>
</div>

{if count($ppo->quizList)}
    <table id="quiz-table">
        <thead>
        <tr>
            <th></th>
            <th>{i18n key="quiz.table.classroom" noEscape=1}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
            {foreach from=$ppo->quizList item=quiz}
            <tr>
                <td class="quiz-colstart">
                    <div class="quiz-title">{$quiz->name}</div>
                    <div class="quiz-description">{$quiz->description}</div>
                </td>
                <td>
                    {assign var='quizClasse' value=$quiz->getClasse()}
                        {if $quizClasse}
                    {$quizClasse->nom}
                {/if}
                </td>
                <td>
                    <a href="{copixurl dest="quiz|admin|processImport" id=$quiz->id}" class="button">
                        {i18n key="quiz.admin.importQuiz" noEscape=1}
                    </a>
                </td>
            </tr>
            {/foreach}
        </tbody>
    </table>
{else}
    <div class="noquiz-button">
        {i18n key="quiz.errors.noQuiz" noEscape=1}
    </div>
{/if}

