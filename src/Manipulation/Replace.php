<?php
/**
 * Author: Rodolfo Puig <rodolfo@puig.io>
 * Date: 2/3/16
 * Time: 3:04 PM.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Sql\QueryBuilder\Manipulation;

use NilPortugues\Sql\QueryBuilder\Syntax\SyntaxFactory;

/**
 * Class Replace.
 */
class Replace extends AbstractCreationalQuery
{
    /**
     * @return string
     */
    public function partName()
    {
        return 'REPLACE';
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        $columns = \array_keys($this->values);

        return SyntaxFactory::createColumns($columns, $this->getTable());
    }
}
