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
use Zend\Db\Sql\Delete;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Http\Client as HttpClient;
use Zend\Dom\Query;

/**
 * Class ZendDbSqlMapper
 * @package Mannschaft\Mapper
 */
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
     * Sucht eine Mannschaft anhand der übergebenen ID in der DB und gibt diese, falls vorhanden, zurück
     * @param $m_id
     * @return MannschaftInterface
     * @internal param int|string $id
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

        throw new \InvalidArgumentException("Mannschaft with given ID:{$m_id} not found.");
    }

    /**
     * Sucht alle Mannschaften in der DB und gibt diese zurück
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
     * Speichert das übergebene Mannschaft-Objekt in der DB
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
     * Sucht den Namen der Mannschaft zu der übergebenen ID aus der DB und gibt ihn zurück
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

    /**
     * Sucht die ID der Mannschaft zu dem übergebenen Namen aus der DB und gibt sie zurück
     * @param $name
     * @return array
     */
    public function findId($name) {

        $sql    = new Sql($this->dbAdapter);
        $select = $sql->select('mannschaft')->columns(array('m_id'));
        $select->where(array('name = ?' => $name));

        $stmt   = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new ResultSet;
            $resultSet->initialize($result);

            return $resultSet->toArray();
        }


        return array();

    }

    /**
     * Crawlt die Mannschaften von 'http://84.200.248.53/em2016/mannschaften.html' und speichert sie in der DB
     * @return array
     */
    public function crawl() {

        //Einen Client instanziieren
        $client = new HttpClient();
        //Dem Client sagen, dass er Curl verwenden soll
        $client->setAdapter('Zend\Http\Client\Adapter\Curl');
        //Dem Client sagen, welche URL er crawlen soll
        $client->setUri('http://84.200.248.53/em2016/mannschaften.html');

        //Request senden
        $result = $client->send();
        //Antwort abspeichern (= der HTML Quelltext)
        $body = $result->getBody();

        //Neues DOM-Query instanziieren und den gecrawlten Quelltext als Argument übergeben
        $dom = new Query($body);
        //Den Inhalt des Elements 'td', welches innerhalb der Tags 'tr' steht, welche wiederrum innerhalb eines Elements
        //mit der ID '#content' stehen, abspeichern
        $title = $dom->execute('#content tr > td');

        $i = 0;
        $j = 0;
        $mannschaft = array();
        $mannschaften = array();

        //Eine Iteration über die abgespeicherten Inhalte und abspeichern als array
        foreach ($title as $t) {

            $mannschaft[$j] = $t->nodeValue;

            if ($j == 2) {

                $j = 0;
                $mannschaften[$i] = $mannschaft;
                $i++;
                $mannschaft = array();

            } else {

                $j++;
            }
        }

        return $mannschaften;

    }

    /**
     * Funktion zum löschen aller Mannschaften in der DB
     * @return bool
     */
    public function delete() {

        $action = new Delete('mannschaft');

        $sql    = new Sql($this->dbAdapter);
        $stmt   = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        return (bool)$result->getAffectedRows();

    }

    /**
     * Funktion zum zählen, der vorhandenen Mannschaften in der DB
     * @return array
     */
    public function count() {

        $sql    = new Sql($this->dbAdapter);
        $select = $sql->select('mannschaft')->columns(array('num' => new \Zend\Db\Sql\Expression('COUNT(*)')));

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