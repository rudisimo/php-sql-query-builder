<?php
/**
 * Author: Rodolfo Puig <rodolfo@puig.io>
 * Date: 2/3/16
 * Time: 3:04 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Sql\QueryBuilder\Builder\Syntax;

use NilPortugues\Sql\QueryBuilder\Builder\GenericBuilder;
use NilPortugues\Sql\QueryBuilder\Manipulation\Insert;
use NilPortugues\Sql\QueryBuilder\Manipulation\QueryException;
use NilPortugues\Sql\QueryBuilder\Manipulation\Replace;

/**
 * Class InsertWriter.
 */
class ReplaceWriter
{
    /**
     * @var GenericBuilder
     */
    private $writer;

    /**
     * @var ColumnWriter
     */
    private $columnWriter;

    /**
     * @param GenericBuilder    $writer
     * @param PlaceholderWriter $placeholder
     */
    public function __construct(GenericBuilder $writer, PlaceholderWriter $placeholder)
    {
        $this->writer = $writer;
        $this->columnWriter = WriterFactory::createColumnWriter($this->writer, $placeholder);
    }

    /**
     * @param Replace $replace
     *
     * @throws QueryException
     *
     * @return string
     */
    public function write(Replace $replace)
    {
        $columns = $replace->getColumns();

        if (empty($columns)) {
            throw new QueryException('No columns were defined for the current schema.');
        }

        $columns = $this->writeQueryColumns($columns);
        $values = $this->writeQueryValues($replace->getValues());
        $table = $this->writer->writeTable($replace->getTable());
        $comment = AbstractBaseWriter::writeQueryComment($replace);

        return $comment."REPLACE INTO {$table} ($columns) VALUES ($values)";
    }

    /**
     * @param $columns
     *
     * @return string
     */
    protected function writeQueryColumns($columns)
    {
        return $this->writeCommaSeparatedValues($columns, $this->columnWriter, 'writeColumn');
    }

    /**
     * @param $collection
     * @param $writer
     * @param string $method
     *
     * @return string
     */
    protected function writeCommaSeparatedValues($collection, $writer, $method)
    {
        \array_walk(
            $collection,
            function (&$data) use ($writer, $method) {
                $data = $writer->$method($data);
            }
        );

        return \implode(', ', $collection);
    }

    /**
     * @param $values
     *
     * @return string
     */
    protected function writeQueryValues($values)
    {
        return $this->writeCommaSeparatedValues($values, $this->writer, 'writePlaceholderValue');
    }
}
