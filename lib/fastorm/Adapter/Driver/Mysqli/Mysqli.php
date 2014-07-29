<?php

namespace fastorm\Adapter\Driver\Mysqli;

use fastorm\Adapter\Database;
use fastorm\Adapter\Driver\DriverException;
use fastorm\Adapter\Driver\QueryException;

class Mysqli implements Database
{
    /**
     * @var \mysqli $connection
     */
    protected $connection;
    /**
     * @var bool
     */
    protected $connected = false;

    public function connect($hostname, $username, $password, $port)
    {
        $driver = new \mysqli_driver();
        $driver->report_mode = MYSQLI_REPORT_STRICT;

        try {
            $this->connection = new \mysqli($hostname, $username, $password, null, $port);
            $this->connected = true;
        } catch (\mysqli_sql_exception $e) {
            throw new DriverException('Connect Error : '.$e->getMessage(), $e->getCode());
        }

        return $this;

    }

    public function setDatabase($database)
    {
        $this->connection->select_db($database);

        if ($this->error() !== false) {
            throw new DriverException('Select database error : ' . $this->error(), $this->connection->errno);
        }
    }

    public function error()
    {

        if ($this->connection->error === '') {
            return false;
        } else {
            return $this->connection->error;
        }

    }

    public function escape($value)
    {
        return $this->connection->real_escape_string($value);
    }

    public function prepare($sql)
    {

        $paramsOrder = array();
        $sql = preg_replace_callback(
            '/:([a-zA-Z0-9_-]+)/',
            function ($match) use (&$paramsOrder) {
                $paramsOrder[$match[1]] = null;

                return '?';
            },
            $sql
        );

        $mysqliStatement = $this->connection->prepare($sql);

        if ($mysqliStatement === false) {
            QueryException::throwException($sql, $this);
        }

        $statement = new Statement($mysqliStatement);
        $statement->setParamsOrder($paramsOrder);

        return $statement;
    }

    public function getInsertId()
    {
        return $this->connection->insert_id;
    }

    public function getSqlState()
    {
        return $this->connection->sqlstate;
    }

    public function getErrorNo()
    {
        return $this->connection->errno;
    }

    public function getErrorMessage()
    {
        return $this->connection->error;
    }

    /**
     * @return boolean
     */
    public function getConnected()
    {
        return $this->connected;
    }

    public function protectFieldName($field)
    {
        return '`'.$field.'`';
    }

    public function protectTableName($table)
    {
        return '`'.str_replace('.', '`.`', $table).'`';
    }
}
