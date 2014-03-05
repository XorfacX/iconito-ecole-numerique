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
    <table class="viewItems">
        <thead>
        <tr>
            <th>{i18n key="quiz.table.quiz" noEscape=1}</th>
            <th>{i18n key="quiz.table.classroom" noEscape=1}</th>
            <th>{i18n key="quiz.table.actions" noEscape=1}</th>
        </tr>
        </thead>
        <tbody>
            {foreach from=$ppo->quizList item=quiz}
            <tr>
                <td>
                    <h3 class="quizTitle">{$quiz->name|utf8_decode}</h3>
                    <p class="quizDescription">{$quiz->description|utf8_decode}</p>
                </td>
                <td>
                    {assign var='quizClasse' value=$quiz->getClasse()}
                        {if $quizClasse}
                    {$quizClasse->nom}
                {/if}
                </td>
                <td>
                    <a href="{copixurl dest="quiz|admin|processImport" id=$quiz->id}" class="button button-down">
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

