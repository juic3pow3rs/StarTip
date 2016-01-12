<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 11.12.2015
 * Time: 10:00
 */

namespace Post\Controller;

use Post\Service\PostServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ListController extends  AbstractActionController {

    /**
     * @var \Post\Service\PostServiceInterface
     */
    protected $postService;
    

    public function __construct(PostServiceInterface $postService)
    {
        $this->postService = $postService;
     
    }

    /**
     * @return ViewModel
     * @todo: Index wird nicht benötigt, bei Gelegenheit loeschen.
     */
    public function indexAction()
    {
    	  return new ViewModel(array(
                'posts' => $this->postService->findAllPosts()
            ));
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     * @todo: Wenn aufgerufen und Gruppe hat keine Posts, erscheint weiße Seite!
     */
    public function detailAction()
    {
    	$g_id = $this->params()->fromRoute('g_id');
    	
    	try {
    		$post = $this->postService->findPost($g_id);
    	} catch (\InvalidArgumentException $ex) {
    		return $this->redirect()->toRoute('post');
    	}
    	
    
    	return new ViewModel(array(
    			'liste' => $post,
    			'gruppe' => $g_id
    	));
    }

    
}