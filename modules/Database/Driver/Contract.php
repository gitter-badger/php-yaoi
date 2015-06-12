<?php

namespace Yaoi\Database\Driver;
use Yaoi\Database\Dsn;
use Yaoi\Database\Quoter;

interface Contract extends Quoter
{
    public function __construct(Dsn $dsn);

    public function query($statement);

    public function lastInsertId();

    public function rowsAffected($result);

    public function escape($value);

    public function rewind($result);

    public function fetchAssoc($result);

    public function queryErrorMessage($result);

    public function disconnect();

    public function getDialect();

    /**
     * @return Contract
     */
    public function getUtility();
}