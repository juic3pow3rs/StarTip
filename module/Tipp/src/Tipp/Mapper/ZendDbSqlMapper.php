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
     * @return array|AlbumInterface[]
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

   
    public function save(TippInterface $tippObject, $s_id)
    {
    	
        $tippData = $this->hydrator->extract($tippObject);
	    unset($tippData['t_id']); 
        unset($tippData['punkte']);
        $tippData['s_id']=$s_id;
        
        //Prüft ob die Tore für Mannschaft 1 nummerisch sind
        if(is_numeric($tippData['tipp1'])){
        	$tore1=1;
        }else {
        	$tore1=0;
        }
        
        //Prüft ob die Tore für Mannschaft 2 nummerisch sind
        if(is_numeric($tippData['tipp2'])){
        	$tore2=1;
        }else {
        	$tore2=0;
        }

       //Wird nur upgedatet/eingefügt in die DB wenn die Tore nummerisch sind
        if($tore1==1 && $tore2==1){
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
      	  }
        
        }

        if($tore1 ==0 && $tore2==0)
        	throw new \Exception("Die Anzahl der Tore fuer die Erste Mannschaft und Zweite Mannschaft muessen als Zahl angegeben werden.");
        if($tore1==0)
        throw new \Exception("Die Anzahl der Tore fuer die Erste Mannschaft muessen als Zahl angegeben werden.");
        if($tore2==0)
        	throw new \Exception("Die Anzahl der Tore fuer die Zweite Mannschaft muessen als Zahl angegeben werden");
     
        
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

    public function zusatzPunkteBerechnen($id) {

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

        foreach ($zusatztipp as $zst) {

            if ($zst['m_id'] == $erg[0]['erg']) {
                /**
                echo "<pre>";
                print_r($id);
                print_r($zst['m_id']);
                print_r($erg[0]['erg']);
                echo "</pre>";
                **/

                //update Zusatztipp 3 Punkte
                $action = new Update('zusatztipp_abgabe');
                $action->set(array('punkte' => 3));
                $action->where(array('z_id = ?' => $id, 'm_id' => $zst['m_id']));

                $sql    = new Sql($this->dbAdapter);
                $stmt   = $sql->prepareStatementForSqlObject($action);
                $result = $stmt->execute();

                //return (bool)$result->getAffectedRows();

            }

        }

    }

    public function punkteBerechnen($s_id) {

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

        foreach($tipps as $tipp) {


            echo "<pre>";
            print_r($tipp['tipp1']);
            print_r($tipp['tipp2']);
            echo "</pre>";
            echo "<pre>";
            print_r($spiel['tore1']);
            print_r($spiel['tore2']);
            echo "</pre>";

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

}