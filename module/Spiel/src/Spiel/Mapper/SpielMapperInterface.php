<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 03.12.2015
 * Time: 11:01
 */

namespace Spiel\Mapper;

use Spiel\Model\SpielInterface;

interface SpielMapperInterface {

    /**
     * @param int|string $id
     * @return SpielInterface
     * @throws \InvalidArgumentException
     */
    public function find($s_id);

    /**
     * @return array|SpielInterface[]
     */
    public function findAll();

    /**
     * @param SpielInterface $spielObject
     *
     * @param SpielInterface $spielObject
     * @return SpielInterface
     * @throws \Exception
     */
    public function save(SpielInterface $spielObject);

    /**
     * @param SpielInterface $spielObject
     *
     * @return bool
     * @throws \Exception
     */
    
}