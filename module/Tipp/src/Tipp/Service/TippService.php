<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 14.12.2015
 * Time: 15:48
 */

namespace Tipp\Service;

use Tipp\Mapper\TippMapperInterface;
use Tipp\Model\TippInterface;
use Zend\Db\TableGateway\TableGatewayInterface;

class TippService implements TippServiceInterface {

    protected $tippMapper;

    /**
     * @param TippMapperInterface $tippMapper
     */
    public function __construct(TippMapperInterface $tippMapper)
    {
        $this->tippMapper = $tippMapper;
    }

    /**
     * @inheritDoc
     */
    public function findAllTipps($user_id)
    {
        return $this->tippMapper->findAllTipps($user_id);
    }

    /**
     * @inheritDoc
     */
    public function findTipp($t_id)
    {
        return $this->tippMapper->find($t_id);
    }

    /**
     * @inheritDoc
     */
    public function saveTipp(TippInterface $tipp, $s_id)
    {
        return $this->tippMapper->save($tipp, $s_id);
    }

    public function updateZusatztipp($id, $status) {

        return $this->tippMapper->updateZusatztipp($id, $status);
    }

    public function addZusatztipp($id, $user_id, $m_id) {

        return $this->tippMapper->addZusatztipp($id, $user_id, $m_id);
    }

    public function setZusatztipp($id, $m_id) {

        return $this->tippMapper->setZusatztipp($id, $m_id);
    }

    public function zusatzPunkteBerechnen($id){

        return $this->tippMapper->zusatzPunkteBerechnen($id);
    }

    public function isActive() {

        return $this->tippMapper->isActive();
    }

    public function punkteBerechnen($s_id) {

        return $this->tippMapper->punkteBerechnen($s_id);
    }
    
    public function tippAbgegeben($s_id, $user_id) {
    
    	return $this->tippMapper->tippAbgegeben($s_id, $user_id);
    }
    
   
}