<h2>{i18n key="quiz.msg.import"}</h2>

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
                    <div class="quiz-title">{$quiz.name}</div>
                    <div class="quiz-description">{$quiz.description}</div>
                </td>
                <td></td>
                <td>Action</td>
            </tr>
        {/foreach}
    </tbody>
</table>
