<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 14.01.2016
 * Time: 16:23
 */

namespace Gruppe\Controller;

use Gruppe\Service\GruppeServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class DeleteController
 * @package Gruppe\Controller
 */
class DeleteController extends AbstractActionController
{
    
    protected $gruppeService;

	/**
	 * @param GruppeServiceInterface $gruppeService
     */
	public function __construct(GruppeServiceInterface $gruppeService)
    {
        $this->gruppeService = $gruppeService;
    }

	/**
	 * Funktion, um eine Tippgemeinschaft zu verlassen
	 * @return \Zend\Http\Response|ViewModel
     */
	public function deleteAction()
    {
    	//Id des Users holen
    	$user  = $this->zfcUserAuthentication()->getIdentity();
    	$user_id = $user->getId();
    	    	    	
    	try {
    		$gruppe = $this->gruppeService->findGruppe($this->params('g_id'));
    	} catch (\InvalidArgumentException $e) {
    		return $this->redirect()->toRoute('gruppe');
    	}
    	
    	//Prüft ob die übergebene g_id überhaupt existiert
    	$gruppe = $this->gruppeService->findGruppe($this->params('g_id'));
    	if($gruppe==0){
    		$this->flashMessenger()->addErrorMessage('Tippgemeinschaft existiert nicht.');
    		return $this->redirect()->toRoute('gruppe');
    	}
    	
    	//Prüft ob der eingeloggte User der Leiter der Tippgemeinschaft ist
    	if($user_id == $gruppe->getUser_id()){
    		$this->flashMessenger()->addErrorMessage('Sie sind der Leiter der Tippgemeinschaft "'.$gruppe->getName().'" und koennen diese nicht verlassen.');
    		return $this->redirect()->toRoute('gruppe');
    	}
    	
    	//Prüft ob der User überhaupt Mitglied in der Gruppe ist
    	if($this->gruppeService->isMitglied($user_id, $gruppe->getG_id()) == 0){
    		$this->flashMessenger()->addErrorMessage('Sie sind kein Mitglied der Tippgemeinschaft "'.$gruppe->getName().'".');
    		return $this->redirect()->toRoute('gruppe');
    	}
    	
    	
    	$request = $this->getRequest();
    	
    	if ($request->isPost()) {
    		$del = $request->getPost('delete_confirmation', 'nein');
    	
    		if ($del === 'ja') {
    			
    			$this->gruppeService->delete($gruppe->getG_id(), $user_id);
    			$this->flashMessenger()->addSuccessMessage('Mitgliedschaft in der Tippgemeinschaft "'.$gruppe->getName().'" erfolgreich beendet.');
    			 
    		}
    	
    		return $this->redirect()->toRoute('gruppe');
    	}
    	
    	return new ViewModel(array(
    			'gruppe' => $gruppe,
    			'message' => $this->flashMessenger()->getMessages()
    	));
    }
}