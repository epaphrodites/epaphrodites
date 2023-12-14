<?php

namespace Epaphrodite\database\query\buildChaines;

trait queryChaines
{

    private $table;
    private $key;
    private $rdb;
    private $add;
    private $chaine;
    private $where;
    private $get;
    private $like;
    private $match;
    private $between;
    private $and;
    private $order;
    private $join;
    private $limit;
    private $limit_i;
    private $group;
    private $insert;
    private $values;
    private $set;
    private $set_i;
    private $replace;
    private $having;
    private $or;
    private $is;
    private ?array $param = [];
    private ?int $db = 1;
    private ?bool $close = false;

    /**
     * Sets the database to use
     *
     * @param null|int $db
     * @return mixed
     */
    public function sdb(int $db = 1): mixed
    {
        $this->db = $db;

        return $this;
    }  

    /**
     * Enables or disables connection closure
     *
     * @param null|int $close
     * @return bool
     */
    public function close($close = false): mixed
    {
        $this->close = $close;

        return $this;
    }

    /**
     * Sets parameters for the query
     *
     * @param array|null $param
     * @return self
     */
    public function param(array $param = []): self
    {
        $this->param = $param;

        return $this;
    }

    /**
     * Sets the query string or string
     *
     * @param string $string The query string or chain
     * @return self
     */
    public function chaine(string $string): self
    {
        $this->chaine = "$string";

        return $this;
    }

    /**
     * Sets the query string or string
     *
     * @param string $string The query string or chain
     * @return self
     */
    public function key(string $key): self
    {
        $this->key = "$key";

        return $this;
    }    

    /**
     * Sets the table name for the query
     *
     * @param string $table The table name
     * @return self
     */
    public function table(string $table): self
    {
        $this->table = "$table";

        return $this;
    }

    /**
     * Sets the insert statement for the query
     *
     * @param string $insert The insert statement
     * @return self
     */
    public function insert(string $insert): self
    {
        $this->insert = "$insert";

        return $this;
    }

    /**
     * Sets the values for the query
     *
     * @param string $values The values to be inserted
     * @return self
     */
    public function values(string $values): self
    {
        $this->values = "$values";

        return $this;
    }
    /**
     * Sets the WHERE condition for the query
     *
     * @param string $where The WHERE condition
     * @param string|null $type The type of comparison (e.g., '=', '>', '<', etc.)
     * @return self
     */
    public function where(string $where, ?string $type = null): self
    {
        if ($type === null) {
            $this->where = "$where = ?";
        } else {
            $this->where = "$where $type ?";
        }

        return $this;
    }

    /**
     * Sets the LIKE condition for the query
     *
     * @param string $like The LIKE condition
     * @return self
     */
    public function like(string $like): self
    {
        $this->like = "$like";

        return $this;
    }

    /**
     * Sets the MATCH condition for the query
     *
     * @param string $match The MATCH condition
     * @return self
     */
    public function match(string $match): self
    {
        $this->match = "$match";

        return $this;
    }
    /**
     * Sets the BETWEEN condition for the query
     *
     * @param string $between The BETWEEN condition
     * @return self
     */
    public function between(string $between): self
    {
        $this->between = "$between";

        return $this;
    }

    /**
     * Sets the LIMIT and OFFSET for the query
     *
     * @param string $beginning The beginning limit
     * @param string $end The ending limit
     * @return self
     */
    public function limit(string $beginning, string $end): self
    {
        $this->limit = "LIMIT $end OFFSET $beginning";

        return $this;
    }

    /**
     * Sets the IS condition for the query
     *
     * @param string $type The type of condition
     * @param string $property The property to check
     * @return self
     */
    public function is(string $type, string $property): self
    {
        $this->is = " AND $property IS $type";

        return $this;
    }

    /**
     * Sets the HAVING clause for the query
     *
     * @param string $count The COUNT() function argument
     * @param string $sign The sign for comparison (e.g., '>', '=', '<', etc.)
     * @return self
     */
    public function having(string $count, string $sign): self
    {
        $this->having = "HAVING COUNT($count) $sign ?";

        return $this;
    }

    /**
     * Sets the LIMIT for the query
     *
     * @param int $limit The LIMIT value
     * @return self
     */
    public function limit_i(int $limit): self
    {
        $this->limit_i = "LIMIT $limit";

        return $this;
    }

    /**
     * Sets the ORDER BY clause for the query
     *
     * @param string $key The key to order by
     * @param string $direction The direction of ordering ('ASC' or 'DESC')
     * @return self
     */
    public function orderBy(string $key, string $direction): self
    {
        $this->order = "ORDER BY $key $direction";

        return $this;
    }
    /**
     * Sets the GROUP BY clause for the query
     *
     * @param string $group The field to group by
     * @return self
     */
    public function groupBy(string $group): self
    {
        $this->group = "GROUP BY $group";

        return $this;
    }

    /**
     * Sets the AND conditions for the query
     *
     * @param array $getand The array of conditions to be connected with AND
     * @return self
     */
    public function and(array $getand = []): self
    {
        foreach ($getand as $val) {
            $this->and .= " AND " . $val . " = ? ";
        }

        return $this;
    }

    /**
     * Sets the OR conditions for the query
     *
     * @param array $getOr The array of conditions to be connected with OR
     * @return self
     */
    public function or(array $getOr = []): self
    {
        foreach ($getOr as $val) {
            $this->or .= " OR " . $val . " = ? ";
        }

        return $this;
    }

    /**
     * Sets the JOIN clauses for the query
     *
     * @param array $getJoin The array of JOIN clauses
     * @return self
     */
    public function join(array $getJoin = []): self
    {
        foreach ($getJoin as $val) {
            $this->join .= ' JOIN ' . str_replace('|', ' ON ', $val);
        }

        return $this;
    }

    /**
     * Sets the SET clause for the query
     *
     * @param array $getSet The array of properties to set
     * @return self
     */
    public function set(array $getSet = []): self
    {
        foreach ($getSet as $val) {
            $this->set .= $val . " = ?" . " , ";
        }

        $this->set = rtrim($this->set, " , ");

        return $this;
    }

    /**
     * Sets the SET clause with arithmetic operations for the query
     *
     * @param array $getSet The array of properties to set
     * @param string|null $sign The arithmetic sign for the properties
     * @return self
     */
    public function set_i(array $getSet = [], ?string $sign = "+"): self
    {
        foreach ($getSet as $val) {
            $this->set_i .= $val . " = $val $sign ?" . " , ";
        }

        $this->set_i = rtrim($this->set_i, " , ");

        return $this;
    }

    /**
     * Sets the REPLACE function for properties in the query
     *
     * @param array $properties The array of properties for REPLACE function
     * @return self
     */
    public function replace(array $properties = []): self
    {
        foreach ($properties as $val) {
            $this->replace .= $val . " = REPLACE( $val , ? , ? ) , ";
        }

        $this->replace = rtrim($this->replace, " , ");

        return $this;
    }
}
