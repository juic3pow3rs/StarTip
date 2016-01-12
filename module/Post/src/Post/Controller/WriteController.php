<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 11.12.2015
 * Time: 11:19
 */

namespace Post\Controller;

use Post\Service\PostServiceInterface;
use Zend\Form\FormInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class WriteController extends AbstractActionController {

    protected $postService;
    protected $postForm;

    public function __construct(
        PostServiceInterface $postService,
        FormInterface $postForm
    ) {
        $this->postService = $postService;
        $this->postForm = $postForm;
    }
    
   /**
    * @todo Nach einfügen eines neuen Eintrags nicht auf die Index Seite sondern zum Forum der Gruppe
    */

    public function addAction() {
    	
    	$request = $this->getRequest();
    	$g_id = $this->params()->fromRoute('g_id');
        if ($request->isPost()) {
            $this->postForm->setData($request->getPost());

            if ($this->postForm->isValid()) {
                try {
                    $this->postService->savePost($this->postForm->getData(),$g_id);

                    return $this->redirect()->toRoute('post/detail', array('g_id' => ($this->params('g_id'))));
                } catch (\Exception $e) {
                    die($e->getMessage());
                    //Some DB Error happened, log it and let the user know
                }
            }
        }

       
        $form = $this->postForm;

        return array(
            'form' => $form,
        	        	
        );
    }

   
}