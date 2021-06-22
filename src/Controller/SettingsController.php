<?php

namespace App\Controller;

use App\Entity\Settings;
use App\Entity\AmountSetting;
use App\Form\AmountSettingType;
use App\Repository\SettingsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SettingsController extends AbstractController
{
    #[Route('/settings', name: 'app_settings')]
    /**
     * index
     *
     * @param  mixed $request
     * @return Response
     */
    public function index(Request $request, SettingsRepository $settingsRepository,EntityManagerInterface $em): Response
    {
        $amountSettings = new AmountSetting;
        $amountForm = $this->createForm(AmountSettingType::class, $amountSettings);

        $amountForm->handleRequest($request);

        $setting = $settingsRepository->findOneBy(["code" => "ADMIN"]);

        if ($amountForm->isSubmitted() && $amountForm->isValid()) {


            if ($setting == null)
                $setting = new Settings;

            if ($amountSettings->getAmountRegister() != 0)
                $setting->setDefaultRegistrationAmount($amountSettings->getAmountRegister());

            else if ($amountSettings->getReductionRegister() != 0)
                $setting->setDefaultRegistrationAmount($setting->getDefaultRegistrationAmount() * (1 - ($amountSettings->getReductionRegister()) / 100));

            if ($amountSettings->getAmountSubs() != 0)
                $setting->setDefaultSubsAmount($amountSettings->getAmountSubs());

            else if ($amountSettings->getReductionSubs() != 0){

                $setting->setDefaultSubsAmount($setting->getDefaultSubsAmount() * (1 - ($amountSettings->getReductionSubs()) / 100));
            }
            

            $em->flush();

        }


        return $this->render('settings/index.html.twig', [
            'amountForm' => $amountForm->createView(),
            'setting' => $setting,
        ]);
    }

    #[Route('/settings/amounts', name: 'app_settings_amounts')]
    /**
     * change amounts settings
     *
     * @param  mixed $request
     * @return Response
     */
    public function changeAmounts(Request $request): Response
    {

        $amounts = $request->request->get('amount_setting');

        foreach ($amounts as $amountType => $amountValue) {
            switch ($amountType) {
                case 'amountRegister':
                    if ($amountValue != 0) {
                    }
                    break;
            }
        }
        dd($amounts);
        return $this->render('settings/index.html.twig', []);
    }
}
