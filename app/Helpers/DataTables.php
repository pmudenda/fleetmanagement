<?php

namespace App\Helpers;


use PDO;
use PDOException;

class DataTables
{
    public static function data_output($columns, $data)
    {
        $out = array();

        for ($i = 0, $ien = count($data); $i < $ien; $i++) {
            $row = array();

            for ($j = 0, $jen = count($columns); $j < $jen; $j++) {
                $column = $columns[$j];

                // Is there a formatter?
                if (isset($column['formatter'])) {
                    if (empty($column['db'])) {
                        $row[$column['dt']] = $column['formatter']($data[$i]);
                    } else {
                        $row[$column['dt']] = $column['formatter']($data[$i][$column['db']], $data[$i]);
                    }
                } else {
                    if (!empty($column['db'])) {
                        $row[$column['dt']] = $data[$i][$columns[$j]['db']];
                    } else {
                        $row[$column['dt']] = "";
                    }
                }
            }

            $out[] = $row;
        }

        return $out;
    }


    public static function limit($request, $columns): string
    {
        $limit = '';

        if (isset($request['start']) && $request['length'] != -1) {
            $limit = "LIMIT " . intval($request['start']) . ", " . intval($request['length']);
        }

        return $limit;
    }


    public static function order($request, $columns): string
    {
        $order = '';

        if (isset($request['order']) && count($request['order'])) {
            $orderBy = array();
            $dtColumns = self::pluck($columns, 'dt');

            for ($i = 0, $ien = count($request['order']); $i < $ien; $i++) {
                // Convert the column index into the column data property
                $columnIdx = intval($request['order'][$i]['column']);
                $requestColumn = $request['columns'][$columnIdx];

                $columnIdx = array_search($requestColumn['data'], $dtColumns);
                $column = $columns[$columnIdx];

                if ($requestColumn['orderable'] == 'true') {
                    $dir = $request['order'][$i]['dir'] === 'asc' ?
                        'ASC' :
                        'DESC';

                    $orderBy[] = '`' . $column['db'] . '` ' . $dir;
                }
            }

            if (count($orderBy)) {
                $order = 'ORDER BY ' . implode(', ', $orderBy);
            }
        }

        return $order;
    }


    public static function filter($request, $columns, $bindings): string
    {
        $globalSearch = array();
        $columnSearch = array();
        $dtColumns = self::pluck($columns, 'dt');

        if (isset($request['search']) && $request['search']['value'] != '') {
            $str = $request['search']['value'];

            for ($i = 0, $ien = count($request['columns']); $i < $ien; $i++) {
                $requestColumn = $request['columns'][$i];
                $columnIdx = array_search($requestColumn['data'], $dtColumns);
                $column = $columns[$columnIdx];

                if ($requestColumn['searchable'] == 'true') {
                    if (!empty($column['db'])) {
                        $binding = self::bind($bindings, '%' . $str . '%', PDO::PARAM_STR);
                        $globalSearch[] = "`" . $column['db'] . "` LIKE " . $binding;
                    }
                }
            }
        }

        // Individual column filtering
        if (isset($request['columns'])) {
            for ($i = 0, $ien = count($request['columns']); $i < $ien; $i++) {
                $requestColumn = $request['columns'][$i];
                $columnIdx = array_search($requestColumn['data'], $dtColumns);
                $column = $columns[$columnIdx];

                $str = $requestColumn['search']['value'];

                if ($requestColumn['searchable'] == 'true' &&
                    $str != '') {
                    if (!empty($column['db'])) {
                        $binding = self::bind($bindings, '%' . $str . '%', PDO::PARAM_STR);
                        $columnSearch[] = "`" . $column['db'] . "` LIKE " . $binding;
                    }
                }
            }
        }

        // Combine the filters into a single string
        $where = '';

        if (count($globalSearch)) {
            $where = '(' . implode(' OR ', $globalSearch) . ')';
        }

        if (count($columnSearch)) {
            $where = $where === '' ?
                implode(' AND ', $columnSearch) :
                $where . ' AND ' . implode(' AND ', $columnSearch);
        }

        if ($where !== '') {
            $where = 'WHERE ' . $where;
        }

        return $where;
    }


