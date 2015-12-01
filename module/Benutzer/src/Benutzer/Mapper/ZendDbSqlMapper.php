<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 01.12.2015
 * Time: 10:59
 */

namespace Benutzer\Mapper;

use ZfcUser\Entity\UserInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements BenutzerMapperInterface {

    /**
     * @var \Zend\Db\Adapter\AdapterInterface
     */
    protected $dbAdapter;

    protected $hydrator;

    protected $benutzerPrototype;

    /**
     * @param AdapterInterface  $dbAdapter
     * @param HydratorInterface $hydrator
     * @param UserInterface    $benutzerPrototype
     */
    public function __construct(
        AdapterInterface $dbAdapter,
        HydratorInterface $hydrator,
        UserInterface $benutzerPrototype
    ) {
        $this->dbAdapter      = $dbAdapter;
        $this->hydrator       = $hydrator;
        $this->benutzerPrototype = $benutzerPrototype;
    }


    /**
     * @return array|UserInterface[]
     */
    public function findAll()
    {
        $sql    = new Sql($this->dbAdapter);
        $select = $sql->select('user');

        $stmt   = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new HydratingResultSet($this->hydrator, $this->benutzerPrototype);

            return $resultSet->initialize($result);
        }

        return array();
    }

}