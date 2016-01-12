<?php
/**
 * Created by PhpStorm.
 * User: 06.12.2015
 * Time: 15:47
 */

namespace Mannschaft\Service;

use Mannschaft\Model\MannschaftInterface;

interface MannschaftServiceInterface {
    /**
     * Should return a set of all mannschaft that we can iterate over. Single entries of the array or \Traversable object
     * should be of type \Mannschaft\Model\Mannschaft
     *
     * @return array|\Traversable
     */
    public function findAllMannschaften();

    /**
     * Should return a single Mannschaft
     *
     * @param  int $id Identifier of the Mannschaft that should be returned
     * @return \Mannschaft\Model\Mannschaft
     */
    public function findMannschaft($m_id);

    /**
     * Should save a given implementation of the MannschaftInterface and return it. If it is an existing Mannschaft the Mannschaft
     * should be updated, if it's a new Mannschaft it should be created.
     *
     * @param  MannschaftInterface $mannschaft
     * @return MannschaftInterface
     */
    public function saveMannschaft(MannschaftInterface $mannschaft);
    
    public function findName($m_id);

  
}