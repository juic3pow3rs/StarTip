<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 05.12.2015
 * Time: 11:15
 */

namespace Mannschaft\Mapper;

use Mannschaft\Model\MannschaftInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Update;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\Db\ResultSet\ResultSet;

class ZendDbSqlMapper implements MannschaftMapperInterface {

    /**
     * @var \Zend\Db\Adapter\AdapterInterface
     */
    protected $dbAdapter;

    protected $hydrator;

    protected $mannschaftPrototype;

    /**
     * @param AdapterInterface  $dbAdapter
     * @param HydratorInterface $hydrator
     * @param MannschaftInterface    $mannschaftPrototype
     */
    public function __construct(
        AdapterInterface $dbAdapter,
        HydratorInterface $hydrator,
        MannschaftInterface $mannschaftPrototype
    ) {
        $this->dbAdapter      = $dbAdapter;
        $this->hydrator       = $hydrator;
        $this->mannschaftPrototype = $mannschaftPrototype;
    }

    /**
     * @param int|string $id
     *
     * @return MannschaftInterface
     * @throws \InvalidArgumentException
     */
    public function find($m_id)
    {
        $sql    = new Sql($this->dbAdapter);
        $select = $sql->select('mannschaft');
        $select->where(array('m_id = ?' => $m_id));

        $stmt   = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()) {
            return $this->hydrator->hydrate($result->current(), $this->mannschaftPrototype);
        }

        throw new \InvalidArgumentException("Mannschaft with given ID:{$id} not found.");
    }

    /**
     * @return array|MannschaftInterface[]
     */
    public function findAll()
    {
        $sql    = new Sql($this->dbAdapter);
        $select = $sql->select('mannschaft');

        $stmt   = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new HydratingResultSet($this->hydrator, $this->mannschaftPrototype);

            return $resultSet->initialize($result);
        }

        return array();
    }

    /**
     * @param MannschaftInterface $mannschaftObject
     *
     * @return MannschaftInterface
     * @throws \Exception
     */
    public function save(MannschaftInterface $mannschaftObject)
    {
        $mannschaftData = $this->hydrator->extract($mannschaftObject);
        unset($mannschaftData['m_id']); // Neither Insert nor Update needs the ID in the array

        if ($mannschaftObject->getM_id()) {
            // ID present, it's an Update
            $action = new Update('mannschaft');
            $action->set($mannschaftData);
            $action->where(array('m_id = ?' => $mannschaftObject->getM_id()));
        } else {
            // ID NOT present, it's an Insert
            $action = new Insert('mannschaft');
            $action->values($mannschaftData);
        }

        $sql    = new Sql($this->dbAdapter);
        $stmt   = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface) {
            if ($newId = $result->getGeneratedValue()) {
                // When a value has been generated, set it on the object
                $mannschaftObject->setM_id($newId);
            }

            return $mannschaftObject;
        }

        throw new \Exception("Database error");
    }

    /**
     * @param $m_id
     * @return mixed
     */
    public function findName($m_id)
    {
        $sql    = new Sql($this->dbAdapter);
        $select = $sql->select('mannschaft');
        $select->where(array('m_id = ?' => $m_id));
      
        $stmt   = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        
   
        
        return $result->current();   
 }

}