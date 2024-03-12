<?php
namespace RCare\Rpm\Http\Controllers;
use App\Http\Controllers\Controller;  
use Illuminate\Http\Request;
use mikehaertl\wkhtmlto\Pdf;
  
class PDFController extends Controller
{
    /**
     * Write code on Construct
     *
     * @return \Illuminate\Http\Response
     */
    public function preview()
    {
        return view('Rpm::monthlyService.device-data-report-pdf');
    }
  
    /**
     * Write code on Construct
     *
     * @return \Illuminate\Http\Response
     */
    public function download()
    {
        $render = view('Rpm::monthlyService.device-data-report-pdf')->render();
        $pdf = new Pdf;
        $pdf->addPage($render);
        $pdf->setOptions(['javascript-delay' => 5000]);
        $pdf->saveAs(public_path('report.pdf'));
        return response()->download(public_path('report.pdf'));
    }
}
