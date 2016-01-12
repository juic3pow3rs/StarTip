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

class ListController extends  AbstractActionController {

    /**
     * @var \Gruppe\Service\GruppeServiceInterface
     */
    protected $gruppeService;

    public function __construct(GruppeServiceInterface $gruppeService)
    {
        $this->gruppeService = $gruppeService;
    }

    public function indexAction()
    
    {
        //@todo: Abfrage wieder rausnehmen.
    	//$user_id=$this->zfcUserAuthentication()->getIdentity()->getId();
        // Direkter Zugriff auf getId nicht möglich, daher die Lösung wie folgt
       
            $user  = $this->zfcUserAuthentication()->getIdentity();
            $user_id = $user->getId();
        

        //print_r($this->gruppeService->isAdmin($user_id, 5));
        //print_r($this->gruppeService->isMitglied($user_id, 5));

         return new ViewModel(array(
                'gruppen' => $this->gruppeService->findAllGruppen($user_id)
            ));
    }

    /**
     * @return array|ViewModel
     * @todo: Error Handling (redirect zu 403 oder was weiss ich)
     * @todo: ?> in der URL noch drin
     */
    public function compareAction()
    {
        $g_id = $this->params()->fromRoute('g_id');
        $user  = $this->zfcUserAuthentication()->getIdentity();
        $user_id = $user->getId();

        if ($this->gruppeService->isMitglied($user_id, $g_id)){
            $compare = $this->gruppeService->compare($g_id);

            return new ViewModel(array(
                'benutzer' => $compare
            ));
        }
        return array();

    }

    /**
     * @todo: Wenn keine Einladungen vorhanden sind ausgeben, dass man zur zeit keine Einladungen hat
     */
    public function showAction()
    
    {
    	if ($this->zfcUserAuthentication()->hasIdentity()) {
    	$user  = $this->zfcUserAuthentication()->getIdentity();
    	$user_id = $user->getId();
    	}
    	if(count( $this->gruppeService->findAllEinladungen($user_id)) == 0)
    	{
    		return print('Sie haben im Moment keine Einladungen');
    	}
    	return new ViewModel(array(
    			'einladungen' => $this->gruppeService->findAllEinladungen($user_id)
    			
    			
    	));
    
    }
    
    public function annehmenAction()
    {
    	
    	$g_id = $this->params()->fromRoute('g_id');
    	    	
    	if ($this->zfcUserAuthentication()->hasIdentity()) {
    			$user  = $this->zfcUserAuthentication()->getIdentity();
    			$user_id = $user->getId();
    	}
    		
    	$this->gruppeService->annehmen($user_id, $g_id);
    		
    	
    
    	return $this->redirect()->toRoute('gruppe/show');
    
    
    }
    
    public function ablehnenAction()
    {
    
    	$g_id = $this->params()->fromRoute('g_id');
    	
    	if ($this->zfcUserAuthentication()->hasIdentity()) {
    		$user  = $this->zfcUserAuthentication()->getIdentity();
    		$user_id = $user->getId();
    	}
    
    	$this->gruppeService->ablehnen($user_id, $g_id);
    	return $this->redirect()->toRoute('gruppe/show');
    	
    }
}