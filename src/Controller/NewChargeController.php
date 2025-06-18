<?php

namespace App\Controller;

use App\Model\ChargeData;
use App\Form\ChargeTypeForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormInterface;

class NewChargeController extends AbstractController{

    #[Route('/cobrancas/nova', name: 'app_charge_new')]
    public function nova(Request $request): Response{
        $cobranca = new ChargeData();

        $form = $this->createForm(ChargeTypeForm::class, $cobranca);
        $form->handleRequest($request);

        $step = $form->get('currentStep')->getData() ?? '1';

        $form = $this->createForm(ChargeTypeForm::class, $cobranca, [
            'validation_groups' => function (FormInterface $form) use ($step) {
                return match ($step) {
                    '1' => ['Default', 'step1'],
                    '2' => ['Default', 'step2'],
                    default => ['Default'],
                };
            }
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $request->isMethod('POST')) {
            if ($form->isValid()) {
                if ($step == 1) {
                    $this->addFlash('success', 'Passo 1 validado com sucesso.');
                } elseif ($step == 2) {
                    $this->addFlash('success', 'Cobrança criada com sucesso!');
                    return $this->redirectToRoute('app_home');
                }
            } else {
                $this->addFlash('error', 'Existem erros no formulário.');
            }
        }

        return $this->render('new_charge/index.html.twig', [
            'chargeForm' => $form->createView(),
            'step' => $step,
        ]);
    }
}
