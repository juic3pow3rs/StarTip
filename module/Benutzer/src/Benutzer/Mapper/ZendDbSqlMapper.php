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
use Zend\Db\Sql\Update;
use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * Class ZendDbSqlMapper
 * @package Benutzer\Mapper
 */
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
     * Alle Benutzer aus der View 'rang_overall' selektieren und zurückgeben.
     * @return array|UserInterface[]
     */
    public function findAll()
    {
        $sql    = new Sql($this->dbAdapter);
        $select = $sql->select('rang_overall');

        $stmt   = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        /**
         * Prüft, ob das Ergebnis eine Instanz des ResultInterfaces ist und ob es ein QueryResult ist
         */
        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new ResultSet;
            $resultSet->initialize($result);

            return $resultSet->toArray();
        }

        return array();
    }

    /**
     * Prüft, ob der Benutzername in der DB existiert, wenn ja wird er zurückgegeben.
     * @param $name
     * @return mixed
     */
    public function find($name)
    {
        $sql    = new Sql($this->dbAdapter);
        $select = $sql->select('user');
        $select->where(array('username = ?' => $name));

        $stmt   = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        return $result->current();
    }

    /**
     * Prüft, ob der Benutzer mit der übergebenen ID in der Datenbank existiert, wenn ja wird er zurückgegeben
     * @param $id
     * @return object
     */
    public function findUser($id)
    {
    	
    	 $sql    = new Sql($this->dbAdapter);
        $select = $sql->select('user');
        $select->where(array('user_id = ?' => $id));

        $stmt   = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()) {
            return $this->hydrator->hydrate($result->current(), $this->benutzerPrototype);
        }

        throw new \InvalidArgumentException("Benutzer with given ID:{$id} not found.");
    }


    /**
     * Funktion zum Einladen eines Benutzers in eine Tippgemeinschaft
     * @param $g_id
     * @param $id
     * @param $leiter
     * @return bool
     * @throws \Exception
     */
    public function invite($g_id, $id, $leiter) {
	
    	$fehler = 0;
    	
    	//Fehler falls der Benutzername nicht existiert
    	if($id == NULL){
    		$fehler = 1;
    	}
    	
    	//Prüft sich der leiter der Gruppe selbst einladen will
    	if($leiter == $id){
    		$fehler = 2;
    	}
    		
    	//Wenn kein Fehler aufgetreten ist, Benutzer einladen
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

    /**
     * Funktion für die Suche nach einem Benutzer.
     * Sucht in der Datenbank nach Benutzernamen, die die Eingabe enthalten
     * @param $benutzername
     * @return array
     */
    public function such($benutzername)
    {
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

    /**
     * @param $id
     * @param $url
     * @return bool
     */
    public function setAva($id, $url) {

            $action = new Update('user');
            $action->set(array('profile_picture' => $url));
            $action->where(array('user_id = ?' => $id));

            $sql    = new Sql($this->dbAdapter);
            $stmt   = $sql->prepareStatementForSqlObject($action);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface) {
                return true;
            }

            return false;
        }

    /**
     * Gibt die URL des BenutzerAvatars zurück, falls vorhanden.
     * @param $id
     * @return array|bool
     */
    public function getAva($id) {

            $sql    = new Sql($this->dbAdapter);
            $select = $sql->select('user');
            $select->columns(array('profile_picture'));
            $select->where(array('user_id = ?' => $id));

            $sql    = new Sql($this->dbAdapter);
            $stmt   = $sql->prepareStatementForSqlObject($select);
            $result = $stmt->execute();

            if ($result instanceof ResultInterface && $result->isQueryResult()) {
                $resultSet = new ResultSet;
                $resultSet->initialize($result);

                return $resultSet->toArray();
            }

            return false;
        }
    
}