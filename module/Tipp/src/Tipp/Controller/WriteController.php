<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 14.12.2015
 * Time: 17:35
 */

namespace Tipp\Controller;

use Tipp\Service\TippServiceInterface;
use Mannschaft\Service\MannschaftServiceInterface;
use Spiel\Service\SpielServiceInterface;
use Zend\Form\FormInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Helper\ViewModel;

class WriteController extends AbstractActionController {

    protected $tippService;
    protected $tippForm;
    protected $updateZusatztippForm;
    protected $zusatztippForm;
    protected $spielService;
    protected $mannschaftService;

    public function __construct(
        TippServiceInterface $tippService,
        FormInterface $tippForm,
        FormInterface $updateZusatztippForm,
        FormInterface $zusatztippForm,
    	SpielServiceInterface $spielService,
    	MannschaftServiceInterface $mannschaftService
    ) {
        $this->tippService = $tippService;
        $this->tippForm = $tippForm;
        $this->updateZusatztippForm = $updateZusatztippForm;
        $this->zusatztippForm = $zusatztippForm;
        $this->spielService = $spielService;
        $this->mannschaftService = $mannschaftService;
    }

    /**
     * @return array|\Zend\Http\Response
     * @todo: Statt nur Mannschaft-IDs auch Namen ausgeben lassen
     */
    public function addAction() {

    	//s_id aus der Route holen
    	$s_id   =  $this->params()->fromRoute('s_id');
    	$request = $this->getRequest();

    	//Id des Users holen
    	$user  = $this->zfcUserAuthentication()->getIdentity();
    	$user_id = $user->getId();
    	
    	//Infos zunm Spiel mit übergebenenb Id
    	$spiel = $this->spielService->findSpiel($s_id);
    	 
    	//Namen der Beiden Manschaften holen
    	$mannschaft1=$this->mannschaftService->findName($spiel->getMannschaft1());
    	$mannschaft2=$this->mannschaftService->findName($spiel->getMannschaft2());
    	        
        ////Prüft ob das Spiel bereits stattgefunden hat
        $status=$this->spielService->spielStatus($s_id);
       	$abgelaufen= count($status);
   		 if($abgelaufen != 0){
      	  return print('Dieses Spiel ist bereits zu Ende');
        }
                
        //Prüft ob bereichts ein Tipp zu diesen Spiel abgegeben wurde
        $abgegeben=array();
        $abgegeben= $this->tippService->tippAbgegeben($s_id, $user_id);
        $getippt=count($abgegeben);
         
        if($getippt != 0){
      	  return print('Sie haben auf dieses Spiel bereits getippt');
        }
       
       
     
      
        if ($request->isPost() ) {
        	
            $this->tippForm->setData($request->getPost());
            
           
            if ($this->tippForm->isValid()) {
            	try {
            		
            		 $this->tippService->saveTipp($this->tippForm->getData(),$s_id);
            		 
            
            		return $this->redirect()->toRoute('tipp');
            	} catch (\Exception $e) {
            		print ($e->getMessage());
            	
            		//Some DB Error happened, log it and let the user know
            	}
            }
        }

        $form = $this->tippForm;

        return array(
            'form' => $form,
            'spiel' => $spiel,
        	'mannschaft1' => $mannschaft1,
        	'mannschaft2' => $mannschaft2
        );
    }

    public function editAction()
    {
    	//t_id aus der Route holen
        $request = $this->getRequest();
        $tipp   = $this->tippService->findTipp($this->params('t_id'));

        //Infos zunm Spiel mit auf das getippt wurde
        $spiel = $this->spielService->findSpiel($tipp->getS_id());
        
        //Namen der Beiden Manschaften holen
        $mannschaft1=$this->mannschaftService->findName($spiel->getMannschaft1());
        $mannschaft2=$this->mannschaftService->findName($spiel->getMannschaft2());
         
        
        //Infos des Tipps mit der t_id in die Form übergeben
        $this->tippForm->bind($tipp);

        if ($request->isPost()) {
            $this->tippForm->setData($request->getPost());

            if ($this->tippForm->isValid()) {
                try {
                    $this->tippService->saveTipp($tipp, $tipp->getS_id());

                    return $this->redirect()->toRoute('tipp');
                } catch (\Exception $e) {
                    print ($e->getMessage());
                    // Some DB Error happened, log it and let the user know
                }
            }
        }

        return array(
            'form' => $this->tippForm,
        	'spiel' => $spiel,
        	'mannschaft1' => $mannschaft1,
        	'mannschaft2' => $mannschaft2
        );
    }

    
    /**
     * @return array|\Zend\Http\Response
     * @todo: Abfrage, ob Turnier schon gestartet
     */
    public function updateZusatztippAction() {

        $request = $this->getRequest();

        if ($request->isPost()) {
            $this->updateZusatztippForm->setData($request->getPost());

            if ($this->updateZusatztippForm->isValid()) {
                try {
                    $this->tippService->updateZusatztipp('1', $this->updateZusatztippForm->get('status1')->getValue());
                    $this->tippService->updateZusatztipp('2', $this->updateZusatztippForm->get('status2')->getValue());
                    $this->tippService->updateZusatztipp('3', $this->updateZusatztippForm->get('status3')->getValue());
                    $this->tippService->updateZusatztipp('4', $this->updateZusatztippForm->get('status4')->getValue());
                    $this->tippService->updateZusatztipp('5', $this->updateZusatztippForm->get('status5')->getValue());
                    $this->tippService->updateZusatztipp('6', $this->updateZusatztippForm->get('status6')->getValue());

                    return $this->redirect()->toRoute('home');
                } catch (\Exception $e) {
                    die($e->getMessage());
                    //Some DB Error happened, log it and let the user know
                }
            }
        }

        $form = $this->updateZusatztippForm;

        return array(
            'form' => $form
        );
    }

