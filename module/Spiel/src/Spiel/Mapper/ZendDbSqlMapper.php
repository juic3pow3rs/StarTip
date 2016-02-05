<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 03.12.2015
 * Time: 11:15
 */

namespace Spiel\Mapper;

use Spiel\Model\SpielInterface;
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
 * @package Spiel\Mapper
 */
class ZendDbSqlMapper implements SpielMapperInterface {

    /**
     * @var \Zend\Db\Adapter\AdapterInterface
     */
    protected $dbAdapter;

    protected $hydrator;

    protected $spielPrototype;

    /**
     * @param AdapterInterface  $dbAdapter
     * @param HydratorInterface $hydrator
     * @param SpielInterface    $spielPrototype
     */
    public function __construct(
        AdapterInterface $dbAdapter,
        HydratorInterface $hydrator,
        SpielInterface $spielPrototype
    ) {
        $this->dbAdapter      = $dbAdapter;
        $this->hydrator       = $hydrator;
        $this->spielPrototype = $spielPrototype;
    }

    /**
     * @param $s_id
     * @return SpielInterface
     * @internal param int|string $id
     */
    public function find($s_id)
    {
        $sql    = new Sql($this->dbAdapter);
        $select = $sql->select('spiel');
        $select->where(array('s_id = ?' => $s_id));

     
        $stmt   = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()) {
            return $this->hydrator->hydrate($result->current(), $this->spielPrototype);
        }

        throw new \InvalidArgumentException("Spiel with given ID:{$s_id} not found.");

    }

    /**
     * @param $s_id
     * @return array
     */
    public function spielStatus($s_id)
    {
    	$sql    = new Sql($this->dbAdapter);
    	$select = $sql->select('spiel');
    	$select->where(array('s_id = ?' => $s_id, 'status =?'=> 1));
    	
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
     * @return array|SpielInterface[]
     */
    public function findAll()
    {
        $sql    = new Sql($this->dbAdapter);
        $select = $sql->select('spiel');
       
   
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
     * @param SpielInterface $spielObject
     *
     * @return SpielInterface
     * @throws \Exception
     */
    public function save(SpielInterface $spielObject)
    {
        $spielData = $this->hydrator->extract($spielObject);
        unset($spielData['s_id']); // Neither Insert nor Update needs the ID in the array

        if ($spielObject->getS_id()) {
            // ID present, it's an Update
            $action = new Update('spiel');
            $action->set($spielData);
            $action->where(array('s_id = ?' => $spielObject->getS_id()));
        } else {
            // ID NOT present, it's an Insert
            $action = new Insert('spiel');
            $action->values($spielData);
        }

        $sql    = new Sql($this->dbAdapter);
        $stmt   = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface) {
            if ($newS_id = $result->getGeneratedValue()) {
                // When a value has been generated, set it on the object
                $spielObject->setS_id($newS_id);
            }

            return $spielObject;
        }

        throw new \Exception("Database error");
    }

    //@todo: Hydrating funktioniert nicht
    /**
     * @param $user_id
     * @return array|ResultSet
     */
    public function findTippSpiele($user_id)
    {
    	$sql    = new Sql($this->dbAdapter);
    	$select = $sql->select('spiel');
    	$select->join(array("t" => "tipp"), "t.s_id = spiel.s_id")
    	->where(array('t.b_id = ?' => $user_id));
    
    
    	$stmt   = $sql->prepareStatementForSqlObject($select);
    	$result = $stmt->execute();
    
    	if ($result instanceof ResultInterface && $result->isQueryResult()) {
    		$resultSet = new HydratingResultSet($this->hydrator, $this->spielPrototype);
    
    		return $resultSet->initialize($result);
    	}
    
    	return array();
    }

    /**
     * @param $modus
     * @return array
     */
    public function findModusSpiele($modus)
    {
        $sql    = new Sql($this->dbAdapter);
        $select = $sql->select('spiel');
        $select->where(array('modus = ?' => $modus));

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
     * @return bool
     */
    public function activateTurnier() {

        $action = new Update('turnier');
        $action->set(array('status' => 1));
        $action->where(array('name = ?' => 'em'));

        $sql    = new Sql($this->dbAdapter);
        $stmt   = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        return (bool)$result->getAffectedRows();
    }

    /**
     * @return array
     */
    public function turnierStatus() {

        $sql    = new Sql($this->dbAdapter);
        $select = $sql->select('turnier');
        $select->columns(array('status'));
        $select->where(array('name = ?' => 'em'));

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
     * @param $m
     * @return bool
     */
    public function setModus($m) {

        $action = new Update('turnier');
        $action->set(array('modus' => $m));
        $action->where(array('name = ?' => 'em'));

        $sql    = new Sql($this->dbAdapter);
        $stmt   = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        return (bool)$result->getAffectedRows();
    }

    /**
     * @return array
     */
    public function getModus() {

        $sql    = new Sql($this->dbAdapter);
        $select = $sql->select('turnier');
        $select->columns(array('modus'));
        $select->where(array('name = ?' => 'em'));

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
     * @param $modus
     * @return bool
     */
    public function deleteModus($modus) {

        $action = new Delete('spiel');
        $action->where(array('modus = ?' => $modus));

        $sql    = new Sql($this->dbAdapter);
        $stmt   = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        return (bool)$result->getAffectedRows();

    }

    /**
     * @param $modus
     * @return array
     */
    public function crawl($modus) {

        $client = new HttpClient();
        $client->setAdapter('Zend\Http\Client\Adapter\Curl');

        switch ($modus) {
            case 1:
                $client->setUri('http://84.200.248.53/em2016/vorrunde.html');
        break;
            case 2:
                $client->setUri('http://84.200.248.53/em2016/achtelfinale.html');
        break;
            case 3:
                $client->setUri('http://84.200.248.53/em2016/viertelfinale.html');
        break;
            case 4:
                $client->setUri('http://84.200.248.53/em2016/halbfinale.html');
        break;
            case 5:
                $client->setUri('http://84.200.248.53/em2016/finale.html');
        }

        $result                 = $client->send();
        //content of the web
        $body                   = $result->getBody();

        $dom = new Query($body);
        //get div with id="content" and td'S NodeList
        $title = $dom->execute('#content tr > td');

        $i = 0;
        $j = 0;
        $spiel = array();
        $spiele = array();

        foreach ($title as $t) {

            if ($t->nodeValue != 'A' && $t->nodeValue != 'B' && $t->nodeValue != 'C' && $t->nodeValue != 'D' && $t->nodeValue != 'E' && $t->nodeValue != 'F') {

                $spiel[$j] = $t->nodeValue;

                if ($j == 2) {

                    $j = 0;
                    $spiele[$i] = $spiel;
                    $i++;
                    $spiel = array();

                } else {

                    $j++;
                }
            }
        }

        return $spiele;

    }

    /**
     * @param $modus
     * @return array
     */
    public function count($modus) {

        $sql    = new Sql($this->dbAdapter);
        $select = $sql->select('spiel')->columns(array('num' => new \Zend\Db\Sql\Expression('COUNT(*)')));
        $select->where(array('modus = ?' => $modus));

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
     * Flushen von folgenden Tabellen:
     * - Mannschaft
     * - Alle Zusatztipps auf aktiviert setzen
     * - Turnier Status = 0 und modus = 0 setzen
     */
    public function reset() {

        $action = new Update('turnier');
        $action->set(array('status' => 0, 'modus' => 0));
        $action->where(array('name = ?' => 'em'));

        $sql    = new Sql($this->dbAdapter);
        $stmt   = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        return (bool)$result->getAffectedRows();

    }
}