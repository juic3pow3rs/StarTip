<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 03.12.2015
 * Time: 10:15
 */

namespace Spiel\Service;

use Spiel\Mapper\SpielMapperInterface;
use Spiel\Model\SpielInterface;
use Zend\Db\TableGateway\TableGatewayInterface;

class SpielService implements SpielServiceInterface {

    protected $spielMapper;

    /**
     * @param SpielMapperInterface $spielMapper
     */
    public function __construct(SpielMapperInterface $spielMapper)
    {
        $this->spielMapper = $spielMapper;
    }

    /**
     * @inheritDoc
     */
    public function findAllSpiele()
    {
        return $this->spielMapper->findAll();
    }

    /**
     * @inheritDoc
     */
    public function findSpiel($s_id)
    {
        return $this->spielMapper->find($s_id);
    }
    
    public function spielStatus($s_id)
    {
    	return $this->spielMapper->spielStatus($s_id);
    }

    /**
     * @inheritDoc
     */
    public function saveSpiel(SpielInterface $spiel)
    {
        return $this->spielMapper->save($spiel);
    }
    
    public function findTippSpiele($user_id)
    {
    	return $this->spielMapper->findTippSpiele($user_id);
    }
   
    public function activateTurnier() {

        return $this->spielMapper->activateTurnier();
    }

    public function turnierStatus() {

        return $this->spielMapper->turnierStatus();
    }

    public function setModus($m) {

        return $this->spielMapper->setModus($m);
    }

    public function getModus() {

        return $this->spielMapper->getModus();
    }
    
}