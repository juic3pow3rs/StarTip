<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 06.12.2015
 * Time: 15:48
 */

namespace Mannschaft\Service;

use Mannschaft\Mapper\MannschaftMapperInterface;
use Mannschaft\Model\MannschaftInterface;
use Zend\Db\TableGateway\TableGatewayInterface;

/**
 * Class MannschaftService
 * @package Mannschaft\Service
 */
class MannschaftService implements MannschaftServiceInterface {

    protected $mannschaftMapper;

    /**
     * @param MannschaftMapperInterface $mannschaftMapper
     */
    public function __construct(MannschaftMapperInterface $mannschaftMapper)
    {
        $this->mannschaftMapper = $mannschaftMapper;
    }

    /**
     * @inheritDoc
     */
    public function findAllMannschaften()
    {
        return $this->mannschaftMapper->findAll();
    }

    /**
     * @inheritDoc
     */
    public function findMannschaft($m_id)
    {
        return $this->mannschaftMapper->find($m_id);
    }

    /**
     * @inheritDoc
     */
    public function saveMannschaft(MannschaftInterface $mannschaft)
    {
        return $this->mannschaftMapper->save($mannschaft);
    }

    /**
     * @param $m_id
     * @return mixed
     */
    public function findName($m_id)
    {
    	return $this->mannschaftMapper->findName($m_id);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function findId($name) {

        return $this->mannschaftMapper->findId($name);
    }

    public function crawl() {

        return $this->mannschaftMapper->crawl();
    }

    public function delete() {

        return $this->mannschaftMapper->delete();
    }

    public function count() {

        return $this->mannschaftMapper->count();
    }
}