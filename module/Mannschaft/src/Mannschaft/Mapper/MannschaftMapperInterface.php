<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 05.12.2015
 * Time: 11:13
 */

namespace Mannschaft\Mapper;

use Mannschaft\Model\MannschaftInterface;

interface MannschaftMapperInterface {

    /**
     * @param int|string $id
     * @return MannschaftInterface
     * @throws \InvalidArgumentException
     */
    public function find($m_id);

    /**
     * @return array|MannschaftInterface[]
     */
    public function findAll();

    /**
     * @param MannschaftInterface $mannschaftObject
     *
     * @param MannschaftInterface $mannschaftobject
     * @return MannschaftInterface
     * @throws \Exception
     */
    public function save(MannschaftInterface $mannschaftObject);
    
    public function findName($m_id);

 
}