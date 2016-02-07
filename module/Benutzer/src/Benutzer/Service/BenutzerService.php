<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 01.12.2015
 * Time: 11:11
 */

namespace Benutzer\Service;

use Benutzer\Mapper\BenutzerMapperInterface;
use ZfcUser\Entity\UserInterface;
use Zend\Db\TableGateway\TableGatewayInterface;

/**
 * Class BenutzerService
 * @package Benutzer\Service
 */
class BenutzerService implements BenutzerServiceInterface {

    protected $benutzerMapper;

    /**
     * @param BenutzerMapperInterface $benutzerMapper
     */
    public function __construct(BenutzerMapperInterface $benutzerMapper)
    {
        $this->benutzerMapper = $benutzerMapper;
    }

    /**
     * @return array|\ZfcUser\Entity\UserInterface[]
     */
    public function findAllBenutzer()
    {
        return $this->benutzerMapper->findAll();
    }


    /**
     * @param int $name
     * @return mixed
     */
    public function findBenutzer($name)
    {
        return $this->benutzerMapper->find($name);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findUser($id)
    {
    	return $this->benutzerMapper->findUser($id);
    }


    /**
     * @param $g_id
     * @param $id
     * @param $leiter
     * @return mixed
     */
    public function inviteBenutzer($g_id, $id, $leiter) {

        return $this->benutzerMapper->invite($g_id, $id, $leiter);
    }

    /**
     * @param $benutzername
     * @return mixed
     */
    public function suchBenutzer($benutzername) {
    	
    	return $this->benutzerMapper->such($benutzername);
    }

    /**
     * @param $id
     * @param $url
     * @return mixed
     */
    public function setAva($id, $url) {

        return $this->benutzerMapper->setAva($id, $url);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getAva($id) {

        return $this->benutzerMapper->getAva($id);
    }

}