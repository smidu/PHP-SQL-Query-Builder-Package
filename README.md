Installation:

    Add to Autoloaded Service Providers:
    SqlQueryBuilder\SqlQueryBuilderServiceProvider::class


Usage Example:

    function (SqlQueryBuilder $sqlQueryBuilder) {
        $o1 = new stdClass;
        $o1->column_A = 1;
        $o1->column_B = 2;
        $o1->column_C = 7;
        $o1->column_D = 8;

        $o2 = new stdClass;
        $o2->column_A = 2;
        $o2->column_B = 2;
        $o2->column_C = 9;
        $o2->column_D = 8;

        $tableName = 'table_name';
        $conditionColumns = ['column_A', 'column_B'];
        $updateColumns = ['column_C', 'column_D'];
        $objects = [$o1, $o2];

        $query = $sqlQueryBuilder->buildMultiUpdateQuery(
            $tableName,
            $conditionColumns,
            $updateColumns,
            $objects
        );

        var_dump($query);
    }

Result:

    UPDATE testing SET column_C = (CASE column_A WHEN "1" THEN CASE column_B WHEN "2" THEN "7" ELSE column_C END WHEN "2" THEN CASE column_B WHEN "2" THEN "9" ELSE column_C END ELSE column_C END ), column_D = (CASE column_A WHEN "1" THEN CASE column_B WHEN "2" THEN "8" ELSE column_D END WHEN "2" THEN CASE column_B WHEN "2" THEN "8" ELSE column_D END ELSE column_D END ) WHERE column_A IN (1,2)