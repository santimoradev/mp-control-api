<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\CoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\IOFactory;
use PhpOffice\PhpPresentation\Shape\Drawing\File;
use App\Queries\PhotosReportQuery;

use Sentinel;
use Carbon\Carbon;

use App\Models\User;

class ExportPhotosController extends CoreController
{
  public function aditionals(Request $request)
  {
    $take = ['dates', 'provinceId', 'cityId'];

    $input = $request->only($take);
    $query = PhotosReportQuery::getAditionals($input);
    $data = $query
          ->limit(50)
          ->get()->toArray();

    $presentation = $this->generate($data);

    $writer = IOFactory::createWriter($presentation, 'PowerPoint2007');

    $filename = 'report-aditionals-';
    $filename .= date('Y_m_d_H_i_s');

    return response()->streamDownload(function () use ($writer) {
        $writer->save('php://output');
    }, $filename.'.pptx');
  }


  public function generate(array $observations)
  {
        $presentation = new PhpPresentation();

        $chunks = array_chunk($observations, 4);

        foreach ($chunks as $index => $items) {

            $slide = $index === 0
                ? $presentation->getActiveSlide()
                : $presentation->createSlide();

            // posiciones para 4 fotos
            $positions = [
                [50, 80],
                [500, 80],
                [50, 360],
                [500, 360]
            ];

            foreach ($items as $i => $item) {

                $imageUrl = $item['media']['url'];

                $tmp = tempnam(sys_get_temp_dir(), 'ppt');
                file_put_contents($tmp, file_get_contents($imageUrl));

                $shape = new File();

                $shape->setPath($tmp)
                    ->setResizeProportional(true) // mantiene proporción
                    ->setWidth(400) // tamaño máximo
                    ->setOffsetX($positions[$i][0])
                    ->setOffsetY($positions[$i][1]);

                $slide->addShape($shape);

                // texto debajo de la imagen
                $text = $slide->createRichTextShape()
                    ->setHeight(30)
                    ->setWidth(400)
                    ->setOffsetX($positions[$i][0])
                    ->setOffsetY($positions[$i][1] + 220);

                $location = $item['location']['name'] ?? '';
                $city = $item['location']['city']['name'] ?? '';
                $province = $item['location']['province']['name'] ?? '';

                $text->createTextRun("$location - $city ($province)");
            }
        }

        return $presentation;
  }
}
