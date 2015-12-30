<?php
/**
 * Created by PhpStorm.
 * User: Meli
 * Date: 05.12.2015
 * Time: 17:35
 */

namespace Mannschaft\Controller;

use Mannschaft\Form\UploadInputFilter;
use Zend\Filter\File\RenameUpload;
use Mannschaft\Service\MannschaftServiceInterface;
use Zend\Form\FormInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Helper\ViewModel;
use Mannschaft\Form\UploadForm;

class WriteController extends AbstractActionController {

    protected $mannschaftService;
    protected $mannschaftForm;

    public function __construct(
        MannschaftServiceInterface $mannschaftService,
        FormInterface $mannschaftForm
    ) {
        $this->mannschaftService = $mannschaftService;
        $this->mannschaftForm = $mannschaftForm;
    }

    public function addAction() {

        $request = $this->getRequest();

        if ($request->isPost()) {
            $this->mannschaftForm->setData($request->getPost());

            if ($this->mannschaftForm->isValid()) {
                try {
                    //\Zend\Debug\Debug::dump($this->mannschaftForm->getData());die();
                    $this->mannschaftService->saveMannschaft($this->mannschaftForm->getData());

                    return $this->redirect()->toRoute('mannschaft');
                } catch (\Exception $e) {
                    die($e->getMessage());
                    //Some DB Error happened, log it and let the user know
                }
            }
        }

        $form = $this->mannschaftForm;

        return array(
            'form' => $form
        );
    }

    public function editAction()
    {
        $request = $this->getRequest();
        $mannschaft  = $this->mannschaftService->findMannschaft($this->params('id'));

        $this->mannschaftForm->bind($mannschaft);

        if ($request->isPost()) {
            $this->mannschaftForm->setData($request->getPost());

            if ($this->mannschaftForm->isValid()) {
                try {
                    $this->mannschaftService->saveMannschaft($mannschaft);

                    return $this->redirect()->toRoute('mannschaft');
                } catch (\Exception $e) {
                    die($e->getMessage());
                    // Some DB Error happened, log it and let the user know
                }
            }
        }

        return array(
            'form' => $this->mannschaftForm
        );
    }

    public function uploadFormAction()
    {
        $form = new UploadForm('upload-form');

        $request = $this->getRequest();
        if ($request->isPost()) {


            $inputFilter = new UploadInputFilter();
            $inputFilter->init();
            $form->setInputFilter($inputFilter);

            $files = $request->getFiles()->toArray();

            $form->setData($files);

            if ($form->isValid()) {

                $filter = new RenameUpload('/srv/www/zend/data/uploads/bla.csv');
                $filter->setOverwrite(true);
                $filter->filter($files['csv-file']);

                return array('servus');
            }
        }

        return array('form' => $form);
    }

    /**
    public function uploadFormAction()
    {

        $request = $this->getRequest();

        if ($request->isPost()) {

            print_r($request->getFiles()->toArray());

                $file = $request->getFiles()->toArray();

                $errors= array();
                $file_name = $file['image']['name'];
                $file_size = $file['image']['size'];
                $file_tmp = $file['image']['tmp_name'];
                $file_type = $file['image']['type'];
                $file_ext=strtolower(end(explode('.',$file['image']['name'])));

                $expensions= array("jpeg","jpg","png");
                if(in_array($file_ext,$expensions)=== false){
                    $errors[]="extension not allowed, please choose a JPEG or PNG file.";
                }
                if($file_size > 2097152){
                    $errors[]='File size must be excately 2 MB';
                }
                if(empty($errors)==true){
                    move_uploaded_file($file_tmp,"/srv/www/zend/data/uploads/".$file_name);
                    echo "Success";
                }else{
                    print_r($errors);
                }
        }
    }**/

}