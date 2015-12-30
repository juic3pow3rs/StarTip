<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 14.12.2015
 * Time: 11:15
 */

namespace Tipp\Mapper;

use Tipp\Model\TippInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Update;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements TippMapperInterface {

    /**
     * @var \Zend\Db\Adapter\AdapterInterface
     */
    protected $dbAdapter;

    protected $hydrator;

    protected $tippPrototype;

    /**
     * @param AdapterInterface  $dbAdapter
     * @param HydratorInterface $hydrator
     * @param AlbumInterface    $albumPrototype
     */
    public function __construct(
        AdapterInterface $dbAdapter,
        HydratorInterface $hydrator,
        TippInterface $tippPrototype
    ) {
        $this->dbAdapter      = $dbAdapter;
        $this->hydrator       = $hydrator;
        $this->tippPrototype = $tippPrototype;
    }

    /**
     * @param int|string $id
     *
     * @return AlbumInterface
     * @throws \InvalidArgumentException
     */
    public function find($t_id)
    {
        $sql    = new Sql($this->dbAdapter);
        $select = $sql->select('tipp');
        $select->where(array('t_id = ?' => $t_id));

        $stmt   = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()) {
            return $this->hydrator->hydrate($result->current(), $this->tippPrototype);
        }

        throw new \InvalidArgumentException("Tipp with given ID:{$t_id} not found.");
    }

    /**
     * @return array|AlbumInterface[]
     */
    public function findAll()
    {
        $sql    = new Sql($this->dbAdapter);
        $select = $sql->select('tipp');

        $stmt   = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new HydratingResultSet($this->hydrator, $this->tippPrototype);

            return $resultSet->initialize($result);
        }

        return array();
    }

    /**
     * @param AlbumInterface $albumObject
     *
     * @return AlbumInterface
     * @throws \Exception
     */
    public function save(TippInterface $tippObject)
    {
        $tippData = $this->hydrator->extract($tippObject);
        unset($tippData['t_id']); // Neither Insert nor Update needs the ID in the array

        if ($tippObject->getT_id()) {
            // ID present, it's an Update
            $action = new Update('tipp');
            $action->set($tippData);
            $action->where(array('t_id = ?' => $tippObject->getT_id()));
        } else {
            // ID NOT present, it's an Insert
            $action = new Insert('tipp');
            $action->values($tippData);
        }

        $sql    = new Sql($this->dbAdapter);
        $stmt   = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface) {
            if ($newId = $result->getGeneratedValue()) {
                // When a value has been generated, set it on the object
                $tippObject->setT_id($newId);
            }

            return $tippObject;
        }

        throw new \Exception("Database error");
    }

    /**
     * @inheritDoc
     */
    public function delete(AlbumInterface $albumObject)
    {
        $action = new Delete('album');
        $action->where(array('id = ?' => $albumObject->getId()));

        $sql    = new Sql($this->dbAdapter);
        $stmt   = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        return (bool)$result->getAffectedRows();
    }

    public function updateZusatztipp($id, $status) {

        $data = array('status' => $status);

        $action = new Update('zusatztipp');
        $action->set($data);
        $action->where(array('z_id = ?' => $id));

        $sql = new Sql($this->dbAdapter);
        $stmnt = $sql->prepareStatementForSqlObject($action);

        $result = $stmnt->execute();

        return (bool)$result->getAffectedRows();
    }

    public function addZusatztipp($id, $user_id, $m_id) {

        $action = new Insert('zusatztipp_abgabe');
        $action->values(array(
            'z_id' => $id,
            'b_id' => $user_id,
            'm_id' => $m_id
        ));

        $sql    = new Sql($this->dbAdapter);
        $stmt   = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        return (bool)$result->getAffectedRows();

    }

    public function isActive() {

        $sql    = new Sql($this->dbAdapter);
        $select = $sql->select('zusatztipp');
        $select->columns(array('status'));

        $stmt   = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();


        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new ResultSet;
            $resultSet->initialize($result);

            return $resultSet->toArray();
        }

        return array();

    }

}