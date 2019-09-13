<?php

namespace SqlQueryBuilder;

class SqlQueryBuilder
{
    public function buildMultiUpdateQuery(string $table, array $conditions, array $update, $objects): string
    {
        return implode(" ", [
            "UPDATE $table SET",
            $this->buildCaseClauses(
                $this->mergeRulesArray($conditions, $update, $objects)
            ),
            $this->buildWhereClauses($conditions, $objects),
        ]);
    }

    private function buildCaseClauses(array $rulesArray): string
    {
        $cases = [];

        foreach ($rulesArray as $updateColumn => $rules) {

            $newCase = "$updateColumn = (";

            $newCase .= $this->buildInsideCases($updateColumn, $rules);

            $newCase .= ")";

            $cases[] = $newCase;
        }

        $cases = implode(', ', $cases);

        return $cases;
    }

    private function mergeRulesArray(array $conditions, array $update, $objects): array
    {
        $rulesArray = [];

        $rules = $this->buildRulesArray($conditions, $update, $objects);

        foreach ($rules as $rule) {

            $rulesArray = array_merge_recursive($rulesArray, $this->buildMultidimensionalArray(implode('/', $rule)));

        }

        return $rulesArray;
    }

    private function buildWhereClauses(array $conditions, $objects): string
    {
        $whereColumnName = $conditions[0];

        $whereValues = [];

        foreach ($objects as $object) {

            if (!in_array($object->{$whereColumnName}, $whereValues)) {
                $whereValues[] = $object->{$whereColumnName};
            }

        }

        return "WHERE $whereColumnName IN (" . implode(',', $whereValues) . ")";
    }

    private function buildInsideCases($updateColumn, $rules)
    {
        if (key($rules) == $updateColumn) {
            if($rules[key($rules)] == null) {
                return  " NULL ";
            }
            return "\"" . addslashes($rules[key($rules)]) . "\" ";
        }

        $caseName = key(json_decode(key($rules)));

        $case = "CASE $caseName ";
        
        foreach ($rules as $columnName => $nextRules) {

            $ruleColumn = json_decode($columnName, true);

            $case .= "WHEN \"" . $ruleColumn[key($ruleColumn)] . "\" THEN ";

            $case .= $this->buildInsideCases($updateColumn, $nextRules);
            
        }

        $case .= "ELSE $updateColumn END ";
        
        return $case;
    }

    private function buildRulesArray($conditions, $update, $objects)
    {
        $rules = [];

        foreach ($objects as $object) {

            foreach ($update as $updateColumn) {
    
                $newRules = [];

                $newRules[] = $updateColumn;

                foreach ($conditions as $conditionColumn) {

                    $newRules[] = json_encode([$conditionColumn => $object->{$conditionColumn}]);

                }

                $newRules[] = json_encode([$updateColumn => $object->{$updateColumn}]);

                $rules[] = $newRules;
    
            }
        }

        return $rules;
    }

    private function buildMultidimensionalArray(string $path): array
    {
        preg_match('#(?<!\\\\)\/#', $path, $matches, PREG_OFFSET_CAPTURE);
        
        $pos = isset($matches[0][1]) ? $matches[0][1] : false;

        if ($pos === false) {
            return json_decode($path, true);
        }

        $key = substr($path, 0, $pos);
        $path = substr($path, $pos + 1);

        return [$key => $this->buildMultidimensionalArray($path)];
    }
}
