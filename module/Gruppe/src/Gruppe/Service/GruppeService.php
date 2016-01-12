<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 10.12.2015
 * Time: 15:48
 */

namespace Gruppe\Service;

use Gruppe\Mapper\GruppeMapperInterface;
use Gruppe\Model\GruppeInterface;
use Zend\Db\TableGateway\TableGatewayInterface;

class GruppeService implements GruppeServiceInterface {

    protected $gruppeMapper;

    /**
     * @param GruppeMapperInterface $gruppeMapper
     */
    public function __construct(GruppeMapperInterface $gruppeMapper)
    {
        $this->gruppeMapper = $gruppeMapper;
    }

    /**
     * @inheritDoc
     */
    public function findAllGruppen($user_id)
    {
        return $this->gruppeMapper->findAll($user_id);
    }

    /**
     * @inheritDoc
     */
    public function findGruppe($g_id)
    {
        return $this->gruppeMapper->find($g_id);
    }
    
    public function findUser($username, $g_id)
    {
    	return $this->gruppeMapper->findName($username, $g_id);
    }
    
    
    public function findAllEinladungen($user_id)
    {
    	return $this->gruppeMapper->findAllEinladungen($user_id);
    }
    

    /**
     * @inheritDoc
     */
    public function saveGruppe(GruppeInterface $gruppe)
    {
        return $this->gruppeMapper->save($gruppe);
    }


    public function annehmen($user_id, $g_id)
    {
    	return $this->gruppeMapper->annehmen($user_id, $g_id);
    }
    
    
    public function ablehnen($user_id, $g_id)
    {
    	return $this->gruppeMapper->ablehnen($user_id, $g_id);
    }

    public function isAdmin($user_id, $g_id) {

        return $this->gruppeMapper->isAdmin($user_id, $g_id);
    }

    public function isMitglied($user_id, $g_id) {

        return $this->gruppeMapper->isMitglied($user_id, $g_id);
    }

    public function compare($g_id) {

        return $this->gruppeMapper->compare($g_id);
    }
    
    public function bereitsEingeladen($user_id, $g_id) {
    
    	return $this->gruppeMapper->bereitsEingeladen($user_id, $g_id);
    }
     
   
}