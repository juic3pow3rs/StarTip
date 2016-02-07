<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 11.12.2015
 * Time: 11:19
 */

namespace Post\Controller;

use Post\Service\PostServiceInterface;
use Gruppe\Service\GruppeServiceInterface;
use Zend\Form\FormInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class WriteController
 * @package Post\Controller
 */
class WriteController extends AbstractActionController {

    protected $postService;
    protected $gruppeService;
    protected $postForm;

    /**
     * @param PostServiceInterface $postService
     * @param GruppeServiceInterface $gruppeService
     * @param FormInterface $postForm
     */
    public function __construct(
        PostServiceInterface $postService,
    	GruppeServiceInterface $gruppeService,
        FormInterface $postForm
    ) {
        $this->postService = $postService;
        $this->gruppeService = $gruppeService;
        $this->postForm = $postForm;
    }


    /**
     * Funktion zum Anlegen eines neuen Posts
     * @return array|\Zend\Http\Response
     */
    public function addAction() {
    	
      	//Id des Users holen
      	$user  = $this->zfcUserAuthentication()->getIdentity();
      	$user_id = $user->getId();
      	
      	//G_id aus der Route holen
    	$request = $this->getRequest();
    	$g_id = $this->params()->fromRoute('g_id');
    	
    	//Prüft ob die übergebene g_id überhaupt existiert
    	$gruppe = $this->gruppeService->findGruppe($this->params('g_id'));
    	if($gruppe==0){
    		$this->flashMessenger()->addErrorMessage('Tippgemeinschaft existiert nicht.');
    		return $this->redirect()->toRoute('gruppe');
    	}
    	
    	//Prüft ob der User überhaupt Mitglied in der Tippgemeinschaft ist
    	if($this->gruppeService->isMitglied($user_id, $g_id) == 0){
    		
    			$this->flashMessenger()->addErrorMessage('Sie sind kein Mitglied der Tippgemeinschaft "'.$gruppe->getName().'"und koennen deshalb keinen Beitrag hinterlassen.');
    			return $this->redirect()->toRoute('gruppe');
    		
    	}
    	
    	//Prüft ob das Formular bereits abgesendet wurde und legt wenn möglich den Beitrag an
        if ($request->isPost()) {
            $this->postForm->setData($request->getPost());

            if ($this->postForm->isValid()) {
                try {
                    $this->postService->savePost($this->postForm->getData(),$g_id);
                    $this->flashMessenger()->addSuccessMessage('Der Beitrag wurde erfolgreich hinterlassen.');

                    return $this->redirect()->toRoute('post/detail', array('g_id' => ($this->params('g_id'))));
                } catch (\Exception $e) {
                    die($e->getMessage());
                    
                }
            }
        }

        $form = $this->postForm;

        return array(
            'form' => $form,
        	'message' => $this->flashMessenger()->getMessages()
        	        	
        );
    }
}