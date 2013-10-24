<h2>{$ppo->label}</h2>
<p>
    Du <span class="dateStats">{$ppo->filter->publishedFrom->format('d/m/Y')}</span> au <span class="dateStats">{$ppo->filter->publishedTo->format('d/m/Y')}</span> :
    <ul>
        {assign var=quiz value=$ppo->requestClass->getQuiz()}
        <li><strong>{$quiz.total}</strong> quiz ont été créé(s)  <span class="average">(soit {$quiz.average} quiz par jour)</span></li>
        {assign var=questions value=$ppo->requestClass->getNombreQuestionsEtRatio()}
        <li><strong>{$questions.questions}</strong> question(s) ont été créée(s) <span class="average">(soit {$questions.ratio} question(s) par quiz)</span></li>
    </ul>
</p>