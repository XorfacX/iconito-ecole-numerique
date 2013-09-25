<h2>{$ppo->label}</h2>
<p>
    Du {$ppo->filter->publishedFrom->format('d/m/Y')} au {$ppo->filter->publishedTo->format('d/m/Y')} :
    <ul>
        {assign var=quiz value=$ppo->requestClass->getQuiz()}
        <li>{$quiz.total} quiz ont été créés, soit {$quiz.average} quiz par jour.</li>
        {assign var=questions value=$ppo->requestClass->getNombreQuestionsEtRatio()}
        <li>{$questions.questions} questions ont été créées, soit {$questions.ratio} questions par quiz.</li>
    </ul>
</p>