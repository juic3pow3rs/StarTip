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

/**
 * Class ZendDbSqlMapper
 * @package Tipp\Mapper
 */
class ZendDbSqlMapper implements TippMapperInterface {

    
    protected $dbAdapter;

    protected $hydrator;

    protected $tippPrototype;

   /**
    * 
    * @param AdapterInterface $dbAdapter
    * @param HydratorInterface $hydrator
    * @param TippInterface $tippPrototype
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
     * Sucht den Tipp zu der übergebenen ID aus der DB und gibt diesen, falls vorhanden, zurück
     * @param $t_id
     * @return object
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
     * Prüft ob bereits ein Tipp zur gegebenen s_id und user_id existiert
     * @param  $s_id
     * @param  $user_id
     * @return array
     */
    public function tippAbgegeben($s_id, $user_id)
    {
    	$sql    = new Sql($this->dbAdapter);
    	$select = $sql->select('tipp');
    	$select->join(array("s" => "spiel"), "s.s_id = tipp.s_id")
    	->where(array('tipp.b_id = ?' => $user_id, 'tipp.s_id = ?' => $s_id));
    	   
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
     * Gibt alle Tipps des Benutzers mit der übergebenen user_id zurück
     * @param  $user_id
     * @return array
     */
    public function findAllTipps($user_id)
    {
        $sql    = new Sql($this->dbAdapter);
        $select = $sql->select('tipp');
        $select->join(array("s" => "spiel"), "s.s_id = tipp.s_id")
     	 ->where(array('tipp.b_id = ?' => $user_id));
            
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
     * Legt einen neuen Tipp an/Updatet einen vorhandenen Tipp
     * @param TippInterface $tippObject
     * @param  $s_id
     * @return TippInterface
     * @throws \Exception
     */
    public function save(TippInterface $tippObject, $s_id)
    {
    	
        $tippData = $this->hydrator->extract($tippObject);
	    unset($tippData['t_id']); 
        unset($tippData['punkte']);
        $tippData['s_id']=$s_id;
        
        //Prüft ob bereits ein tipp existiert also der Tipp nur bearbeitet werden soll
     	if ($tippObject->getT_id()) {
        	//Update des Tipp
        	$action = new Update('tipp');
        	$action->set($tippData);
        	$action->where(array('t_id = ?' => $tippObject->getT_id()));
        
      	  //Da noch kein Tipp existiert muss ein neuer Tipp eingefügt werden	
          } else {
        		
     		  $tippData['s_id']=$s_id;
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
      	  
        
        }else {
        	throw new \Exception("Fehler beim speichern.");
        }
       
    }

    /**
     * Updatet einen Zusatztipp
     * @param  $id
     * @param  $status
     * @return boolean
     */
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

    /**
     * Legt einen neuen Zusatztipp an
     * @param $id
     * @param $user_id
     * @param $m_id
     * @return bool
     */
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

    /**
     * Sucht die abgebenen Zusatipps des User mit der übergebenen ID und gibt diese zurück
     * @param $user_id
     * @return array
     */
    public function getZusatztipp($user_id) {

        $sql    = new Sql($this->dbAdapter);
        $select = $sql->select('zusatztipp_abgabe');
        $select->where(array('b_id = ?' => $user_id));

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
     * Setzt das Ergebnis des Zusatztipps mit der übergebenen ID auf die Mannschaft mit der übergebenen ID
     * @param $id
     * @param $m_id
     * @return bool
     */
    public function setZusatztipp($id, $m_id) {

        $data = array('erg' => $m_id);

        $action = new Update('zusatztipp');
        $action->set($data);
        $action->where(array('z_id = ?' => $id));

        $sql = new Sql($this->dbAdapter);
        $stmnt = $sql->prepareStatementForSqlObject($action);

        $result = $stmnt->execute();

        return (bool)$result->getAffectedRows();

    }

    /**
     * Gibt den Status der Zusatztipps zurück
     * @return array
     */
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

    /**
     * Berechnet die Punkte für die abgebenen Tipps zum Zusatztipp mit der übergebenen ID
     * @param $id
     */
    public function zusatzPunkteBerechnen($id) {

        // Alle Tipps zu dem Zusatztipp holen
        $sql    = new Sql($this->dbAdapter);
        $select = $sql->select('zusatztipp_abgabe');
        $select->where(array('z_id' => $id));
        $stmt   = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new ResultSet;
            $resultSet->initialize($result);

            $zusatztipp = $resultSet->toArray();
        }

        // Den Zusatztipp selbst inkl. Ergebnis holen
        $sql2    = new Sql($this->dbAdapter);
        $select2 = $sql2->select('zusatztipp');
        $select2->where(array('z_id = ?' => $id));

        $stmt2   = $sql2->prepareStatementForSqlObject($select2);
        $result2 = $stmt2->execute();

        if ($result2 instanceof ResultInterface && $result2->isQueryResult()) {
            $resultSet2 = new ResultSet;
            $resultSet2->initialize($result2);

            $erg = $resultSet2->toArray();
        }

        // Iterator über jeden abgegeben Tipp und vergleich von diesem mit dem Ergebnis, dementsprechend vergabe der Punkte
        foreach ($zusatztipp as $zst) {

            if ($zst['m_id'] == $erg[0]['erg']) {

                //update Zusatztipp 3 Punkte
                $action = new Update('zusatztipp_abgabe');
                $action->set(array('punkte' => 3));
                $action->where(array('z_id = ?' => $id, 'm_id' => $zst['m_id']));

                $sql    = new Sql($this->dbAdapter);
                $stmt   = $sql->prepareStatementForSqlObject($action);
                $result = $stmt->execute();

            }

        }

    }

    /**
     * Berechnet die Punkte der abgebenen Tipps zu dem Spiel mit der übergebenen ID
     * @param $s_id
     */
    public function punkteBerechnen($s_id) {

        // Spiel inkl allen Infos holen
        $sql    = new Sql($this->dbAdapter);
        $select = $sql->select('spiel');
        $select->where(array('s_id = ?' => $s_id));

        $stmt   = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new ResultSet;
            $resultSet->initialize($result);

            $spiele = $resultSet->toArray();
            $spiel = $spiele['0'];
        } else {
            //throw exception oder so
        }

        // Alle Tipps zu dem übergebenen Spiel holen
        $sql2    = new Sql($this->dbAdapter);
        $select2 = $sql2->select('tipp');
        $select2->where(array('s_id = ?' => $s_id));

        $stmt2   = $sql2->prepareStatementForSqlObject($select2);
        $result2 = $stmt2->execute();

        if ($result2 instanceof ResultInterface && $result2->isQueryResult()) {
            $resultSet2 = new ResultSet;
            $resultSet2->initialize($result2);

            $tipps = $resultSet2->toArray();
        } else {
            //throw exception oder so
        }

        //Iterator über die abgegebenen Tipps
        foreach($tipps as $tipp) {

            if ($spiel['tore1'] == $tipp['tipp1'] && $spiel['tore2'] == $tipp['tipp2']) {
                //update Tipp 3 Punkte
                $action = new Update('tipp');
                $action->set(array('punkte' => 3));
                $action->where(array('t_id = ?' => $tipp['t_id']));

                $sql    = new Sql($this->dbAdapter);
                $stmt   = $sql->prepareStatementForSqlObject($action);
                $result = $stmt->execute();

                //return (bool)$result->getAffectedRows();

            }

            elseif ((($spiel['tore1'] > $spiel['tore2']) && ($tipp['tipp1'] > $tipp['tipp2'])) && (($spiel['tore1'] - $spiel['tore2']) == ($tipp['tipp1'] - $tipp['tipp2']))) {
                //update Tipp 2 Punkte
                $action = new Update('tipp');
                $action->set(array('punkte' => 2));
                $action->where(array('t_id = ?' => $tipp['t_id']));

                $sql    = new Sql($this->dbAdapter);
                $stmt   = $sql->prepareStatementForSqlObject($action);
                $result = $stmt->execute();

                //return (bool)$result->getAffectedRows();
            }

            elseif ((($spiel['tore1'] < $spiel['tore2']) && ($tipp['tipp1'] < $tipp['tipp2'])) && (($spiel['tore2'] - $spiel['tore1']) == ($tipp['tipp2'] - $tipp['tipp1']))) {
                //update Tipp 2 Punkte
                $action = new Update('tipp');
                $action->set(array('punkte' => 2));
                $action->where(array('t_id = ?' => $tipp['t_id']));

                $sql    = new Sql($this->dbAdapter);
                $stmt   = $sql->prepareStatementForSqlObject($action);
                $result = $stmt->execute();

                //return (bool)$result->getAffectedRows();
            }

            elseif ((($spiel['tore1'] > $spiel['tore2']) && ($tipp['tipp1'] > $tipp['tipp2'])) || (($spiel['tore1'] < $spiel['tore2']) && ($tipp['tipp1'] < $tipp['tipp2'])) || (($spiel['tore1'] == $spiel['tore2']) && ($tipp['tipp1'] == $tipp['tipp2']))) {
                //update Tipp 1 Punkt
                $action = new Update('tipp');
                $action->set(array('punkte' => 1));
                $action->where(array('t_id = ?' => $tipp['t_id']));

                $sql    = new Sql($this->dbAdapter);
                $stmt   = $sql->prepareStatementForSqlObject($action);
                $result = $stmt->execute();

                //return (bool)$result->getAffectedRows();
            }

            else {
                //kein Update notwendig da Standard 0 Punkte
            }

        }

    }

    /**
     * Setzt alle Zusatztipps zurück auf aktiviert
     * @return bool
     */
    public function resetZusatztipp() {

        $action = new Update('zusatztipp');
        $action->set(array('status' => 1));

        $sql    = new Sql($this->dbAdapter);
        $stmt   = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        return (bool)$result->getAffectedRows();
    }

}