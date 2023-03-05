<?php

namespace App\Controller;

use App\Form\CostForms\ProfListCostType;
use App\Service\SeoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FenceCostController extends AbstractController
{
    #[Route('/calc-proflist', name: 'app_calc_proflist')]
    public function getProfListCost(Request $request)
    {
        $form = $this->createForm(ProfListCostType::class);
        $form->handleRequest($request);
        $costTable = null;
        $showResult = false;
        if ($form->isSubmitted() && $form->isValid()) {

            $costTable['width'] = $form->get('width')->getData();
            $costTable['height'] = $form->get('height')->getData();
            $showResult = true;
        }
        return $this->render('fence_cost/calcProfList.html.twig',
            [
                'form' => $form,
                'costResponse' => $costTable
            ]);
    }
}