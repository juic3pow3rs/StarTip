<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 13.12.2015
 * Time: 15:40
 */

namespace Tipp\Controller;

use Tipp\Service\TippServiceInterface;
use Spiel\Service\SpielServiceInterface;
use Mannschaft\Service\MannschaftServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ListController extends  AbstractActionController {

    /**
     * @var \Album2\Service\AlbumServiceInterface
     */
    protected $tippService;

    public function __construct(TippServiceInterface $tippService, 
    		SpielServiceInterface $spielService,
    		MannschaftServiceInterface $mannschaftService)
    {
        $this->tippService = $tippService;
        $this->spielService = $spielService;
        $this->mannschaftService = $mannschaftService;
    }

    public function indexAction()
    {
    	//Id des Users 
    	$user  = $this->zfcUserAuthentication()->getIdentity();
    	$user_id = $user->getId();
    	
    	//Alle Tipps des Users mit der user_id
    	$tipps=array();
    	$tipps=$this->tippService->findAllTipps($user_id);
    	
    	//Anstelle der Mannschafts_id den Namen der Mannschaft für alle Tipps speichern
    	$i=0;
    	$test=array();
    	
    	foreach($tipps as $t){
    		
    		$name=array();
    		$name=$this->mannschaftService->findName($t['mannschaft1']);
    		$t['mannschaft1']=$name['name'];
    		
    		
    		$name=array();
    		$name=$this->mannschaftService->findName($t['mannschaft2']);
    		$t['mannschaft2']=$name['name'];
    		
    		$test[$i]=$t;
    		$i++;
    		
    	 
    	}
    	
    	//Infos aller Spiele auf die der User getippt hat holen
    	$spiele=$this->spielService->findTippSpiele($user_id);
    	
            return new ViewModel(array(
                'tipps' => $test,
            	'spiele' => $spiele
            ));
    }

    public function detailAction()
    {
    	//Id des Tipps aus der Route
        $t_id = $this->params()->fromRoute('t_id');

        //Prüfen ob es den Tipp mit der id gibt
        try {
            $tipp= $this->tippService->findTipp($t_id);
        } catch (\InvalidArgumentException $ex) {
            return $this->redirect()->toRoute('tipp');
        }

        //Spiel zur t_id laden
        $spiel=$this->spielService->findSpiel($tipp->getS_id());
   
        $mannschaft1=$this->mannschaftService->findName($spiel->getMannschaft1());
     
     	
     	$mannschaft2=$this->mannschaftService->findName($spiel->getMannschaft2());
   
        
        return new ViewModel(array(
            'tipp' => $tipp,
        	'spiel' => $spiel,
        	'mannschaft1' => $mannschaft1,
        	'mannschaft2' => $mannschaft2
        		
        	
        ));
    }
}