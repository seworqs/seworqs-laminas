<?php

declare(strict_types=1);

namespace Seworqs\Laminas\Model\Repository;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Db\Sql\Sql;
use Laminas\Hydrator\ClassMethodsHydrator;
use Laminas\Hydrator\HydratorInterface;
use Laminas\Mvc\Service\PaginatorPluginManagerFactory;
use Laminas\Paginator\Paginator;

class AbstractHydratorRepository extends AbstractRepository
{
    protected $_hydrator;

    public function __construct(AdapterInterface $adapter, $tableName, HydratorInterface $hydrator, $object)
    {
        parent::__construct($adapter, $tableName);
        $this->_hydrator = $hydrator;
        $this->_object = $object;
    }

    public function hydrate($data, $object)
    {
        return $this->_hydrator->hydrate($date, $object);
    }

    public function extract($object)
    {
        return $this->_hydrator->hydrate($object);
    }

    public function save($object)
    {
        $sql = new Sql($this->_adapter);
        $data = $this->extract($object);
        if (empty($object->getID())) {
            $insert = $sql
                ->insert($this->_table)
                ->values($data);
            $stmt = $sql->prepareStatementForSqlObject($insert);
        } else {
            $update = $sql
                ->update($this->_table)
                ->set($data)
                ->where(['ID' => $object->getID()]);
            $stmt = $sql->prepareStatementForSqlObject($update);
        }

        $result = $stmt->execute();
        if (! $object->getID() && $result->getGeneratedValue()) {
            $object->setID($result->getGeneratedValue());
        }

        return $object;
    }
    public function findByID($id)
    {

        $sql = new Sql($this->_adapter);
        $select = $sql->select($this->_table)
            ->where(['ID' => $id])
            ->limit(1);
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        if ($result->isQueryResult() && $result->current()) {
            return $this->_hydrator->hydrate($result->current(), $this->_object);
        }

        return null;
    }

    public function fetchAll(): Paginator
    {

        $sql = new Sql($this->_adapter);
        $select = $sql->select($this->_table);
        $hydratingResultSet = new HydratingResultSet($this->$_hydrator, $this->_object);
        $paginator = $this->getPaginator($select, $sql, $hydratingResultSet);
        return $paginator;
    }
}
