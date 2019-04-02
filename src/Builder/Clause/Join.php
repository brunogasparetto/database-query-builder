<?php

namespace Database\QueryBuilder\Builder\Clause;

/**
 * The Join Clause
 */
class Join implements IClause
{
    private $joins = [];

    /**
     * Add a Join or an On Clause in the last Join
     *
     * @param mixed $params Parameters get by func_get_args
     * @throws \InvalidArgumentException
     */
    public function set($params)
    {
        $params = func_get_args();
        $paramsTotal = count($params);

        switch ($paramsTotal) {
            case 2:
                $this->setJoin($params);
                break;

            case 3:
                $this->setOn($params);
                break;

            default:
                throw new \InvalidArgumentException(
                    'Expected a table and type for JOIN or column, operator and other column to ON.'
                );
        }
    }

    /**
     * Get the SQL
     *
     * @return string
     */
    public function sql()
    {
        if (empty($this->joins)) {
            return '';
        }

        $sql = '';

        foreach ($this->joins as $join) {
            $sql .= (empty($sql) ? '' : ' ') . $join['type'] . ' JOIN ' . $join['table'];

            $clauses = [];

            foreach ($join['on'] as $clause) {
                list($c1, $op, $c2) = $clause;
                $clauses[] = "$c1 $op $c2";
            }
            if (!empty($clauses)) {
                $sql .= ' ON ' . implode(' AND ', $clauses);
            }
        }
        return $sql;
    }

    /**
     * Add a Join Clause
     *
     * @param array $params
     */
    private function setJoin(array $params)
    {
        list($table, $type) = $params;
        $this->joins[] = ['table' => $table, 'type' => strtoupper($type), 'on' => []];
    }

    /**
     * Add an On Clause
     *
     * @param array $params
     */
    private function setOn($params)
    {
        list($column1, $operator, $column2) = $params;
        $this->joins[count($this->joins) - 1]['on'][] = [$column1, $operator, $column2];
    }
}
