<?php

namespace App\Controller;

use App\Form\ChargeTypeForm;
use App\Model\ChargeData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class NewChargeController extends AbstractController{

    #[Route('/cobrancas/nova', name: 'app_charge_new')]
    public function new(Request $request): Response{
        $chargeData = new ChargeData();

        $form = $this->createForm(ChargeTypeForm::class, $chargeData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash(
                'success', 
                'Formulário de Cobrança enviado com sucesso! (Dados: ' . $chargeData->description . ' - ' . $chargeData->value . ')'
            );
            return $this->redirectToRoute('app_home');
        }

        return $this->render('new_charge/index.html.twig', [
            'chargeForm' => $form->createView(), 
        ]);
    }
}