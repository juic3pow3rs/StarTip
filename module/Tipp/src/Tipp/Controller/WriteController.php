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

/**
 * Class WriteController
 * @package Tipp\Controller
 */
class WriteController extends AbstractActionController {

    protected $tippService;

    protected $tippForm;

    protected $updateZusatztippForm;

    protected $zusatztippForm;

    protected $spielService;

    protected $mannschaftService;

    /**
     * @param TippServiceInterface $tippService
     * @param FormInterface $tippForm
     * @param FormInterface $updateZusatztippForm
     * @param FormInterface $zusatztippForm
     * @param SpielServiceInterface $spielService
     * @param MannschaftServiceInterface $mannschaftService
     */
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
     * Anlegen eines Tipps
     * @return array|\Zend\Http\Response
     */
    public function addAction() {
    	
    	//s_id aus der Route holen
    	$s_id   =  $this->params()->fromRoute('s_id');
    	$request = $this->getRequest();

        $turnierstatus = $this->spielService->turnierStatus();

        if ($turnierstatus[0]['status'] == 0) {

            $this->flashMessenger()->addErrorMessage('Tippen nicht moeglich. Turnier nicht aktiv');

            return  $this->redirect()->toRoute('tipp');
        }

    	//Id des Users holen
    	$user  = $this->zfcUserAuthentication()->getIdentity();
    	$user_id = $user->getId();
    	
    	//Infos zunm Spiel mit übergebenenb Id
    	$spiel = $this->spielService->findSpiel($s_id);
    	 
    	//Namen der Beiden Manschaften holen
    	$mannschaft1 = $this->mannschaftService->findName($spiel->getMannschaft1());
    	$mannschaft2 = $this->mannschaftService->findName($spiel->getMannschaft2());

        $anpfiff = $spiel->getAnpfiff();
        $today = date("Y-m-d H:i:s");

       if ($anpfiff < $today) {

           $this->flashMessenger()->addErrorMessage('Tippen nicht mehr moeglich. Der Anpfiff liegt in der Vergangenheit');

           return  $this->redirect()->toRoute('tipp');
       }

        //Prüft ob das Spiel bereits stattgefunden hat
        $status = $spiel->getStatus();
   		 if($status == 1){

             $this->flashMessenger()->addErrorMessage('Spiel bereits abgeschlossen!');

             return  $this->redirect()->toRoute('spiel');
        }
                
        //Prüft ob bereichts ein Tipp zu diesen Spiel abgegeben wurde
        $abgegeben = array();
        $abgegeben = $this->tippService->tippAbgegeben($s_id, $user_id);
        $getippt = count($abgegeben);
         
        if($getippt != 0){

            $this->flashMessenger()->addErrorMessage('Sie haben auf dieses Spiel bereits getippt!');

            return  $this->redirect()->toRoute('tipp');
        }

      //Prüft ob Formular ausgefült wurde und der Datensatz gespeichert werden kann
        if ($request->isPost() ) {
        	
            $this->tippForm->setData($request->getPost());

            if ($this->tippForm->isValid()) {
            	try {
            		
            		 $this->tippService->saveTipp($this->tippForm->getData(),$s_id);
            		 $this->flashMessenger()->addSuccessMessage('Der Tipp wurde erfolgreich abgegeben.');
            		
            		return  $this->redirect()->toRoute('tipp');
            	} catch (\Exception $e) {
            		$fehler= $e->getMessage();
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

    /**
     * Editiert einen Tipp
     * @return array|\Zend\Http\Response
     */
    public function editAction()
    {
        $turnierstatus = $this->spielService->turnierStatus();

        if ($turnierstatus[0]['status'] == 0) {

            $this->flashMessenger()->addErrorMessage('Tipp bearbeiten nicht moeglich. Turnier nicht aktiv');

            return  $this->redirect()->toRoute('tipp');
        }

        //t_id aus der Route holen
        $request = $this->getRequest();
        $tipp   = $this->tippService->findTipp($this->params('t_id'));

        //Infos zunm Spiel mit auf das getippt wurde
        $spiel = $this->spielService->findSpiel($tipp->getS_id());
        
        $anpfiff = $spiel->getAnpfiff();
        $today = date("Y-m-d H:i:s");
        
        if ($anpfiff < $today) {

            $this->flashMessenger()->addErrorMessage('Tipp bearbeiten nicht mehr moeglich. Der Anpfiff liegt in der Vergangenheit');

            return  $this->redirect()->toRoute('tipp');
        }
        
        //Namen der Beiden Manschaften holen
        $mannschaft1 = $this->mannschaftService->findName($spiel->getMannschaft1());
        $mannschaft2 = $this->mannschaftService->findName($spiel->getMannschaft2());
        
        //Prüft ob das Spiel bereits stattgefunden hat
        $status = $spiel->getStatus();
        if($status == 1){

            $this->flashMessenger()->addErrorMessage('Spiel bereits abgeschlossen!');

            return  $this->redirect()->toRoute('tipp');
        }
        
        //Infos des Tipps mit der t_id in die Form übergeben
        $this->tippForm->bind($tipp);

        //Prüft ob Formular abgeschickt wurde und Datensatz geändert werden konnte
        if ($request->isPost()) {
            $this->tippForm->setData($request->getPost());

            if ($this->tippForm->isValid()) {
                try {

                    $this->tippService->saveTipp($tipp, $tipp->getS_id());
                    $this->flashMessenger()->addSuccessMessage('Der Tipp wurde erfolgreich geaendert.');

                    return $this->redirect()->toRoute('tipp');
                } catch (\Exception $e) {
                    $fehler= $e->getMessage();
                    
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
     * Aktualisiert (aktiviert/deaktiviert) die Zusatipps
     * @return array|\Zend\Http\Response
     */
    public function updateZusatztippAction() {

        $status = $this->spielService->turnierStatus();

        if ($status[0]['status'] == 1) {

            $this->flashMessenger()->addErrorMessage('Zusatztipp aktualisieren nicht mehr möglich. Turnier bereits aktiviert');

            return  $this->redirect()->toRoute('zfcadmin');
        }

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

                    $this->flashMessenger()->addSuccessMessage('Zusatztipp aktualisieren erfolgreich!');

                    return $this->redirect()->toRoute('zfcadmin');
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
     * Gibt Zusatztipps ab
     * @return array|\Zend\Http\Response
     */
    public function addZusatztippAction() {

        $request = $this->getRequest();

        if ($this->zfcUserAuthentication()->hasIdentity()) {
            $user  = $this->zfcUserAuthentication()->getIdentity();
            $user_id = $user->getId();
        }

        $turnierstatus = $this->spielService->turnierStatus();
        $modus = $this->spielService->getModus();
        $zusatztipp = $this->tippService->getZusatztipp($user_id);
        $stati = $this->tippService->isActive();
        $i = 0;

        foreach ($stati as $s) {
            $status[++$i] = $s['status'];
        }

        // Falls Zusatztipps schon einmal abgeben, Zusatztipps aus der DB holen und in der Form setzen
        if (!empty($zusatztipp)) {

            // Iterator über die Stati der Zusatztipps, wenn 1 (=aktiv) Wert aus der DB in der Form setzen
            foreach($zusatztipp as $z) {
                $i = $z['z_id'];
                if ($status[$i] == 1) {

                    switch($i) {
                        case 1:
                            $this->zusatztippForm->get('platz1')->setValue($z['m_id']);
                            break;
                        case 2:
                            $this->zusatztippForm->get('platz2')->setValue($z['m_id']);
                            break;
                        case 3:
                            $this->zusatztippForm->get('platz3')->setValue($z['m_id']);
                            break;
                        case 4:
                            $this->zusatztippForm->get('fair')->setValue($z['m_id']);
                            break;
                        case 5:
                            $this->zusatztippForm->get('unfair')->setValue($z['m_id']);
                            break;
                        case 6:
                            $this->zusatztippForm->get('tore')->setValue($z['m_id']);
                            break;
                    }

                }
            }
        }

        if ($turnierstatus[0]['status'] == 0) {

            $this->flashMessenger()->addErrorMessage('Turnier nicht aktiv. Zusatztipp-Abgabe nicht moeglich');

            return $this->redirect()->toRoute('tipp');

        } elseif ($modus[0]['modus'] > 0) {

            // Modus des Turniers ist Vorrunde oder später, Zusatztipps abgeben nicht mehr möglich
            // => Felder deaktivieren
            // if-Abfrage zum überprüfen, ob Zusatztipp aktiv
            if ($status[1]) $this->zusatztippForm->get('platz1')->setAttributes(array('disabled' => 'disabled'));
            if ($status[2]) $this->zusatztippForm->get('platz2')->setAttributes(array('disabled' => 'disabled'));
            if ($status[3]) $this->zusatztippForm->get('platz3')->setAttributes(array('disabled' => 'disabled'));
            if ($status[4]) $this->zusatztippForm->get('fair')->setAttributes(array('disabled' => 'disabled'));
            if ($status[5]) $this->zusatztippForm->get('unfair')->setAttributes(array('disabled' => 'disabled'));
            if ($status[6]) $this->zusatztippForm->get('tore')->setAttributes(array('disabled' => 'disabled'));
            $this->zusatztippForm->get('submit')->setAttributes(array('disabled' => 'disabled'));

            $message = 1;
        }

        if ($request->isPost()) {

            $this->zusatztippForm->setData($request->getPost());

            if ($this->zusatztippForm->isValid()) {

                try {
                    // if-Abfrage zum überprüfen, ob Zusatztipp aktiv
                    if ($status[1]) $this->tippService->addZusatztipp('1', $user_id, $this->zusatztippForm->get('platz1')->getValue());
                    if ($status[2]) $this->tippService->addZusatztipp('2', $user_id, $this->zusatztippForm->get('platz2')->getValue());
                    if ($status[3]) $this->tippService->addZusatztipp('3', $user_id, $this->zusatztippForm->get('platz3')->getValue());
                    if ($status[4]) $this->tippService->addZusatztipp('4', $user_id, $this->zusatztippForm->get('fair')->getValue());
                    if ($status[5]) $this->tippService->addZusatztipp('5', $user_id, $this->zusatztippForm->get('unfair')->getValue());
                    if ($status[6]) $this->tippService->addZusatztipp('6', $user_id, $this->zusatztippForm->get('tore')->getValue());

                    $this->flashMessenger()->addSuccessMessage('Zusatztipp erfolgreich abgegeben!');

                    return $this->redirect()->toRoute('home');

                    } catch (\Exception $e) {
                        die($e->getMessage());
                        //Some DB Error happened, log it and let the user know
                    }
                }
        }

        $form = $this->zusatztippForm;

        return array(
            'form' => $form,
            'message' => $message
        );
    }

    /**
     * Ergebnis der Zusatztipps eintragen und berechnen lassen.
     * @return array|\Zend\Http\Response
     */
    public function setZusatztippAction() {

        $spiel = $this->spielService->findModusSpiele(5);

        if ($spiel[0]['status'] == 0) {

            $this->flashMessenger()->addErrorMessage('Zusatztipp Ergebnis eintragen nicht moeglich. Finale noch nicht abgeschlossen!');

            return  $this->redirect()->toRoute('zfcadmin');
        }

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

                    // if-Abfragen zum überprüfen, ob Zusatztipp aktiv
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

                    return $this->redirect()->toRoute('zfcadmin');

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