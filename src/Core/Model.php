<?php

namespace App\Core;

use PDO;
use App\Core\Database;

abstract class Model {

    protected $limit = '';
    protected $offset = '';
    protected $having = '';
    protected $orderBy = '';
    protected $groupBy = '';
    protected $asJson = false;
    protected $distinct = false;
    protected $timestamps = true;

    protected $joins = [];
    protected $wheres = [];
    protected $orWheres = [];
    protected $attributes = [];
    protected $selectColumns = ['*'];
    protected $aggregateColumns = '';

    protected static $table = null;
    protected static $fillable = [];
    protected static $primaryKey = 'id';

    public function __construct($data = []) {
        $this->fill($data);
    }

    public function __call($method, $arguments) {
        if (method_exists($this, "_$method")) {
            return $this->{"_$method"}(...$arguments);
        }

        throw new \Exception("Method {$method} does not exist.");
    }

    public static function __callStatic($method, $arguments) {
        $instance = new static(); // Late static binding

        if (method_exists($instance, "_$method")) {
            return $instance->{"_$method"}(...$arguments);
        }

        throw new \Exception("Static method {$method} does not exist.");
    }

    public function __set($name, $value) {
        $this->attributes[$name] = $value;
    }

    public function __get($name) {
        return $this->attributes[$name] ?? null;
    }

    public function __isset($key) {
        return isset($this->attributes[$key]);
    }
    
    public function getFillable() {
        return static::$fillable;
    }

    protected function fill($data) {
        if (!$data) return [];

        $results = [];

        foreach ($data as $row) {
            $model = new static();
            $model->attributes = (array)$row; // cast to array for safety
            $results[] = $model;
        }

        return $results;
    }

    protected function fillSingle($row) {
        $this->attributes = (array)$row; // cast to array for safety
        return $this;
    }

    public function load(array $data) {
        foreach ($data as $key => $value) {
            if (in_array($key, $this->getFillable())) {
                $this->attributes[$key] = $value;
            }
        }
    }

    public function _getTableName() {
        return $this->getTableName();
    }

    protected static function getTableName() {
        if (self::$table) {
            $table = self::$table;
            self::$table = null;
            return $table;
        }
        return self::$table ?? strtolower((new \ReflectionClass(static::class))->getShortName()) . 's';
    }

    public static function find($permission = null) {
        $self = new static();

        if ($permission !== null) {
            if (is_array($permission)) {
                foreach ($permission as $column => $value) {
                    $self->_where($column, '=', $value);
                }
            } else {
                // Assume it's a primary key lookup
                $self->_where(static::$primaryKey, '=', $permission);
            }

            $self->limit(1);
            return $self->get(1); // fetch only one result
        }

        return $self; // no condition, return base query builder
    }

    protected function _select(array $columns) {
        $this->selectColumns = $columns;
        return $this;
    }

    public function rawSelect($expression) {
        $this->selectColumns = [$expression];
        return $this;
    }

    public function _count($column = '*') {
        return $this->aggregate('COUNT', $column);
    }

    public function _sum($column) {
        return $this->aggregate('SUM', $column);
    }

    public function _avg($column) {
        return $this->aggregate('AVG', $column);
    }

    public function _min($column) {
        return $this->aggregate('MIN', $column);
    }

    public function _max($column) {
        return $this->aggregate('MAX', $column);
    }

    protected function aggregate($function, $column = '*') {
        $this->aggregateColumns = "$function($column) AS $function";
        $this->limit(1);
        return $this;
    }

    protected function _from($name) {
        self::$table = $name;
        return $this;
    }

    public function innerJoin($table, $leftColumn, $operator, $rightColumn) {
        return $this->join($table, $leftColumn, $operator, $rightColumn, 'INNER');
    }

    public function leftJoin($table, $leftColumn, $operator, $rightColumn) {
        return $this->join($table, $leftColumn, $operator, $rightColumn, 'LEFT');
    }

    public function rightJoin($table, $leftColumn, $operator, $rightColumn) {
        return $this->join($table, $leftColumn, $operator, $rightColumn, 'RIGHT');
    }

    public function fullJoin($table, $leftColumn, $operator, $rightColumn) {
        return $this->join($table, $leftColumn, $operator, $rightColumn, 'FULL OUTER');
    }

    public function join($table, $leftColumn, $operator, $rightColumn, $type = 'INNER') {
        $this->joins[] = compact('type', 'table', 'leftColumn', 'operator', 'rightColumn');
        return $this;
    }

    public function _where($column, $operator, $value) {
        $this->wheres[] = [$column, $operator, $value];
        return $this;
    }

    public function andWhere($column, $operator, $value) {
        return $this->where($column, $operator, $value);
    }

    public function orWhere($column, $operator, $value) {
        $this->orWheres[] = [$column, $operator, $value];
        return $this;
    }

