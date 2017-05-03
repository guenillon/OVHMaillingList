<?php
namespace MaillingListBundle\Export;

class JPIExport
{	
	protected $phpExcelService;
	protected $translator;
	protected $phpExcelObject;
	
	public function __construct(\Liuggio\ExcelBundle\Factory $phpExcelService, $translator) {
		$this->phpExcelService = $phpExcelService;
		$this->translator = $translator;
	}

	public function export($title, $format, $header, $data)
  	{
  		$title = $this->translator->transChoice($title, count($data));
  		
  		// Création du phpExcel
  		$this->phpExcelObject = $this->phpExcelService->createPHPExcelObject();
  		
  		// Le titre de l'onglet
  		$this->phpExcelObject->getActiveSheet()->setTitle($title);

  		// Le header
  		$i = 'A';
  		foreach($header as $nom) {
  			$nom = $this->translator->trans($nom);
  			$this->phpExcelObject->setActiveSheetIndex(0)->setCellValue($i.'1', $nom)->getColumnDimension($i)->setAutoSize(true);
  			$i++;
  		}
  		
  		// Les données
  		$i = 2;
  		$col = 'A';
  		foreach($data as $litem) {
  			$this->phpExcelObject->setActiveSheetIndex(0)->setCellValue($col.$i, $litem);
  			$i++;
  		}

	  	// L'onglet actif est le premier
	  	$this->phpExcelObject->setActiveSheetIndex(0);

	  	return $this->exportHeader($title, $format);
	}
		
	private function exportHeader($title, $format) {
		// writer selon le format
		switch($format) {
			case "xls":
				$writer = $this->phpExcelService->createWriter($this->phpExcelObject, 'Excel5');
				break;
				 
			case "ods":
				$writer = $this->phpExcelService->createWriter($this->phpExcelObject, 'OpenDocument');
				break;
				 
			case "csv":
				$writer = $this->phpExcelService->createWriter($this->phpExcelObject, 'CSV')
				->setUseBOM(true)
				->setDelimiter(',')
				->setEnclosure('"')
				->setLineEnding("\r\n")
				->setSheetIndex(0);
				break;
				 
			case "xlsx":
			default:
				$writer = $this->phpExcelService->createWriter($this->phpExcelObject, 'Excel2007');
				break;
		}

		// creation de la reponse
		$response = $this->phpExcelService->createStreamedResponse($writer);
		
		// header selon le format
		switch($format) {
			case "xls":
				$response->headers->set('Content-Type', 'application/vnd.ms-excel');
				$response->headers->set('Content-Disposition', 'attachment;filename="'. $title .'.xls"');
				$response->headers->set('Cache-Control', 'max-age=0');
				break;
				 
			case "ods":
				$response->headers->set('Content-Type', 'application/vnd.oasis.opendocument.spreadsheet');
				$response->headers->set('Content-Disposition', 'attachment;filename="'. $title .'.ods"');
				// If you're serving to IE 9, then the following may be needed
				$response->headers->set('Cache-Control', 'max-age=1');
				// If you're serving to IE over SSL, then the following may be needed
				$response->headers->set ('Expires', 'Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
				$response->headers->set ('Last-Modified', gmdate('D, d M Y H:i:s').' GMT'); // always modified
				$response->headers->set ('Cache-Control', 'cache, must-revalidate'); // HTTP/1.1
				$response->headers->set ('Pragma', 'public'); // HTTP/1.0
				break;
				 
			case "csv":
				// Les headers
				$response->headers->set('Content-Encoding', 'UTF-8');
				$response->headers->set('Content-type', 'text/csv; charset=UTF-8');
				$response->headers->set('Content-Disposition', 'attachment; filename="'. $title .'.csv"');
				$response->headers->set('Cache-Control', 'max-age=0');
				break;
				 
			case "xlsx":
			default:
				// Les headers
				$response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				$response->headers->set('Content-Disposition', 'attachment;filename="'. $title .'.xlsx"');
				$response->headers->set('Cache-Control', 'max-age=1');
				 
				$response->headers->set('Expires', 'Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
				$response->headers->set('Last-Modified', gmdate('D, d M Y H:i:s').' GMT'); // always modified
				$response->headers->set('Cache-Control', 'cache, must-revalidate'); // HTTP/1.1
				$response->headers->set('Pragma', 'public');
				break;
		}
		
		return $response;
	}
}
?>
