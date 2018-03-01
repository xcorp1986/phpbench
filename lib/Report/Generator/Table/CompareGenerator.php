<?php

namespace PhpBench\Report\Generator\Table;

use PhpBench\Registry\Config;
use Functional as F;
use PhpBench\Report\Generator\Table\Row;

class CompareGenerator
{
    /**
     * @var array
     */
    private $statKeys;

    /**
     * @var array
     */
    private $classMap;

    public function __construct(array $statKeys, array $classMap)
    {
        $this->statKeys = $statKeys;
        $this->classMap = $classMap;
    }

    public function process(array $tables, Config $config): array
    {
        if (!isset($config['compare'])) {
            return $tables;
        }

        $conditions = array_diff($config['cols'], $this->statKeys, [$config['compare']]);
        $compare = $config['compare'];
        $compareFields = $config['compare_fields'];

        return F\map($tables, function ($table) use ($conditions, $compare, $compareFields) {
            $groups = $this->groupByCols($table, $conditions);
            return $this->processTable($table, $groups, $compare, $compareFields);
        });

        return $tables;
    }

    /**
     * Recursively resolve a comparison column - find a column name that
     * doesn't already exist by adding and incrementing an index.
     *
     * @param Row $row
     * @param int $index
     *
     * @return string
     */
    private function resolveCompareColumnName(Row $row, $name, $index = 1)
    {
        if (!isset($row[$name])) {
            return $name;
        }

        $newName = $name . '#' . (string) $index++;

        if (!isset($row[$newName])) {
            return $newName;
        }

        return $this->resolveCompareColumnName($row, $name, $index);
    }

    private function processTable(array $table, array $groups, string $compare, array $compareFields)
    {
        $table = [];
        $colNames = null;
        foreach ($groups as $group) {
            $firstRow = null;
            foreach ($group as $row) {
                if (null === $firstRow) {
                    $firstRow = $row->newInstance(array_diff_key($row->getArrayCopy(), array_flip($this->statKeys)));
                    if (isset($firstRow[$compare])) {
                        unset($firstRow[$compare]);
                    }
                    foreach ($compareFields as $compareField) {
                        if (isset($firstRow[$compareField])) {
                            unset($firstRow[$compareField]);
                        }
                    }
                }
        
                if (null === $colNames) {
                    $colNames = array_combine($firstRow->getNames(), $firstRow->getNames());
                }
        
                $compared = $row[$compare];
        
                foreach ($compareFields as $compareField) {
                    $name = $compare . ':' . $compared . ':' . $compareField;
        
                    $name = $this->resolveCompareColumnName($firstRow, $name);
        
                    $firstRow[$name] = $row[$compareField];
                    $colNames[$name] = $name;
        
                    // TODO: This probably means the field is non-comparable, could handle this earlier..
                    if (isset($this->classMap[$compareField])) {
                        // we invent a new col name here, use the compare field's class.
                        $this->classMap[$name] = $this->classMap[$compareField];
                    }
                }
            }
        
            $table[] = $firstRow;
        }
        
        $table = F\map($table, function ($row) use ($colNames) {
            $newRow = $row->newInstance([]);
            foreach ($colNames as $colName) {
                $newRow[$colName] = isset($row[$colName]) ? $row[$colName] : null;
            }
        
            return $newRow;
        });
        
        return $table;
    }

    private function groupByCols(array $table, array $conditions)
    {
        $groups = F\group($table, function ($row) use ($conditions) {
            $values = array_intersect_key($row->getArrayCopy(), array_flip($conditions));
        
            return F\reduce_left($values, function ($value, $i, $c, $reduction) {
                return $reduction . $value;
            });
        });
        return $groups;
    }
}