    public function _whereIn($column, array $values) {
        $placeholders = implode(', ', array_fill(0, count($values), '?'));
        $this->wheres[] = ["$column IN ($placeholders)", 'raw', $values];
        return $this;
    }

    public function _whereNull($column) {
        $this->wheres[] = ["$column IS NULL", 'raw', []];
        return $this;
    }

    public function _whereNotNull($column) {
        $this->wheres[] = ["$column IS NOT NULL", 'raw', []];
        return $this;
    }

    public function _groupWhere(callable $callback) {
        $nested = new static();
        $callback($nested);
        $this->wheres[] = ['(' . $nested->buildWhereClause() . ')', 'raw_group', $nested->getWhereParams()];
        return $this;
    }

    public function whereRaw($condition, array $params = []) {
        $this->wheres[] = [$condition, 'raw', $params];
        return $this;
    }

    protected function buildWhereClause() {
        $conditions = array_map(function ($w) {
            if ($w[1] === 'raw' || $w[1] === 'raw_group') {
                return $w[0];
            }
            return "{$w[0]} {$w[1]} ?";
        }, $this->wheres);

        return implode(' AND ', $conditions);
    }

    protected function getWhereParams() {
        $params = [];
        foreach ($this->wheres as $w) {
            if ($w[1] === 'raw' || $w[1] === 'raw_group') {
                $params = array_merge($params, $w[2]);
            } else {
                $params[] = $w[2];
            }
        }
        return $params;
    }

    public function distinct() {
        $this->distinct = true;
        return $this;
    }

    public function having($column, $operator, $value) {
        $this->having = "HAVING $column $operator ?";
        $this->wheres[] = [$column, $operator, $value];
        return $this;
    }

    public function offset($number) {
        $this->offset = "OFFSET $number";
        return $this;
    }

    public function orderBy($column, $direction = 'ASC') {
        $this->orderBy = "ORDER BY $column $direction";
        return $this;
    }

    public function groupBy($column,) {
        $this->groupBy = "GROUP BY $column";
        return $this;
    }

    public function limit($number) {
        $this->limit = "LIMIT $number";
        return $this;
    }

    public function _asJson(){
        $this->asJson = true;
        return $this;
    }

    public static function beginTransaction() {
        Database::connect()->beginTransaction();
    }

    public static function commit() {
        Database::connect()->commit();
    }

    public static function rollback() {
        Database::connect()->rollBack();
    }

    protected function updateTimestamps() {
        $now = date('Y-m-d H:i:s');
        if (isset($this->attributes['created_at']) && $this->attributes['created_at'] != "") {
            $this->attributes['created_at'] = $now;
        }
        if (isset($this->attributes['updated_at'])) {
            $this->attributes['updated_at'] = $now;
        }
    }

    public function save() {
        $table = static::getTableName();
        $primaryKey = static::$primaryKey;

        $records = [];

        // Detect if it's a multi-record update
        if (isset($this->attributes[0]) && (is_array($this->attributes[0]) || is_object($this->attributes[0]))) {
            foreach ($this->attributes as $item) {
                if (is_array($item)) {
                    $records[] = $item;
                } elseif (is_object($item)) {
                    $records[] = (array) $item;
                }
            }
        } else {
            $records[] = is_object($this->attributes) ? (array)$this->attributes : $this->attributes;
        }

        foreach ($records as $attributes) {
            if (!is_array($attributes)) continue;

            // Handle timestamps
            if ($this->timestamps) {
                $now = date('Y-m-d H:i:s');
                $attributes['updated_at'] = $now;
                if (empty($attributes[$primaryKey])) {
                    $attributes['created_at'] = $now;
                }
            }

            if (!empty($attributes[$primaryKey])) {
                $columns = array_keys($attributes);
                $updates = implode(', ', array_map(fn($col) => "$col = ?", $columns));
                $values = array_values($attributes);
                $values[] = $attributes[$primaryKey];

                $sql = "UPDATE $table SET $updates WHERE $primaryKey = ?";
                $this->runStatement($sql, $values);
            } else {
                $columns = implode(', ', array_keys($attributes));
                $placeholders = implode(', ', array_fill(0, count($attributes), '?'));
                $values = array_values($attributes);

                $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
                $this->runStatement($sql, $values);
            }
        }

        return true;
    }

    public static function insertMany(array $rows) {
        if (empty($rows)) return false;
        $table = static::getTableName();
        $columns = array_keys($rows[0]);
        $columnString = implode(',', $columns);
        $placeholders = '(' . implode(',', array_fill(0, count($columns), '?')) . ')';
        $allPlaceholders = implode(',', array_fill(0, count($rows), $placeholders));
        $sql = "INSERT INTO $table ($columnString) VALUES $allPlaceholders";

        $values = [];
        foreach ($rows as $row) {
            foreach ($columns as $col) {
                $values[] = $row[$col];
            }
        }
        $instance = new static();
        return $instance->runStatement($sql, $values);
    }

