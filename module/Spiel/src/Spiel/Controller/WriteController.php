<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 03.12.2015
 * Time: 11:19
 */

namespace Spiel\Controller;

use Spiel\Service\SpielServiceInterface;
use Tipp\Service\TippServiceInterface;
use Zend\Form\FormInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\View;

class WriteController extends AbstractActionController {

    protected $spielService;
    protected $spielForm;
    protected $tippService;

    /**
     * @param SpielServiceInterface $spielService
     * @param FormInterface $spielForm
     */
    public function __construct(
        SpielServiceInterface $spielService,
        FormInterface $spielForm,
        TippServiceInterface $tippService
    ) {
        $this->spielService = $spielService;
        $this->spielForm = $spielForm;
        $this->tippService = $tippService;
    }

    /**
     * @return array|\Zend\Http\Response
     * @todo: Unterbinden das eine Mannschaft zwei mal ausgewählt werden kann!
     */
    public function addAction() {

        $request = $this->getRequest();

        if ($request->isPost()) {
            $this->spielForm->setData($request->getPost());

            if ($this->spielForm->isValid()) {
                try {
                    $this->spielService->saveSpiel($this->spielForm->getData());

                    return $this->redirect()->toRoute('spiel');
                } catch (\Exception $e) {
                    die($e->getMessage());
                    //Some DB Error happened, log it and let the user know
                }
            }
        }

        $form = $this->spielForm;

        return array(
            'form' => $form
        );
    }

    public function editAction()
    {
        $request = $this->getRequest();
        $spiel   = $this->spielService->findSpiel($this->params('id'));

        $this->spielForm->bind($spiel);

        if ($request->isPost()) {
            $this->spielForm->setData($request->getPost());

            if ($this->spielForm->isValid()) {
                try {
                    $this->spielService->saveSpiel($spiel);

                    //If Spielstatus = 1 (enstpricht beendet)
                    //Hier Punkte berechnen Funktion aufrufen
                    if ($spiel->getStatus() == 1) {
                        $this->tippService->punkteBerechnen($spiel->getS_id());
                    }

                    //return $this->redirect()->toRoute('spiel');
                } catch (\Exception $e) {
                    die($e->getMessage());
                    // Some DB Error happened, log it and let the user know
                }
            }
        }

        return array(
            'form' => $this->spielForm
        );
    }

}