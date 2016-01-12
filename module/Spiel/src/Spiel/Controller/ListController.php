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

class ListController extends  AbstractActionController {

    /**
     * @var \Spiel\Service\SpielServiceInterface
     */
    protected $spielService;
    protected $mannschaftService;
    protected $tippService;
    

    public function __construct(SpielServiceInterface $spielService, MannschaftServiceInterface $mannschaftService,
    		TippServiceInterface $tippService)
    {
        $this->spielService = $spielService;
        $this->mannschaftService = $mannschaftService;
        $this->tippService = $tippService;

     
    }

    /**
     * @return ViewModel
     * @todo: findAllSpiele() so umschreiben, dass auch die Namen der Mannschaften mit ausgegeben werden!
     * @todo: Nur Spiele vom aktuellen + vergangenen Modus anzeigen!
     * Idee: zusätzlich zum Spiele Array ein Array mit allen Mannschaften (ID => Name) an das ViewModel übergeben,
     * das MannschaftServiceInterface muss halt wie das SpielServiceInterface noch Injected werden (einmal hier im Controller
     * angeben und einmal in der Factory)
     */
    public function indexAction()
    {
    	

        /**
         * Array mit Index Name und Wert ID erstellen:
         *
         * $mannschaften = $this->mannschaftService;
         * $liste = array();
         * foreach ($mannschaften as $m)
         * $liste[$m->getName()] => $m->getM_id();
         *
         *Diese Liste dann im ViewModel noch mit übergeben und im Template dann an der Stelle "echo $s->getMannschaft1()",
         * "echo $liste[$s->getMannschaft1()];" schreiben, dann müsste der Name ausgegeben werden
         */
    	
    	
    	$spiele=$this->spielService->findAllSpiele();
    	
    	$i=0;
    	$test=array();
    	foreach($spiele as $s){
    	
    		$name=array();
    		$name=$this->mannschaftService->findName($s['mannschaft1']);
    		$s['mannschaft1']=$name['name'];
    		
    		$name=array();
    		$name=$this->mannschaftService->findName($s['mannschaft2']);
    		$s['mannschaft2']=$name['name'];
    		
    		$test[$i]=$s;
    		$i++;
    	}
    	
    	$user  = $this->zfcUserAuthentication()->getIdentity();
    	$user_id = $user->getId();
    	$getippt=array();
    	$getippt=$this->tippService->findAllTipps($user_id);

		
    
    	  return new ViewModel(array(
                'spiele' =>$test,
    	  		'getippt' =>$getippt,
            ));
    }

    public function detailAction()
    {
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