    public function delete($id = null) {
        $table = static::getTableName();
        $primaryKey = static::$primaryKey;

        // If an ID is explicitly passed
        if ($id !== null) {
            $sql = "DELETE FROM $table WHERE $primaryKey = ?";
            return $this->runStatement($sql, [$id]);
        }

        // If the model has attributes loaded
        $records = [];

        if (isset($this->attributes[0]) && (is_array($this->attributes[0]) || is_object($this->attributes[0]))) {
            foreach ($this->attributes as $item) {
                if (is_object($item)) {
                    $item = (array) $item;
                }
                if (!empty($item[$primaryKey])) {
                    $records[] = $item[$primaryKey];
                }
            }
        } else {
            $attrs = is_object($this->attributes) ? (array) $this->attributes : $this->attributes;
            if (!empty($attrs[$primaryKey])) {
                $records[] = $attrs[$primaryKey];
            }
        }

        // Delete each record
        foreach ($records as $pkValue) {
            $sql = "DELETE FROM $table WHERE $primaryKey = ?";
            $this->runStatement($sql, [$pkValue]);
        }

        return true;
    }

    public function deleteWhere() {
        $params = [];
        $table = static::getTableName();
        $sql = "DELETE FROM $table";
        if (!empty($this->wheres)) {
            $sql .= " WHERE " . $this->buildWhereClause();
            $params = $this->getWhereParams();
        }
        return $this->runStatement($sql, $params);
    }

    public function _first() {
        return $this->one();
    }

    public function _one() {
        $results = $this->get(1);
        return $results ?? null;
    }

    public function _sql(){
        return $this->toSql();
    }

    public static function rawSql($sql){
        $params = [];
        $model = new static();
        return $model->executeQuery($sql, $params, false, PDO::FETCH_ASSOC);
    }

    protected function runStatement($sql, $params = []) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function column($limit = null) {
        return $this->get($limit, true);
    }

    public function get($limit = null, $fetchColumn = null) {
        $params = [];

        if ($limit > 1) {
            $this->limit($limit);
        }

        $sql = $this->buildSqlQuery($params);
        $fetchAssoc = $this->asJson ? PDO::FETCH_ASSOC : PDO::FETCH_OBJ;
        if ($fetchColumn) {
            return $this->executeQuery($sql, $params, false, PDO::FETCH_COLUMN);
        }

        // Run query and fetch results
        $result = $this->executeQuery($sql, $params, false, $fetchAssoc);

        // If only one row and limit is 1, return a single model
        if ($limit === 1 || (!empty($this->limit) && $this->limit === 'LIMIT 1')) {
            if (!$result || count($result) === 0) return null;
            return (new static())->fillSingle($result[0]);  // use helper
        }

        // Return multiple models
        return $this->asJson ? $result : $this->fill($result) ;
    }

    protected function executeQuery($sql, &$params = [], $fetchOne = false, $fetchAssoc = false) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        if ($fetchOne) {
            return $stmt->fetch($fetchAssoc) ?: null;
        }
        return $stmt->fetchAll($fetchAssoc) ?: null;
    }

    public function toSql() {
        $params = [];
        return $this->buildSqlQuery($params, replaceParams: true);
    }

    protected function buildSqlQuery(&$params = [], $replaceParams = false) {
        $table = static::getTableName();
        $select = $this->aggregateColumns ?: implode(', ', $this->selectColumns);
        $select = ($this->distinct ? 'DISTINCT ' : '') . $select;
        $sql = "SELECT $select FROM $table";

        foreach ($this->joins as $join) {
            $sql .= " {$join['type']} JOIN {$join['table']} ON {$join['leftColumn']} {$join['operator']} {$join['rightColumn']}";
        }

        if (!empty($this->wheres)) {
            $conditions = array_map(function ($w) use (&$params) {
                if ($w[1] === 'raw' || $w[1] === 'raw_group') {
                    $params = array_merge($params, $w[2]);
                    return $w[0];
                } else {
                    $params[] = $w[2];
                    return "{$w[0]} {$w[1]} ?";
                }
            }, $this->wheres);
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }

        if (!empty($this->groupBy)) {
            $sql .= " " . $this->groupBy;
        }

        if (!empty($this->having)) {
            $sql .= " " . $this->having;
        }

        if (!empty($this->orderBy)) {
            $sql .= " " . $this->orderBy;
        }

        if (!empty($this->limit)) {
            $sql .= " " . $this->limit;
        }

        if (!empty($this->offset)) {
            $sql .= " " . $this->offset;
        }

        if ($replaceParams) {
            foreach ($params as $param) {
                $value = is_numeric($param) ? $param : "'" . addslashes($param) . "'";
                $sql = preg_replace('/\?/', $value, $sql, 1);
            }
        }
        return $sql;
    }
}