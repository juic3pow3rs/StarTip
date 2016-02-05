<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 11.12.2015
 * Time: 10:00
 */

namespace Post\Controller;

use Post\Service\PostServiceInterface;
use Gruppe\Service\GruppeServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class ListController
 * @package Post\Controller
 */
class ListController extends  AbstractActionController {

    /**
     * @var \Post\Service\PostServiceInterface
     */
    protected $postService;
    protected $gruppeService;


	/**
	 * @param PostServiceInterface $postService
	 * @param GruppeServiceInterface $gruppeService
     */
	public function __construct(PostServiceInterface $postService, GruppeServiceInterface $gruppeService)
    {
        $this->postService = $postService;
        $this->gruppeService = $gruppeService;
        
     
    }



    /**
     * @return \Zend\Http\Response|ViewModel

	 
     */
    public function detailAction()
    {
    	
    	//Id des Users holen
    	$user  = $this->zfcUserAuthentication()->getIdentity();
    	$user_id = $user->getId();
    	//G_id aus Route holen
    	$g_id = $this->params()->fromRoute('g_id');
    	
    	//Prüft ob die übergebene g_id überhaupt existiert
    	$gruppe = $this->gruppeService->findGruppe($this->params('g_id'));
    	if($gruppe==0){
    		$this->flashMessenger()->addErrorMessage('Tippgemeinschaft existiert nicht.');
    		return $this->redirect()->toRoute('gruppe');
    	}
    	 
    	//Prüft ob der User überhaupt Mitglied in der Tippgemeinschaft ist
    	if($this->gruppeService->isMitglied($user_id, $g_id) == 0){
    	
    		$this->flashMessenger()->addErrorMessage('Sie sind kein Mitglied der Tippgemeinschaft.');
    		return $this->redirect()->toRoute('gruppe');
    	
    	}
    	
    	try {
    		$post=array();
    		$post = $this->postService->findPost($g_id);
    	} catch (\InvalidArgumentException $ex) {
    		return $this->redirect()->toRoute('post');
    	}
    	$flashMessenger = $this->flashMessenger();
    	if ($flashMessenger->hasMessages()) {
    		$return['messages'] = $flashMessenger->getMessages();
    	}
    	
    	
    	  
    
    	return new ViewModel(array(
    			'liste' => $post,
    			'gruppe' => $g_id,
    			'message' => $this->flashMessenger()->getMessages()
    	));
    }

    
}