<?php

declare(strict_types=1);

namespace Seworqs\Laminas\Model\Repository;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSetInterface;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Sql;
use Laminas\Paginator\Adapter\LaminasDb\DbSelect;
use Laminas\Paginator\Paginator;

abstract class AbstractRepository
{
    protected $_adapter;
    protected $_table;

    public function __construct(AdapterInterface $adapter, $tableName)
    {
        $this->_adapter = $adapter;
        $this->_table = $tableName;
    }

    abstract public function hydrate($data, $object);
    abstract public function extract($object);
    protected function getPaginator(Select $select, AdapterInterFace|Sql $adapterOrSqlObject, ResultSetInterface $resultSetPrototype = null, Select $countSelect = null, $itemCountPerPage = null)
    {

        $dbSelect = new DbSelect($select, $adapterOrSqlObject, $resultSetPrototype);
        $paginator = new Paginator($dbSelect);
        if (is_int($itemCountPerPage) and $itemCountPerPage > 0) {
            $paginator->setItemCountPerPage($itemCountPerPage);
        }

        return $paginator;
    }
}