    public static function simple($request, $table, $columns): array
    {
        $bindings = array();

        // Allow for a JSON string to be passed in
        if (isset($request['json'])) {
            $request = json_decode($request['json'], true);
        }

        // Build the SQL query string from the request
        $limit = self::limit($request, $columns);
        $order = self::order($request, $columns);
        $where = self::filter($request, $columns, $bindings);

        // Main query to actually get the data
        $data = self::sql_exec($db, $bindings,
            "SELECT `" . implode("`, `", self::pluck($columns, 'db')) . "`
			 FROM `$table`
			 $where
			 $order
			 $limit"
        );

        // Data set length after filtering
        $resFilterLength = self::sql_exec($db, $bindings,
            "SELECT COUNT(`{$primaryKey}`)
			 FROM   `$table`
			 $where"
        );
        $recordsFiltered = $resFilterLength[0][0];

        // Total data set length
        $resTotalLength = self::sql_exec($db,
            "SELECT COUNT(`{$primaryKey}`)
			 FROM   `$table`"
        );
        $recordsTotal = $resTotalLength[0][0];

        /*
         * Output
         */
        return array(
            "draw" => isset ($request['draw']) ?
                intval($request['draw']) :
                0,
            "recordsTotal" => intval($recordsTotal),
            "recordsFiltered" => intval($recordsFiltered),
            "data" => self::data_output($columns, $data)
        );
    }


    public static function complex(
        $request,
        $conn,
        $table,
        $primaryKey,
        $columns,
        $whereResult = null,
        $whereAll = null
    ): array
    {
        $bindings = array();
        $whereAllBindings = array();
        $db = self::db($conn);
        $whereAllSql = '';

        // Build the SQL query string from the request
        $limit = self::limit($request, $columns);
        $order = self::order($request, $columns);
        $where = self::filter($request, $columns, $bindings);

        // whereResult can be a simple string, or an assoc. array with a
        // condition and bindings
        if ($whereResult) {
            $str = $whereResult;

            if (is_array($whereResult)) {
                $str = $whereResult['condition'];

                if (isset($whereResult['bindings'])) {
                    self::add_bindings($bindings, $whereResult['bindings']);
                }
            }

            $where = $where ?
                $where . ' AND ' . $str :
                'WHERE ' . $str;
        }

        // Likewise for whereAll
        if ($whereAll) {
            $str = $whereAll;

            if (is_array($whereAll)) {
                $str = $whereAll['condition'];

                if (isset($whereAll['bindings'])) {
                    self::add_bindings($whereAllBindings, $whereAll['bindings']);
                }
            }

            $where = $where ?
                $where . ' AND ' . $str :
                'WHERE ' . $str;

            $whereAllSql = 'WHERE ' . $str;
        }

        // Main query to actually get the data
        $data = self::sql_exec($db, $bindings,
            "SELECT `" . implode("`, `", self::pluck($columns, 'db')) . "`
			 FROM `$table`
			 $where
			 $order
			 $limit"
        );

        // Data set length after filtering
        $resFilterLength = self::sql_exec($db, $bindings,
            "SELECT COUNT(`{$primaryKey}`)
			 FROM   `$table`
			 $where"
        );
        $recordsFiltered = $resFilterLength[0][0];

        // Total data set length
        $resTotalLength = self::sql_exec($db, $whereAllBindings,
            "SELECT COUNT(`{$primaryKey}`)
			 FROM   `$table` " .
            $whereAllSql
        );
        $recordsTotal = $resTotalLength[0][0];

        /*
         * Output
         */
        return array(
            "draw" => isset ($request['draw']) ?
                intval($request['draw']) :
                0,
            "recordsTotal" => intval($recordsTotal),
            "recordsFiltered" => intval($recordsFiltered),
            "data" => self::data_output($columns, $data)
        );
    }


    static function fatal($msg)
    {
        echo json_encode(array(
            "error" => $msg
        ));

        exit(0);
    }


    static function bind($a, $val, $type)
    {
        $key = ':binding_' . count($a);

        $a[] = array(
            'key' => $key,
            'val' => $val,
            'type' => $type
        );

        return $key;
    }

    public static function addBindings($a, $vals)
    {
        foreach ($vals['bindings'] as $key => $value) {
            $bindings[] = array(
                'key' => $key,
                'val' => $value,
                'type' => PDO::PARAM_STR
            );
        }
    }

    private static function pluck($columns, string $string)
    {
    }

}
