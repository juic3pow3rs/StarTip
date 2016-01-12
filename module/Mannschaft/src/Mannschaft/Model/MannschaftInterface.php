<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 05.12.2015
 * Time: 15:51
 */

namespace Mannschaft\Model;

interface MannschaftInterface {
    /**
     * Will return the ID of the Mannschaft
     *
     * @return int
     */
    public function getM_id();

    /**
     * Will return the NAME of the Mannschaft
     *
     * @return string
     */
    public function getName();

    /**
     * Will return the KUERZEL of the Mannschaft
     *
     * @return string
     */
    public function getKuerzel();
    
    /**
     * Will return the GRUPPE of the Mannschaft
     *
     * @return string
     */
    public function getGruppe();
}