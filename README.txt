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

        $tableName = 'testing';
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