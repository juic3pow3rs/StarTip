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
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Insert;
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
     * @todo: Error handling
     * @todo: Punkte von Zusatztipps nicht berücksichtigt!
     */
    public function findAll()
    {
        $sql    = new Sql($this->dbAdapter);
        $select = $sql->select('rang_global');

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
     * @param $name
     * @return mixed
     * @todo: Error handling
     */
    public function find($name)
    {
        $sql    = new Sql($this->dbAdapter);
        $select = $sql->select('user');
        $select->where(array('username = ?' => $name));

        $stmt   = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        /**
        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new HydratingResultSet(new \Zend\Stdlib\Hydrator\ClassMethods(false), new \Benutzer\Model\User());

            return $resultSet->initialize($result);
        }**/

        return $result->current();

        /**
        if ($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()) {
            return $this->hydrator->hydrate($result->current(), new \Benutzer\Model\User() );
        }**/

        //throw new \InvalidArgumentException("User with given Name:{$name} not found.");
    }
    
    public function findUser($id)
    {
    	
    	 	$sql    = new Sql($this->dbAdapter);
    	$select = $sql->select('user');
    	$select->where(array('user_id = ?' => $id));
    
    	$stmt   = $sql->prepareStatementForSqlObject($select);
    	$result = $stmt->execute();
    
    
    	return $result->current();
   
    
    }

    public function invite($g_id, $id, $leiter) {
	
    	$fehler=0;
    	
    	//Fehler falls der Benutzername nicht existiert
    	if($id == NULL){
    		$fehler=1;
    	}
    	
    	//Prüft sich der leiter der Gruppe selbst einladen will
    	if($leiter == $id){
    		$fehler=2;
    	}
    		
    	//Wenn kein Fehler aufgetreten ist
    	if($fehler == 0)   	{
        $action = new Insert('mitglied');
        $action->values(
            array(
                'b_id' => $id,
                'g_id' => $g_id
            )
        );

        $sql    = new Sql($this->dbAdapter);
        $stmt   = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface) {

            return true;
        }
    	}

    	if($fehler == 2)
        throw new \Exception("Sie sind der Leiter dieser Gruppe und koenen sich selbst keine Einladung schicken");
    	if($fehler == 1)
    	throw new \Exception("Der Benutzername existiert nicht");
    }

    public function such($benutzername)
    {
    	    	    	    
    	
      /** $sql    = new Sql($this->dbAdapter);
       $select = $sql->select('user');
       $spec = function (Where $where) {
       	$where->like('username', '%'.$benutzername.'%');
       };
       $select->where(($spec));
       */
    	
       $sql    = new Sql($this->dbAdapter);
       $select = $sql->select('user');
       $select->where->like('username', '%'.$benutzername.'%');
            
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