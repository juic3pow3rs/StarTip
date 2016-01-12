<?php
/**
 * Created by PhpStorm.
 * User: Andi
 * Date: 01.12.2015
 * Time: 10:34
 */

namespace Benutzer\Controller;

use Benutzer\Service\BenutzerServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ListController extends  AbstractActionController {

    /**
     * @var \Benutzer\Service\BenutzerServiceInterface
     */
    protected $benutzerService;

    public function __construct(BenutzerServiceInterface $benutzerService)
    {
        $this->benutzerService = $benutzerService;
    }

    public function listAction()
    {
        $globalRang = $this->benutzerService->findAllBenutzer();
        //print_r($globalRang);

        return new ViewModel(array(
            'benutzer' => $globalRang
        ));
    }
    
    public function detailAction()
    {
    	$id = $this->params()->fromRoute('id');
    
    	try {
    		$benutzer = $this->benutzerService->findUser($id);
    		
    	} catch (\InvalidArgumentException $ex) {
    		return $this->redirect()->toRoute('user');
    	}
  
    	return new ViewModel(array(
    			'benutzer' => $benutzer
    	));
    }
}