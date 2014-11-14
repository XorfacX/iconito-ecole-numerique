<?php

class CsvExporter
{
    /**
     * @var array
     */
    protected $lines;

    /**
     * @var ConsolidatedStatisticFilter
     */
    protected $filter;

    /**
     * La partie dont on veut l'export
     *
     * @var string
     */
    protected $part;

    /**
     * @var string $delimiter
     */
    protected $delimiter = ',';

    /**
     * @var string $enclosure
     */
    protected $enclosure = '"';

    /**
     * Constructor
     *
     * @param ConsolidatedStatisticFilter $filter
     * @param string                      $part
     */
    public function __construct(ConsolidatedStatisticFilter $filter, $part)
    {
        $this->filter = $filter;
        $this->part   = $part;
    }

    /**
     * Génération du CSV
     */
    public function generate(array $options = array())
    {
        $className = 'CsvFormatter'.ucfirst($this->part);

        _classInclude('statistiques|CsvFormatter');
        _classInclude('statistiques|'.$className);

        $formatter = new $className($this->filter, $options);

        if (!$formatter instanceof CsvFormatter) {
            throw new Exception('La classe de génération des lignes CSV soit étendre la classe "CsvFormatter"');
        }

        $this->lines = $formatter->getLines();
    }

    /**
     * Send CSV content
     *
     * @param string $filename
     *
     * @return CopixActionReturn
     */
    public function send($filename)
    {
        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        header('Pragma: no-cache');
        header('Expires: 0');

        ob_start();
        $handler = fopen('php://output', 'r+');
        foreach ($this->lines as $line) {
            fputcsv($handler, $line, $this->delimiter, $this->enclosure);
        }
        fclose($handler);

        // Récupération du contenu du tampon
        echo ob_get_clean();
        return _arNone();
    }
}