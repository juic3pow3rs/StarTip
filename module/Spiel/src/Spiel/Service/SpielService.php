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

/**
 * Class SpielService
 * @package Spiel\Service
 */
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

    /**
     * @param $s_id
     * @return mixed
     */
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

    /**
     * @param $user_id
     * @return bool
     */
    public function findTippSpiele($user_id)
    {
    	return $this->spielMapper->findTippSpiele($user_id);
    }

    /**
     * @param $modus
     * @return mixed
     */
    public function findModusSpiele($modus) {

        return $this->spielMapper->findModusSpiele($modus);
    }
   
    public function activateTurnier() {

        return $this->spielMapper->activateTurnier();
    }

    /**
     * @return mixed
     */
    public function deactivateTurnier() {

        return $this->spielMapper->deactivateTurnier();
    }

    public function turnierStatus() {

        return $this->spielMapper->turnierStatus();
    }

    /**
     * @param $m
     * @return mixed
     */
    public function setModus($m) {

        return $this->spielMapper->setModus($m);
    }

    public function getModus() {

        return $this->spielMapper->getModus();
    }

    /**
     * @param $modus
     * @return mixed
     */
    public function crawl($modus) {

        return $this->spielMapper->crawl($modus);
    }

    /**
     * @param $modus
     * @return mixed
     */
    public function deleteModus($modus) {

        return $this->spielMapper->deleteModus($modus);
    }

    /**
     * @param $modus
     * @return mixed
     */
    public function count($modus)
    {

        return $this->spielMapper->count($modus);
    }

    public function reset() {

        return $this->spielMapper->reset();
    }
}