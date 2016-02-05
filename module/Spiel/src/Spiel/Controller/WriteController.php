<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 03.12.2015
 * Time: 11:19
 */

namespace Spiel\Controller;

use Mannschaft\Service\MannschaftServiceInterface;
use Spiel\Service\SpielServiceInterface;
use Spiel\Model\Spiel;
use Tipp\Service\TippServiceInterface;
use Zend\Form\FormInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\View;

/**
 * Class WriteController
 * @package Spiel\Controller
 */
class WriteController extends AbstractActionController {

    protected $spielService;
    protected $spielForm;
    protected $tippService;
    protected $mannschaftService;

    /**
     *
     * @param SpielServiceInterface $spielService
     * @param FormInterface $spielForm
     * @param TippServiceInterface $tippService
     * @param MannschaftServiceInterface $mannschaftService
     */
    public function __construct(
        SpielServiceInterface $spielService,
        FormInterface $spielForm,
        TippServiceInterface $tippService,
        MannschaftServiceInterface $mannschaftService
    ) {
        $this->spielService = $spielService;
        $this->spielForm = $spielForm;
        $this->tippService = $tippService;
        $this->mannschaftService = $mannschaftService;
    }

    /**
     * @return array|\Zend\Http\Response
     * @todo: Checken, ob Mannschaft 1 oder 2 schon in einer Partie eingetragen (Ab 8tel Finale)
     */
    public function addAction() {

        $turnierstatus = $this->spielService->turnierStatus();

        if ($turnierstatus[0]['status'] == 0) {

            $this->flashMessenger()->addErrorMessage('Turnier nicht aktiv. Spiel eintragen nicht möglich!');

            return $this->redirect()->toRoute('zfcadmin');
        }

        $modus = $this->spielService->getModus();
        $spielecount = $this->spielService->count($modus[0]['modus']+1);

        switch ($modus[0]['modus']) {
            case 0:
                if ($spielecount[0]['num'] == 36) {
                    $this->flashMessenger()->addErrorMessage('Bereits alle Spiele fuer die Vorrunde eingetragen');

                    return $this->redirect()->toRoute('zfcadmin');
                }
                break;
            case 1:
                if ($spielecount[0]['num'] == 8) {
                    $this->flashMessenger()->addErrorMessage('Bereits alle Spiele fuer das 8tel-Finale eingetragen');

                    return $this->redirect()->toRoute('zfcadmin');
                }
                break;
            case 2:
                if ($spielecount[0]['num'] == 4) {
                    $this->flashMessenger()->addErrorMessage('Bereits alle Spiele fuer das 4tel-Finale eingetragen');

                    return $this->redirect()->toRoute('zfcadmin');
                }
                break;
            case 3:
                if ($spielecount[0]['num'] == 2) {
                    $this->flashMessenger()->addErrorMessage('Bereits alle Spiele fuer das Halb-Finale eingetragen"');

                    return $this->redirect()->toRoute('zfcadmin');
                }
                break;
            case 4:
                if ($spielecount[0]['num'] == 1) {
                    $this->flashMessenger()->addErrorMessage('Finale bereits eingetragen"');

                    return $this->redirect()->toRoute('zfcadmin');
                }
                break;
        }

        $this->spielForm->get('spiel-fieldset')->get('modus')->setValue($modus[0]['modus']+1);
        $this->spielForm->get('spiel-fieldset')->get('tore1')->setAttributes(array('disabled' => 'disabled'));
        $this->spielForm->get('spiel-fieldset')->get('tore2')->setAttributes(array('disabled' => 'disabled'));
        $this->spielForm->get('spiel-fieldset')->get('punkte1')->setAttributes(array('disabled' => 'disabled'));
        $this->spielForm->get('spiel-fieldset')->get('punkte2')->setAttributes(array('disabled' => 'disabled'));
        $this->spielForm->get('spiel-fieldset')->get('gelb1')->setAttributes(array('disabled' => 'disabled'));
        $this->spielForm->get('spiel-fieldset')->get('gelb2')->setAttributes(array('disabled' => 'disabled'));
        $this->spielForm->get('spiel-fieldset')->get('rot1')->setAttributes(array('disabled' => 'disabled'));
        $this->spielForm->get('spiel-fieldset')->get('rot2')->setAttributes(array('disabled' => 'disabled'));

        $request = $this->getRequest();

        if ($request->isPost()) {
            $this->spielForm->setData($request->getPost());

            $mannschaft1 = $this->spielForm->get('spiel-fieldset')->get('mannschaft1')->getValue();
            $mannschaft2 = $this->spielForm->get('spiel-fieldset')->get('mannschaft2')->getValue();

            $check = 0;

            if ($mannschaft1 == $mannschaft2) {

                $this->spielForm->get('spiel-fieldset')->get('mannschaft2')->setMessages(array('2x gleiche Mannschaft ausgewaehlt!'));
                $check = 1;
            }

            $status = $this->spielForm->get('spiel-fieldset')->get('status')->getValue();

            if ($status == 1) {

                $this->spielForm->get('spiel-fieldset')->get('status')->setMessages(array('Beendetes Spiel anlegen nicht moeglich!'));
                $check = 1;
            }

            $datetime = $this->spielForm->get('spiel-fieldset')->get('anpfiff')->getValue();

            $valid = preg_match('/20\d{2}(-)((0[1-9])|(1[0-2]))(-)((0[1-9])|([1-2][0-9])|(3[0-1]))(\s)(([0-1][0-9])|(2[0-3])):([0-5][0-9]):([0-5][0-9])/', $datetime);

            if ($valid == 0) {

                $this->spielForm->get('spiel-fieldset')->get('anpfiff')->setMessages(array('Ungueltige Eingabge, nur folgendes Format erlaubt (ab 2000): 2014-04-01 12:00:00'));
                $check = 1;
            }

            if ($this->spielForm->isValid() && $check == 0) {
                try {

                    $this->spielService->saveSpiel($this->spielForm->getData());

                    $this->flashMessenger()->addSuccessMessage('Spiel erfolgreich angelegt.');

                    return $this->redirect()->toRoute('zfcadmin');
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

    /**
     * @return array|\Zend\Http\Response
     */
    public function editAction()
    {
        $request = $this->getRequest();
        $spiel   = $this->spielService->findSpiel($this->params('id'));

        if ($spiel->getStatus() == 1) {

            $this->flashMessenger()->addErrorMessage('Spiel abgeschlossen. Editieren nicht mehr moeglich!');

            return $this->redirect()->toRoute('zfcadmin');
        }

        $this->spielForm->bind($spiel);

        if ($request->isPost()) {
            $this->spielForm->setData($request->getPost());

            $mannschaft1 = $this->spielForm->get('spiel-fieldset')->get('mannschaft1')->getValue();
            $mannschaft2 = $this->spielForm->get('spiel-fieldset')->get('mannschaft2')->getValue();
            $status = $this->spielForm->get('spiel-fieldset')->get('status')->getValue();

            $check = 0;

            if ($mannschaft1 == $mannschaft2) {

                $this->spielForm->get('spiel-fieldset')->get('mannschaft2')->setMessages(array('2x gleiche Mannschaft ausgewaehlt!'));
                $check = 1;
            }

            $tore1 = $this->spielForm->get('spiel-fieldset')->get('tore1')->getValue();
            $tore2 = $this->spielForm->get('spiel-fieldset')->get('tore2')->getValue();
            $punkte1 = $this->spielForm->get('spiel-fieldset')->get('punkte1')->getValue();
            $punkte2 = $this->spielForm->get('spiel-fieldset')->get('punkte2')->getValue();

            if ($tore1 != 0 || $tore2 != 0 || $punkte1 != 0 || $punkte2 != 0) {

                $this->spielForm->get('spiel-fieldset')->get('tore1')->setMessages(array('Bitte "Ergebnis eintragen"-Funktion nutzen, um Ergebnis einzutragen'));
                $this->spielForm->get('spiel-fieldset')->get('tore2')->setMessages(array('Bitte "Ergebnis eintragen"-Funktion nutzen, um Ergebnis einzutragen'));
                $this->spielForm->get('spiel-fieldset')->get('punkte1')->setMessages(array('Bitte "Ergebnis eintragen"-Funktion nutzen, um Ergebnis einzutragen'));
                $this->spielForm->get('spiel-fieldset')->get('punkte2')->setMessages(array('Bitte "Ergebnis eintragen"-Funktion nutzen, um Ergebnis einzutragen'));
                $check = 1;
            }

            if ($status == 1) {

                $this->spielForm->get('spiel-fieldset')->get('status')->setMessages(array('Bitte "Ergebnis eintragen"-Funktion nutzen, um Spiel abzuschließen!'));
                $check = 1;
            }

            $datetime = $this->spielForm->get('spiel-fieldset')->get('anpfiff')->getValue();

            $valid = preg_match('/20\d{2}(-)((0[1-9])|(1[0-2]))(-)((0[1-9])|([1-2][0-9])|(3[0-1]))(\s)(([0-1][0-9])|(2[0-3])):([0-5][0-9]):([0-5][0-9])/', $datetime);

            if ($valid == 0) {

                $this->spielForm->get('spiel-fieldset')->get('anpfiff')->setMessages(array('Ungueltige Eingabge, nur folgendes Format erlaubt (ab 2000): 2014-04-01 12:00:00'));
                $check = 1;
            }

            if ($this->spielForm->isValid() && $check == 0) {
                try {
                    $this->spielService->saveSpiel($spiel);

                    $this->flashMessenger()->addSuccessMessage('Spiel erfolgreich bearbeitet!!');

                    return $this->redirect()->toRoute('zfcadmin');

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

    /**
     * @return array|\Zend\Http\Response
     */
    public function ergAction()
    {
        $request = $this->getRequest();
        $spiel   = $this->spielService->findSpiel($this->params('id'));

        $this->spielForm->bind($spiel);

        $this->spielForm->get('spiel-fieldset')->get('modus')->setAttributes(array('disabled' => 'disabled'));
        $this->spielForm->get('spiel-fieldset')->get('anpfiff')->setAttributes(array('disabled' => 'disabled'));
        $this->spielForm->get('spiel-fieldset')->get('status')->setValue(1);


        if ($request->isPost()) {
            $this->spielForm->setData($request->getPost());

            $mannschaft1 = $this->spielForm->get('spiel-fieldset')->get('mannschaft1')->getValue();
            $mannschaft2 = $this->spielForm->get('spiel-fieldset')->get('mannschaft2')->getValue();
            $status = $this->spielForm->get('spiel-fieldset')->get('status')->getValue();
            $tore1 = $this->spielForm->get('spiel-fieldset')->get('tore1')->getValue();
            $tore2 = $this->spielForm->get('spiel-fieldset')->get('tore2')->getValue();
            $punkte1 = $this->spielForm->get('spiel-fieldset')->get('punkte1')->getValue();
            $punkte2 = $this->spielForm->get('spiel-fieldset')->get('punkte2')->getValue();

            $check = 0;

            if ($mannschaft1 != $spiel->getMannschaft1()) {

                $this->spielForm->get('spiel-fieldset')->get('mannschaft1')->setMessages(array('Mannschaft aendern nicht erlaubt!'));
                $check = 1;
            } elseif ($mannschaft2 != $spiel->getMannschaft2()) {

                $this->spielForm->get('spiel-fieldset')->get('mannschaft2')->setMessages(array('Mannschaft aendern nicht erlaubt!'));
                $check = 1;
            }

            if (($tore1 > $tore2 && $punkte1 <= $punkte2) || ($tore1 < $tore2 && $punkte1 >= $punkte2) || ($tore1 == $tore2 && ($punkte1 != 1 || $punkte2 != 1))) {

                $this->spielForm->get('spiel-fieldset')->get('tore1')->setMessages(array('Ergebnisse und Punkte muessen konsistent sein!'));
                $this->spielForm->get('spiel-fieldset')->get('tore2')->setMessages(array('Ergebnisse und Punkte muessen konsistent sein!'));
                $this->spielForm->get('spiel-fieldset')->get('punkte1')->setMessages(array('Ergebnisse und Punkte muessen konsistent sein!'));
                $this->spielForm->get('spiel-fieldset')->get('punkte2')->setMessages(array('Ergebnisse und Punkte muessen konsistent sein!'));
                $check = 1;
            }

            if ($status != 1) {

                $this->spielForm->get('spiel-fieldset')->get('status')->setMessages(array('Ergebnis eintragen, ohne Spiel zu beenden nicht erlaubt!'));
                $check = 1;
            }

            if ($this->spielForm->isValid() && $check == 0) {
                try {
                    $this->spielService->saveSpiel($spiel);

                    //If Spielstatus = 1 (enstpricht beendet)
                    //Hier Punkte berechnen Funktion aufrufen
                    if ($spiel->getStatus() == 1) {
                        $this->tippService->punkteBerechnen($spiel->getS_id());
                    }

                    $this->flashMessenger()->addSuccessMessage('Ergebnis eingetragen und Punkte berechnet!');

                    return $this->redirect()->toRoute('zfcadmin');
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

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function crawlAction()
    {

        $modus = $this->spielService->getModus();
        $modus = $modus[0]['modus'] + 1;

        $request = $this->getRequest();

        if ($request->isPost()) {
            $crwl = $request->getPost('crawl_confirmation', 'nein');

            if ($crwl === 'ja') {

                $spiele = $this->spielService->crawl($modus);

                foreach ($spiele as $s) {

                    $m1 = $this->mannschaftService->findId($s[0]);
                    $m2 = $this->mannschaftService->findId($s[1]);

                    if (empty($m1)) {

                        $this->flashMessenger()->addErrorMessage('Crawlen abgebrochen, bitte Mannschaft "'.$s[0].'" eintragen');

                        return $this->redirect()->toRoute('spiel');
                    } elseif (empty($m2)) {
                        $this->flashMessenger()->addErrorMessage('Crawlen abgebrochen, bitte Mannschaft "'.$s[1].'" eintragen!');

                        return $this->redirect()->toRoute('spiel');
                    }
                }

                $this->spielService->deleteModus($modus);

                foreach ($spiele as $s) {

                    $m1 = $this->mannschaftService->findId($s[0]);
                    $m2 = $this->mannschaftService->findId($s[1]);

                    $game = new Spiel;

                    $game->setMannschaft1($m1[0]['m_id']);
                    $game->setMannschaft2($m2[0]['m_id']);
                    $game->setModus($modus);
                    $game->setTore1(0);
                    $game->setTore2(0);
                    $game->setGelb1(0);
                    $game->setGelb2(0);
                    $game->setRot1(0);
                    $game->setRot2(0);
                    $game->setPunkte1(0);
                    $game->setPunkte2(0);
                    $game->setAnpfiff(date('Y-m-d H:i:s', strtotime($s[2])));
                    $game->setStatus(0);

                    $this->spielService->saveSpiel($game);
                }


                $this->flashMessenger()->addSuccessMessage('Crawlen erfolgreich!');

                return $this->redirect()->toRoute('zfcadmin');
            }

            return $this->redirect()->toRoute('zfcadmin');
        }


        return new ViewModel(array(
            'modus' => $modus
        ));
    }

}