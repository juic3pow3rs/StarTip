<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 01.12.2015
 * Time: 10:58
 */

namespace Benutzer\Mapper;

use ZfcUser\Entity\UserInterface;

interface BenutzerMapperInterface {

    /**
     * @return array|UserInterface[]
     */
    public function findAll();

}