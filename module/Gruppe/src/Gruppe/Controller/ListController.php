<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 10.12.2015
 * Time: 15:40
 */

namespace Gruppe\Controller;

use Gruppe\Service\GruppeServiceInterface;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class ListController
 * @package Gruppe\Controller
 */
class ListController extends  AbstractActionController {

    /**
     * @var \Gruppe\Service\GruppeServiceInterface
     */
    protected $gruppeService;

	/**
	 * @param GruppeServiceInterface $gruppeService
     */
	public function __construct(GruppeServiceInterface $gruppeService)
    {
        $this->gruppeService = $gruppeService;
    }

	/**
	 * Funktion für das auflisten, der Tippgemeinschaften in denen der Benutzer Mitglied ist
	 * @return ViewModel
     */
	public function indexAction()
    
    {
    	$flashMessenger = $this->flashMessenger();
    	if ($flashMessenger->hasMessages()) {
    		$return['messages'] = $flashMessenger->getMessages();
    	}
       
        $user  = $this->zfcUserAuthentication()->getIdentity();
		$user_id = $user->getId();

		return new ViewModel(array(
            'gruppen' => $this->gruppeService->findAllGruppen($user_id),
        	'message' => $this->flashMessenger()->getMessages()
        ));
    }

	/**
	 * Detail-Ansicht einer Tippgemeinschaft
	 * @return \Zend\Http\Response|ViewModel
     */
	public function detailAction()
    {
    	//Id der Gruppe aus der Route
    	$g_id = $this->params()->fromRoute('g_id');
    	
    	//Id des eingeloggten User
    	$user  = $this->zfcUserAuthentication()->getIdentity();
    	$user_id = $user->getId();
    	
    	//Prüft ob die übergebene g_id überhaupt existiert
    	$gruppe = $this->gruppeService->findGruppe($this->params('g_id'));

    	if($gruppe == 0){

			//Fehlermeldung im Flash-Messenger speichern, wird nach Redirect zur Route 'gruppe' angezeigt
    		$this->flashMessenger()->addErrorMessage('Tippgemeinschaft existiert nicht.');
    		return $this->redirect()->toRoute('gruppe');
    	}

		$avatar = $this->gruppeService->getAva($g_id);

    	//Prüft ob der User überhaupt Mitglied in der Tippgemeinschaft ist
    	if($this->gruppeService->isMitglied($user_id, $gruppe->getG_id()) == 0){

    		$this->flashMessenger()->addErrorMessage('Sie sind kein Mitglied der Tippgemeinschaft "'.$gruppe->getName().'".');
    		return $this->redirect()->toRoute('gruppe');
    	}
    	if($this->gruppeService->isMitglied($user_id, $g_id)){

			$gruppe = $this->gruppeService->findGruppe($g_id);

			return new ViewModel(array(
					'gruppe' => $gruppe,
					'avatar' => $avatar

			));
    	}else{
    		return $this->redirect()->toRoute('gruppe');
    	}
    }
    
    /**
     * @return array|ViewModel
     */
    public function compareAction()
    {
        $g_id = $this->params()->fromRoute('g_id');
        $user  = $this->zfcUserAuthentication()->getIdentity();
        $user_id = $user->getId();

        //Prüft ob die übergebene g_id überhaupt existiert
        $gruppe = $this->gruppeService->findGruppe($this->params('g_id'));

        if($gruppe == 0){

        	$this->flashMessenger()->addErrorMessage('Tippgemeinschaft existiert nicht.');
        	return $this->redirect()->toRoute('gruppe');
        }
        //Prüft ob der User überhaupt Mitglied in der Tippgmeinschaft ist
        if($this->gruppeService->isMitglied($user_id, $gruppe->getG_id()) == 0){
        	$this->flashMessenger()->addErrorMessage('Sie sind kein Mitglied der Tippgemeinschaft "'.$gruppe->getName().'".');
        	return $this->redirect()->toRoute('gruppe');
        }
        
        if ($this->gruppeService->isMitglied($user_id, $g_id)){
            $compare = $this->gruppeService->compare($g_id);

            return new ViewModel(array(
                'benutzer' => $compare,
            	'gruppe' => $gruppe,
            	'message' => $this->flashMessenger()->getMessages()
            ));
        }
        return array();

    }


	/**
	 * @return ViewModel
     */
	public function showAction()
    
    {
    	if ($this->zfcUserAuthentication()->hasIdentity()) {
    	$user  = $this->zfcUserAuthentication()->getIdentity();
    	$user_id = $user->getId();
    	}

    	if(count($this->gruppeService->findAllEinladungen($user_id)) == 0)
    	{
    		$fehler='Sie haben im Moment keine Einladungen.';
    	}
    	return new ViewModel(array(
    			'einladungen' => $this->gruppeService->findAllEinladungen($user_id),
    			'fehler' => $fehler
    			
    			
    	));
    
    }

	/**
	 * Funktion, um eine Einladung anzunehmen
	 * @return \Zend\Http\Response
     */
	public function annehmenAction()
    {
    	
    	$g_id = $this->params()->fromRoute('g_id');
    	//Id des eingeloggten User
    	$user  = $this->zfcUserAuthentication()->getIdentity();
    	$user_id = $user->getId();
    	    	    
    	//Prüft ob die übergebene g_id überhaupt existiert
    	$gruppe = $this->gruppeService->findGruppe($this->params('g_id'));
    	if($gruppe == 0){

    		$this->flashMessenger()->addErrorMessage('Tippgemeinschaft existiert nicht.');
    		return $this->redirect()->toRoute('gruppe');
    	}
    	
    	//Prüft ob der User überhaupt eine Einladung bekommen hat
    	$eingeladen = $this->gruppeService->bereitsEingeladen($user_id, $g_id);
    	if($eingeladen == 0){

    		$this->flashMessenger()->addErrorMessage('Sie haben keine Einladung fuer diese Tippgemeinschaft erhalten.');
    		return $this->redirect()->toRoute('gruppe');
    	}
    	    		
    	$this->gruppeService->annehmen($user_id, $g_id);
    	
    	return $this->redirect()->toRoute('gruppe/show');
    
    
    }

	/**
	 * Funktion zum Ablehnen, einer Einladung
	 * @return \Zend\Http\Response
     */
	public function ablehnenAction()
    {
    
    	$g_id = $this->params()->fromRoute('g_id');
    	//Id des eingeloggten User
    	$user  = $this->zfcUserAuthentication()->getIdentity();
    	$user_id = $user->getId();
    	
    	//Prüft ob die übergebene g_id überhaupt existiert
    	$gruppe = $this->gruppeService->findGruppe($this->params('g_id'));
    	if($gruppe == 0){

    		$this->flashMessenger()->addErrorMessage('Tippgemeinschaft existiert nicht.');
    		return $this->redirect()->toRoute('gruppe');
    	}
    	//Prüft ob der User überhaupt eine Einladung bekommen hat
    	$eingeladen = $this->gruppeService->bereitsEingeladen($user_id, $g_id);
    	if($eingeladen == 0){

    		$this->flashMessenger()->addErrorMessage('Sie haben keine Einladung fuer diese Tippgemeinschaft erhalten.');
    		return $this->redirect()->toRoute('gruppe');
    	}
    
    	$this->gruppeService->ablehnen($user_id, $g_id);
    	return $this->redirect()->toRoute('gruppe/show');
    	
    }
}