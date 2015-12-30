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
     * @param AlbumMapperInterface $albumMapper
     */
    public function __construct(TippMapperInterface $tippMapper)
    {
        $this->tippMapper = $tippMapper;
    }

    /**
     * @inheritDoc
     */
    public function findAllTipps()
    {
        return $this->tippMapper->findAll();
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
    public function saveTipp(TippInterface $tipp)
    {
        return $this->tippMapper->save($tipp);
    }

    public function updateZusatztipp($id, $status) {

        return $this->tippMapper->updateZusatztipp($id, $status);
    }

    public function addZusatztipp($id, $user_id, $m_id) {

        return $this->tippMapper->addZusatztipp($id, $user_id, $m_id);
    }

    public function isActive() {

        return $this->tippMapper->isActive();
    }
}