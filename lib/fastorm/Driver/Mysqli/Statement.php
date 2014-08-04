<?php

namespace fastorm\Driver\Mysqli;

use fastorm\Driver\QueryException;

class Statement implements \fastorm\Driver\StatementInterface
{

    protected $statement;
    protected $paramsOrder;

    public function setStatement($statement)
    {
        $this->statement = $statement;
    }

    public function setParamsOrder(array $params)
    {
        $this->paramsOrder = $params;
    }

    public function bindParams(array $params)
    {

        $values = array();
        foreach (array_keys($this->paramsOrder) as $key) {
            $values[] = &$params[$key];
        }

        array_unshift($values, str_repeat('s', count($this->paramsOrder)));
        call_user_func_array(array($this->statement, 'bind_param'), $values);
    }

    /**
     * @return bool
     */
    public function execute()
    {
        return $this->statement->execute();
    }

    /**
     * @return int
     */
    public function getAffectedRows()
    {
        return $this->statement->affected_rows;
    }

    /**
     * @return Result
     * @throws \fastorm\Adapter\Driver\QueryException
     */
    public function getResult()
    {

        $result = $this->statement->get_result();
        if ($result === false) {
            throw new QueryException($this->statement->error, $this->statement->errno);
        }

        return new Result($result);
    }

    public function close()
    {
        $this->statement->close();
    }
}
