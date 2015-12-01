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

class BenutzerService implements BenutzerServiceInterface {

    protected $benutzerMapper;

    /**
     * @param BenutzerMapperInterface $albumMapper
     */
    public function __construct(BenutzerMapperInterface $benutzerMapper)
    {
        $this->benutzerMapper = $benutzerMapper;
    }

    /**
     * @inheritDoc
     */
    public function findAllBenutzer()
    {
        return $this->benutzerMapper->findAll();
    }

    /**
     * @inheritDoc
     */
    public function findBenutzer($id)
    {
        return $this->benutzerMapper->find($id);
    }
}