<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 11.12.2015
 * Time: 11:15
 */

namespace Post\Mapper;

use Post\Model\PostInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Update;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements PostMapperInterface {

    /**
     * @var \Zend\Db\Adapter\AdapterInterface
     */
    protected $dbAdapter;

    protected $hydrator;

    protected $postPrototype;

    /**
     * @param AdapterInterface  $dbAdapter
     * @param HydratorInterface $hydrator
     * @param PostInterface    $postPrototype
     */
    public function __construct(
        AdapterInterface $dbAdapter,
        HydratorInterface $hydrator,
        PostInterface $postPrototype
    ) {
        $this->dbAdapter      = $dbAdapter;
        $this->hydrator       = $hydrator;
        $this->postPrototype = $postPrototype;
    }

    /**
     * @param int|string $id
     *
     * @return PostInterface
     * @throws \InvalidArgumentException
     */
    public function find($g_id)
    {
        $sql    = new Sql($this->dbAdapter);
        $select = $sql->select('post');
        $select->where(array('g_id = ?' => $g_id));

        $stmt   = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new HydratingResultSet($this->hydrator, $this->postPrototype);

            return $resultSet->initialize($result);
        }

        return array();
    }

    /**
     * @return array|SpielInterface[]
     */
    public function findAll()
    {
        $sql    = new Sql($this->dbAdapter);
        $select = $sql->select('post');

        $stmt   = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new HydratingResultSet($this->hydrator, $this->postPrototype);

            return $resultSet->initialize($result);
        }

        return array();
    }

    /**
     * @param PostInterface $postObject
     *
     * @return PostInterface
     * @throws \Exception
     */
    public function save(PostInterface $postObject, $g_id)
    {
        $postData = $this->hydrator->extract($postObject);
      // Neither Insert nor Update needs the ID in the array
    	   $postData['g_id']=$g_id;
		unset($postData['datum_zeit']);
        if ($postObject->getP_id()) {
            // ID present, it's an Update
            $action = new Update('post');
            $action->set($postData);
            $action->where(array('p_id = ?' => $postObject->getP_id()));
        } else {
            // ID NOT present, it's an Insert
            $action = new Insert('post');
            $action->values($postData);
            
        }

        $sql    = new Sql($this->dbAdapter);
        $stmt   = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface) {
            if ($newP_id = $result->getGeneratedValue()) {
                // When a value has been generated, set it on the object
                $postObject->setP_id($newP_id);
            }

            return $postObject;
        }

        throw new \Exception("Database error");
    }

}