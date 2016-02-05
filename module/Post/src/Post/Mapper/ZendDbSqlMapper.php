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
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Update;
use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * Class ZendDbSqlMapper
 * @package Post\Mapper
 */
class ZendDbSqlMapper implements PostMapperInterface {

    /**
     * @var \Zend\Db\Adapter\AdapterInterface
     */
    protected $dbAdapter;

    protected $hydrator;

    protected $postPrototype;

   /**
    * 
    * @param AdapterInterface $dbAdapter
    * @param HydratorInterface $hydrator
    * @param PostInterface $postPrototype
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
     * Sucht alle Posts der durch g_id übergebenen Gruppe
     * @param  $g_id
     * @return array|PostInterface
     */
    public function find($g_id)
    {
        $sql    = new Sql($this->dbAdapter);
        $select = $sql->select('post');
        $select->where(array('g_id = ?' => $g_id));
        $select->join(array("u" => "user"), "u.user_id = post.b_id")
        ->order(array("post.datum_zeit DESC"));

        
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
     * Gibt alle Posts zurück
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
   * Speichert einen neuen Post
   * @param PostInterface $postObject
   * @param  $g_id
   * @throws \Exception
   * @return \Post\Model\PostInterface
   */
    public function save(PostInterface $postObject, $g_id)
    {
        $postData = $this->hydrator->extract($postObject);
  		$postData['g_id']=$g_id;
  		
		unset($postData['datum_zeit']);
		
		$action = new Insert('post');
        $action->values($postData);
       
        $sql    = new Sql($this->dbAdapter);
        $stmt   = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface) {
            if ($newP_id = $result->getGeneratedValue()) {
               
                $postObject->setP_id($newP_id);
            }

            return $postObject;
        }

        throw new \Exception("Database error");
    }

}