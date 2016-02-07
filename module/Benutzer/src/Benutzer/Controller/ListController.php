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

/**
 * Class ListController
 * @package Benutzer\Controller
 */
class ListController extends  AbstractActionController {

    /**
     * @var \Benutzer\Service\BenutzerServiceInterface
     */
    protected $benutzerService;

    /**
     * @param BenutzerServiceInterface $benutzerService
     */
    public function __construct(BenutzerServiceInterface $benutzerService)
    {
        $this->benutzerService = $benutzerService;
    }

    /**
     * Funktion für die Rangliste
     * @return ViewModel
     */
    public function listAction()
    {
        $globalRang = $this->benutzerService->findAllBenutzer();
        //print_r($globalRang);

        return new ViewModel(array(
            'benutzer' => $globalRang
        ));
    }

    /**
     * Detail Anzeige des Benutzer Profils
     * @return \Zend\Http\Response|ViewModel
     */
    public function detailAction()
    {
    	$id = $this->params()->fromRoute('id');
        $ava = $this->benutzerService->getAva($id);
    
    	try {
    		$benutzer = $this->benutzerService->findUser($id);
    		
    	} catch (\InvalidArgumentException $ex) {
    		return $this->redirect()->toRoute('user');
    	}
  
    	return new ViewModel(array(
    			'benutzer' => $benutzer,
                'ava' => $ava
    	));
    }
}