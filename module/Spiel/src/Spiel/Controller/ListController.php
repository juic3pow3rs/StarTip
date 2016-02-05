<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 03.12.2015
 * Time: 10:00
 */

namespace Spiel\Controller;

use Spiel\Service\SpielServiceInterface;
use Tipp\Service\TippServiceInterface;
use Mannschaft\Service\MannschaftServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class ListController
 * @package Spiel\Controller
 */
class ListController extends  AbstractActionController {

    
    protected $spielService;
    protected $mannschaftService;
    protected $tippService;
    
/**
 * 
 * @param SpielServiceInterface $spielService
 * @param MannschaftServiceInterface $mannschaftService
 * @param TippServiceInterface $tippService
 */
    public function __construct(SpielServiceInterface $spielService, MannschaftServiceInterface $mannschaftService,
    		TippServiceInterface $tippService)
    {
        $this->spielService = $spielService;
        $this->mannschaftService = $mannschaftService;
        $this->tippService = $tippService;

     
    }

    /**
     * @return ViewModel
     
     * @todo: Nur Spiele vom aktuellen + vergangenen Modus anzeigen!
     * Idee: zusätzlich zum Spiele Array ein Array mit allen Mannschaften (ID => Name) an das ViewModel übergeben,
     * das MannschaftServiceInterface muss halt wie das SpielServiceInterface noch Injected werden (einmal hier im Controller
     * angeben und einmal in der Factory)
     */
    public function indexAction()
    {
        $turnierstatus = $this->spielService->turnierStatus();

        $modus = $this->spielService->getModus();

        for ($i = 1; $i < 6; $i++){

            $spiele = $this->spielService->findModusSpiele($i);

            $j = 0;

            $buffer = array();

            //Anstatt id die Namen der Mannschaften speichern
            foreach($spiele as $s){

                $name = $this->mannschaftService->findName($s['mannschaft1']);
                $s['mannschaft1'] = $name['name'];

                $name = $this->mannschaftService->findName($s['mannschaft2']);
                $s['mannschaft2'] = $name['name'];

                $buffer[$j] = $s;

                $j++;
            }
            $spieleliste[$i] = $buffer;
        }
    	
    	//id des eingologten Users
    	$user  = $this->zfcUserAuthentication()->getIdentity();
    	$user_id = $user->getId();
    	
    	//Tipps des Users
    	$getippt = array();
    	$getippt = $this->tippService->findAllTipps($user_id);

    	return new ViewModel(array(
    	  	'getippt' => $getippt,
            'turnierstatus' => $turnierstatus,
            'modus' => $modus[0]['modus'],
            'spiele' => $spieleliste
            ));
    }

    /**
     * Detailansicht für Spiel
     */
    public function detailAction()
    {
    	//Spiel id aus der Route holen
        $s_id = $this->params()->fromRoute('id');

        try {
            $spiel = $this->spielService->findSpiel($s_id);
        } catch (\InvalidArgumentException $ex) {
            return $this->redirect()->toRoute('spiel');
        }

        return new ViewModel(array(
            'spiel' => $spiel
        ));
    }
}