    /**
     * @return array|\Zend\Http\Response
     * @todo: Abfrage, ob Turnier aktiv und ob Vorrunde schon gestartet und ob Zusatztipp schon abgegeben wurde!
     */
    public function addZusatztippAction() {

        $request = $this->getRequest();

        if ($this->zfcUserAuthentication()->hasIdentity()) {
            $user  = $this->zfcUserAuthentication()->getIdentity();
            $user_id = $user->getId();
        }

        if ($request->isPost()) {

            $this->zusatztippForm->setData($request->getPost());

            if ($this->zusatztippForm->isValid()) {

                try {

                    $i = 0;
                    $stati = $this->tippService->isActive();
                    $status = array();

                    foreach ($stati as $s) {
                        $status[++$i] = $s['status'];
                    }

                    if ($status[1]) $this->tippService->addZusatztipp('1', $user_id, $this->zusatztippForm->get('platz1')->getValue());
                    if ($status[2]) $this->tippService->addZusatztipp('2', $user_id, $this->zusatztippForm->get('platz2')->getValue());
                    if ($status[3]) $this->tippService->addZusatztipp('3', $user_id, $this->zusatztippForm->get('platz3')->getValue());
                    if ($status[4]) $this->tippService->addZusatztipp('4', $user_id, $this->zusatztippForm->get('fair')->getValue());
                    if ($status[5]) $this->tippService->addZusatztipp('5', $user_id, $this->zusatztippForm->get('unfair')->getValue());
                    if ($status[6]) $this->tippService->addZusatztipp('6', $user_id, $this->zusatztippForm->get('tore')->getValue());

                    return $this->redirect()->toRoute('home');

                    } catch (\Exception $e) {
                        die($e->getMessage());
                        //Some DB Error happened, log it and let the user know
                    }
                }
        }

        $form = $this->zusatztippForm;

        return array(
            'form' => $form
        );
    }

    public function setZusatztippAction() {

        $request = $this->getRequest();

        if ($request->isPost()) {

            $this->zusatztippForm->setData($request->getPost());

            if ($this->zusatztippForm->isValid()) {

                try {

                    $i = 0;
                    $stati = $this->tippService->isActive();
                    $status = array();

                    foreach ($stati as $s) {
                        $status[++$i] = $s['status'];
                    }

                    if ($status[1]) {
                        $this->tippService->setZusatztipp('1', $this->zusatztippForm->get('platz1')->getValue());
                        $this->tippService->zusatzPunkteBerechnen(1);
                    }
                    if ($status[2]) {
                        $this->tippService->setZusatztipp('2', $this->zusatztippForm->get('platz2')->getValue());
                        $this->tippService->zusatzPunkteBerechnen(2);
                    }
                    if ($status[3]) {
                        $this->tippService->setZusatztipp('3', $this->zusatztippForm->get('platz3')->getValue());
                        $this->tippService->zusatzPunkteBerechnen(3);
                    }
                    if ($status[4]) {
                        $this->tippService->setZusatztipp('4', $this->zusatztippForm->get('fair')->getValue());
                        $this->tippService->zusatzPunkteBerechnen(4);
                    }
                    if ($status[5]) {
                        $this->tippService->setZusatztipp('5', $this->zusatztippForm->get('unfair')->getValue());
                        $this->tippService->zusatzPunkteBerechnen(5);
                    }
                    if ($status[6]) {
                        $this->tippService->setZusatztipp('6', $this->zusatztippForm->get('tore')->getValue());
                        $this->tippService->zusatzPunkteBerechnen(6);
                    }

                    return $this->redirect()->toRoute('home');

                } catch (\Exception $e) {
                    die($e->getMessage());
                    //Some DB Error happened, log it and let the user know
                }
            }
        }

        $form = $this->zusatztippForm;

        return array(
            'form' => $form
        );
    }


}