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

/**
 * Class GruppeService
 * @package Gruppe\Service
 */
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

    /**
     * @param $name
     * @return mixed
     */
    public function pruefGruppe($name)
    {
    	return $this->gruppeMapper->pruefGruppe($name);
    }

    /**
     * @param $username
     * @param $g_id
     * @return mixed
     */
    public function findUser($username, $g_id)
    {
    	return $this->gruppeMapper->findName($username, $g_id);
    }


    /**
     * @param $user_id
     * @return mixed
     */
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


    /**
     * @param $user_id
     * @param $g_id
     * @return mixed
     */
    public function annehmen($user_id, $g_id)
    {
    	return $this->gruppeMapper->annehmen($user_id, $g_id);
    }


    /**
     * @param $user_id
     * @param $g_id
     * @return mixed
     */
    public function ablehnen($user_id, $g_id)
    {
    	return $this->gruppeMapper->ablehnen($user_id, $g_id);
    }

    /**
     * @param $user_id
     * @param $g_id
     * @return mixed
     */
    public function isAdmin($user_id, $g_id) {

        return $this->gruppeMapper->isAdmin($user_id, $g_id);
    }

    /**
     * @param $user_id
     * @param $g_id
     * @return mixed
     */
    public function isMitglied($user_id, $g_id) {

        return $this->gruppeMapper->isMitglied($user_id, $g_id);
    }

    /**
     * @param $g_id
     * @return mixed
     */
    public function compare($g_id) {

        return $this->gruppeMapper->compare($g_id);
    }

    /**
     * @param $user_id
     * @param $g_id
     * @return mixed
     */
    public function bereitsEingeladen($user_id, $g_id) {
    
    	return $this->gruppeMapper->bereitsEingeladen($user_id, $g_id);
    }

    /**
     * @param $g_id
     * @param $user_id
     * @return mixed
     */
    public function delete($g_id, $user_id)
    {
    	return $this->gruppeMapper->delete($g_id, $user_id);
    }

    /**
     * @param $id
     * @param $url
     * @return mixed
     */
    public function setAva($id, $url) {

        return $this->gruppeMapper->setAva($id, $url);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getAva($id) {

        return $this->gruppeMapper->getAva($id);
    }
   
}