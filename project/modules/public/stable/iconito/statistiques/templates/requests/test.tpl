<h2>{$ppo->label}</h2>
<p>Au {$ppo->filter->publishedEndDate->format('d/m/Y')}, il y avait :
    <ul>
        <li>{$ppo->requestClass->getFakeResult()} blogs ouverts.</li>
    </ul>
</p>

<p>
    Du {$ppo->filter->publishedBeginDate->format('d/m/Y')} au {$ppo->filter->publishedEndDate->format('d/m/Y')} :
    <ul>
        {assign var=fake value=$ppo->requestClass->getFakeCountAndAverage()}
        <li>{$fake.total} articles ont été rédigés, soit {$fake.average} articles par blog.</li>
    </ul>
</p>