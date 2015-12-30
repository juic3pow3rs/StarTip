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

    public function find($name);

    public function invite($g_id, $id);
    
    public function such($benutzername);

}