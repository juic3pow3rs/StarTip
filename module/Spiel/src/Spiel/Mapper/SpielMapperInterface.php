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
    
    public function spielStatus($s_id);

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
    
    public function findTippSpiele($user_id);

    public function activateTurnier();

    public function turnierStatus();

    public function setModus($m);

    public function getModus();
}