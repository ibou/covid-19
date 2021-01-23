<?php

namespace App\Controller;

use App\Service\CallApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class DepartmentController extends AbstractController
{
    /**
     * @Route("/department/{department}", name="department")
     * @param string $department
     * @param CallApiService $service
     * @return Response
     */
    public function index(string $department, CallApiService $service, ChartBuilderInterface $chartBuilder): Response
    {
        $label = [];
        $hospitalisation = [];
        $rea = [];
        
        for ($i = 1; $i < 8; $i++) {
            $date = new \DateTime('- '.$i.' day');
            $datas = $service->getAllDataByDate($date->format('Y-m-d'));
            
            foreach ($datas['allFranceDataByDate'] as $data) {
                if ($data['nom'] === $department) {
                    $label [] = $data['date'];
                    $hospitalisation [] = $data['hospitalises'];
                    $rea [] = $data['reanimation'];
                    break;
                }
            }
        }
        
        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData(
            [
                'labels' => array_reverse($label),
                'datasets' => [
                    [
                        'label' => 'Nouvelle hospita',
                        'borderColor' => ('rgb(255,99,132)'),
                        'data' => array_reverse($hospitalisation),
                    ],
                    [
                        'label' => 'Nouvelle Réa',
                        'borderColor' => ('rgb(46,41,78)'),
                        'data' => array_reverse($rea),
                    ],
                ],
            ]
        );
        
        $chart->setOptions([]);
        
        return $this->render(
            'department/index.html.twig',
            [
                'data' => $service->getDepartementData($department),
                'chart'=>$chart
            ]
        );
    }
}
