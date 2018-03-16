<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PreviewController extends Controller
{
    /**
     * @Route("/preview", name="preview")
     */
    public function previewAction(Request $request)
    {
        $session = $request->getSession();
        $file = $session->get('media');
        $inputFileName = $file->getPath().$file->getName();
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($inputFileName);

        //$highest_column = $spreadsheet->setActiveSheetIndex(0)->getHighestColumn();
        //$highest_row = $spreadsheet->setActiveSheetIndex(0)->getHighestRow();


        $rows = [];
        foreach ($spreadsheet->getActiveSheet()->getRowIterator() AS $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                $cells = [];


            foreach ($cellIterator as $cell) {
                if($cell->getValue() != NULL){
                    $cells[] = $cell->getValue();
                }


            }
            if ($cells != NULL){
                $rows[] = $cells;
            }

        }

        $session->set('rows',$rows );

        return $this->render('AppBundle:Preview:preview.html.twig', array(
            'rows'=>$rows,
        ));
    }

}
