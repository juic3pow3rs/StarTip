<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 10.12.2015
 * Time: 11:15
 * @todo: Funktion isAdmin() programmieren
 */

namespace Gruppe\Mapper;

use Gruppe\Model\GruppeInterface;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Delete;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ZendDbSqlMapper implements GruppeMapperInterface {

    /**
     * @var \Zend\Db\Adapter\AdapterInterface
     */
    protected $dbAdapter;

    protected $hydrator;

    protected $gruppePrototype;

    /**
     * @param AdapterInterface  $dbAdapter
     * @param HydratorInterface $hydrator
     * @param GruppeInterface    $gruppePrototype
     */
    public function __construct(
        AdapterInterface $dbAdapter,
        HydratorInterface $hydrator,
        GruppeInterface $gruppePrototype
    ) {
        $this->dbAdapter      = $dbAdapter;
        $this->hydrator       = $hydrator;
        $this->gruppePrototype = $gruppePrototype;
    }

    /**
     * @param int|string $id
     *
     * @return GruppeInterface
     * @throws \InvalidArgumentException
     * @todo: Nur Gruppen anzeigen, deren Status in der Mitglieder Tabelle 1 ist.
     */
    public function find($g_id)
    {
        $sql    = new Sql($this->dbAdapter);
        $select = $sql->select('gruppe');
        $select->where(array('g_id = ?' => $g_id));

        $stmt   = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()) {
            return $this->hydrator->hydrate($result->current(), $this->gruppePrototype);
        }

        throw new \InvalidArgumentException("Gruppe with given ID:{$g_id} not found.");
    }
    
    public function findName($username, $g_id)
    {
    	$sql    = new Sql($this->dbAdapter);
    	$select = $sql->select('user');
    	$select->where(array('username = ?' => $username));
    
    	$stmt   = $sql->prepareStatementForSqlObject($select);
    	$result = $stmt->execute();
    
    	if ($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()) {
    		$action = new Insert('mitglied');
            $action->values($g_id, $g_id);
    		
    		
    		$stmt   = $sql->prepareStatementForSqlObject($select);
    		$result = $stmt->execute();
    		
    		
    	}
    
    	throw new \InvalidArgumentException("Der Benutzername :{$username} existiert nicht.");
    }

    /**
     * @return array|GruppeInterface[]
     * @todo: Abfrage von $user_id von Mitglied Tabelle abfragen, nicht von Gruppe Tabelle
     */
    public function findAll($user_id)
    {    	
        $sql    = new Sql($this->dbAdapter);
        $select = $sql->select('gruppe');
        $select->join(array("m" => "mitglied"), "m.g_id = gruppe.g_id")
        ->where(array('m.b_id = ?' => $user_id, 'm.status =?' => 1));
      
        
        $stmt   = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new HydratingResultSet($this->hydrator, $this->gruppePrototype);

            return $resultSet->initialize($result);
        }

        return array();
    }

    /**
     * @param GruppeInterface $gruppeObject
     *
     * @return GruppeInterface
     * @throws \Exception
     * @todo: Abfrage, ob User berechtigt ist die Gruppe zu editieren
     * @todo: Bei Erstellen, User in die mitglied Tabelle eintragen
     */
    public function save(GruppeInterface $gruppeObject)
    {
        $gruppeData = $this->hydrator->extract($gruppeObject);
        unset($gruppeData['g_id']); // Neither Insert nor Update needs the ID in the array
	
        $leiter=1;
        if ($gruppeObject->getG_id()) {
            // ID present, it's an Update
            $action = new Update('gruppe');
            $action->set($gruppeData);
            $action->where(array('g_id = ?' => $gruppeObject->getG_id()));
        } else {
            // ID NOT present, it's an Insert
            $action = new Insert('gruppe');
            $action->values($gruppeData);
            $leiter=0;
        }

        $sql    = new Sql($this->dbAdapter);
        $stmt   = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface) {
            if ($newId = $result->getGeneratedValue()) {
                // When a value has been generated, set it on the object
                $gruppeObject->setG_id($newId);
            }

            //Leiter als Mitglied der Gruppe setzen
            if($leiter==0)
            {
            $this->addLeiter($gruppeObject->getUser_id(),$gruppeObject->getG_id());
            }

            return $gruppeObject;
        }

        throw new \Exception("Database error");
    }

    public function compare($g_id){

        $sql    = new Sql($this->dbAdapter);
        $select = $sql->select('rang_global');

        $select->join(array("m" => "mitglied"), "m.b_id = rang_global.b_id")
            ->where(array('m.g_id = ?' => $g_id))
            ->group('rang_global.b_id')
            ->order('rang_global.gesamt DESC');

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
     * @param $user_id
     * @return array|\Zend\Db\ResultSet\ResultSet
     */
    public function findAllEinladungen($user_id)
    {
    	    	
    	
    	$sql    = new Sql($this->dbAdapter);
    	$select = $sql->select('gruppe');
		$select->join(array("m" => "mitglied"), "m.g_id = gruppe.g_id")
    	->where(array('m.b_id = ?' => $user_id, 'm.status =?' => 0));
    
    	
    	$stmt   = $sql->prepareStatementForSqlObject($select);
    	$result = $stmt->execute();
    
    if ($result instanceof ResultInterface && $result->isQueryResult()) {
    		$resultSet = new HydratingResultSet($this->hydrator, $this->gruppePrototype);
    	
    		return $resultSet->initialize($result);
    }
    
    	return array();
    }

    /**
     * @param $user_id
     * @param $g_id
     * @return array
     * @throws \Exception
     */
    public function annehmen($user_id, $g_id)
    {
    	$a=array('status' => 1);
    	$action = new Update('mitglied');
    	$action->set($a);
    	$action->where(array('b_id = ?' => $user_id, 'g_id = ?' => $g_id,));

    	$sql    = new Sql($this->dbAdapter);
    	$stmt   = $sql->prepareStatementForSqlObject($action);
    	$result = $stmt->execute();


        return (bool)$result->getAffectedRows();

        // @todo: Wozu die Abfrage? $a ist ein array ohne Funktionen.
  	    /**if ($result instanceof ResultInterface) {
            if ($newId = $result->getGeneratedValue()) {
                // When a value has been generated, set it on the object
                $a->setG_id($newId);
            }

            return $a;
        }
    
    	throw new \Exception("Database error");**/
    }
  
    public function ablehnen($user_id, $g_id)
    {
        $action = new Delete('mitglied');
        $action->where(array('b_id = ?' => $user_id, 'g_id =?' => $g_id));
    
        $sql    = new Sql($this->dbAdapter);
        $stmt   = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();
    
        return (bool)$result->getAffectedRows();
    }

    public function isAdmin($user_id, $g_id) {

        $sql    = new Sql($this->dbAdapter);
        $select = $sql->select('gruppe');
        $select->where(array('user_id = ?' => $user_id, 'g_id = ?' => $g_id));

        $stmt   = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()) {
            return true;
        }

        return 0;
    }

    public function isMitglied($user_id, $g_id) {

        $sql    = new Sql($this->dbAdapter);
        $select = $sql->select('mitglied');
        $select->where(array('b_id = ?' => $user_id, 'g_id = ?' => $g_id, 'status = ?' => 1));

        $stmt   = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()) {
            return true;
        }

        return 0;
    }

    public function addLeiter($user_id, $g_id) {

        $action = new Insert('mitglied');
        $action->values(array(
            'b_id' => $user_id,
            'g_id' => $g_id,
            'status' => '1'
        ));

        $sql    = new Sql($this->dbAdapter);
        $stmt   = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()) {
            return true;
        }

        return 0;

    }
    
    public function bereitsEingeladen($user_id, $g_id) {
    
    	$sql    = new Sql($this->dbAdapter);
    	$select = $sql->select('mitglied');
    	$select->where(array('b_id = ?' => $user_id, 'g_id = ?' => $g_id));
    
    	$stmt   = $sql->prepareStatementForSqlObject($select);
    	$result = $stmt->execute();
    
    	if ($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()) {
    		return true;
    	}
    
    	return 0;
    }

}