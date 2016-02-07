<?php
/**
 * Copyright (c) 2012 Jurian Sluiman.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the names of the copyright holders nor the names of the
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package     ZfcAdmin
 * @subpackage  Controller
 * @author      Jurian Sluiman <jurian@soflomo.com>
 * @copyright   2012 Jurian Sluiman.
 * @license     http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link        http://zf-commons.github.com
 */

namespace ZfcAdmin\Controller;

use Spiel\Service\SpielServiceInterface;
use Tipp\Service\TippServiceInterface;
use Mannschaft\Service\MannschaftServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Class AdminController
 * @package ZfcAdmin\Controller
 */
class AdminController extends AbstractActionController
{
    protected $spielService;

    protected $tippService;

    protected $mannschaftService;

    /**
     * @param SpielServiceInterface $spielService
     * @param TippServiceInterface $tippService
     * @param MannschaftServiceInterface $mannschaftService
     */
    public function __construct(
        SpielServiceInterface $spielService,
        TippServiceInterface $tippService,
        MannschaftServiceInterface $mannschaftService
    ) {
        $this->spielService = $spielService;
        $this->tippService = $tippService;
        $this->mannschaftService = $mannschaftService;
    }

    /**
     * Sammelt alle Informationen, die im Admin Dashboard (=index.phtml) verwendet werden
     * @return array
     */
    public function indexAction() {

        $status = $this->spielService->turnierStatus();
        $modus = $this->spielService->getModus();
        $mannschaften = $this->mannschaftService->findAllMannschaften();

        for ($i = 1; $i < 6; $i++){

            $spiele = $this->spielService->findModusSpiele($i);

            $j = 0;

            $buffer = array();

            //Anstatt id die Namen der Mannschaften speichern
            foreach($spiele as $s){

                $name = $this->mannschaftService->findName($s['mannschaft1']);
                $s['mannschaft1'] = $name['name'];

                $name = $this->mannschaftService->findName($s['mannschaft2']);
                $s['mannschaft2'] = $name['name'];

                $buffer[$j] = $s;

                $j++;
            }
            $spieleliste[$i] = $buffer;
        }

        return array(
            'status' => $status[0]['status'],
            'modus' => $modus[0]['modus'],
            'spiele' => $spieleliste,
            'mannschaften' => $mannschaften
        );

    }

    /**
     * Aktiviert das Turnier
     * @return array|\Zend\Http\Response
     */
    public function activateAction()
    {
        $request = $this->getRequest();

        $count = $this->mannschaftService->count();

        if ($count[0]['num'] != 24) {


            $this->flashMessenger()->addErrorMessage('Mannschaften noch nicht vollstaendig (24), erst '.$count[0]['num'].' Mannschaft eingetragen!');

            return $this->redirect()->toRoute('zfcadmin');
        }

        $status = $this->spielService->turnierStatus();

        if ($request->isPost()) {
            $act = $request->getPost('activate_confirmation', 'nein');

            if ($act === 'ja') {
                $this->spielService->activateTurnier();
            }

            return $this->redirect()->toRoute('zfcadmin');
        }

        return array(
            'status' => $status[0]['status'],
        );

    }

    /**
     * Setzt das Turnier zurück
     * @return array|\Zend\Http\Response
     */
    public function resetAction()
    {
        $request = $this->getRequest();

        if ($request->isPost()) {
            $act = $request->getPost('reset_confirmation', 'nein');

            if ($act === 'ja') {
                $a = $this->spielService->reset();
                $b = $this->mannschaftService->delete();
                $c = $this->tippService->resetZusatztipp();
            }

            return $this->redirect()->toRoute('zfcadmin');
        }

        return array(
        );

    }

    /**
     * Aktiviert den nächsten Modus
     * @return array|\Zend\Http\Response
     */
    public function modusAction() {

        $request = $this->getRequest();
        // Holt aktuellen Modus
        $modus = $this->spielService->getModus();

        switch ($modus[0]['modus']) {
            case 0: $m = 'Vorrunde';
                break;
            case 1: $m = 'Achtelfinale';
                break;
            case 2: $m = 'Viertelfinale';
                break;
            case 3: $m = 'Halbfinale';
                break;
            case 4: $m = 'Finale';
                break;
        }

        $anzahl = $this->spielService->count($modus[0]['modus']+1);

        // Überprüft, ob alle Spiele für den zu aktivierenden Modus schon eingetragen sind
        // Und, ob alle Spiele des aktuellen Modus abgeschlossen (= status = 1) sind
        switch ($modus[0]['modus']) {
            case 0;
                if ($anzahl[0]['num'] != 36) {
                    $this->flashMessenger()->addErrorMessage('Noch nicht alle Spiele fuer die Vorrunde eingetragen!');

                    return $this->redirect()->toRoute('zfcadmin');
                }
                break;
            case 1:
                if ($anzahl[0]['num'] != 8) {
                    $this->flashMessenger()->addErrorMessage('Noch nicht alle Spiele fuer das Achtelfinale eingetragen!');

                    return $this->redirect()->toRoute('zfcadmin');
                }
                $spiele = $this->spielService->findModusSpiele(1);
                foreach ($spiele as $s) {
                    if ($s['status'] == 0) {
                        $this->flashMessenger()->addErrorMessage('Mindestens ein Spiel in der Vorrunde nicht abgeschlossen!');

                        return $this->redirect()->toRoute('zfcadmin');
                    }
                }
                break;
            case 2:
                if ($anzahl[0]['num'] != 4) {
                    $this->flashMessenger()->addErrorMessage('Noch nicht alle Spiele fuer das Viertelfinale eingetragen!');

                    return $this->redirect()->toRoute('zfcadmin');
                }
                $spiele = $this->spielService->findModusSpiele(2);
                foreach ($spiele as $s) {
                    if ($s['status'] == 0) {
                        $this->flashMessenger()->addErrorMessage('Mindestens ein Spiel im Achtelfinale nicht abgeschlossen!');

                        return $this->redirect()->toRoute('zfcadmin');
                    }
                }
                break;
            case 3:
                if ($anzahl[0]['num'] != 2) {
                    $this->flashMessenger()->addErrorMessage('Noch nicht alle Spiele fuer das Halbfinale eingetragen!');

                    return $this->redirect()->toRoute('zfcadmin');
                }
                $spiele = $this->spielService->findModusSpiele(3);
                foreach ($spiele as $s) {
                    if ($s['status'] == 0) {
                        $this->flashMessenger()->addErrorMessage('Mindestens ein Spiel im Viertelfinale nicht abgeschlossen!');

                        return $this->redirect()->toRoute('zfcadmin');
                    }
                }
                break;
            case 4:
                if ($anzahl[0]['num'] != 1) {
                    $this->flashMessenger()->addErrorMessage('Noch nicht alle Spiele fuer das Finale eingetragen!');

                    return $this->redirect()->toRoute('zfcadmin');
                }
                $spiele = $this->spielService->findModusSpiele(4);
                foreach ($spiele as $s) {
                    if ($s['status'] == 0) {
                        $this->flashMessenger()->addErrorMessage('Mindestens ein Spiel im Halbfinale nicht abgeschlossen!');

                        return $this->redirect()->toRoute('zfcadmin');
                    }
                }
                break;
        }

        $this->spielService->findModusSpiele($modus[0]['modus']);

        if ($request->isPost()) {
            $act = $request->getPost('modus_confirmation', 'nein');

            if ($act === 'ja') {

                $this->spielService->setModus($modus[0]['modus']+1);
            }

            return $this->redirect()->toRoute('zfcadmin');
        }

        return array(
            'modus' => $m
        );
    }

}
