<?php

namespace App\Controller;

use App\Entity\Settings;
use App\Entity\AmountSetting;
use App\Form\AmountSettingType;
use App\Repository\SaleRepository;
use Flasher\Toastr\Prime\ToastrFactory;
use App\Repository\ServiceRepository;
use App\Repository\SettingsRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\ResponsableActivityTracker;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SettingsController extends AbstractController
{

    private  const  SETTING_AMOUNT_ACTIVITY = "Modification des montants";
    private  const  SETTING_SALES_ACTIVITY = "Suppression de l'historique des ventes";
    private  const  SETTING_USER_TRACKING_ACTIVITY = "Suppression du tracking des responsables";
    private  const  SETTING_CLIENT_TRACKING_ACTIVITY = "Suppression du tracking des clients";


    private $em;
    private $flasher;
    private $responsableTracker;

    public function __construct(EntityManagerInterface $em, ToastrFactory $flasher, ResponsableActivityTracker $responsableTracker)
    {
        $this->em = $em;
        $this->flasher = $flasher;
        $this->responsableTracker = $responsableTracker;
    }

    #[Route('/settings', name: 'app_settings')]
    /**
     * index
     *
     * @param  mixed $request
     * @return Response
     */
    public function index(Request $request, SettingsRepository $settingsRepository): Response
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

            else if ($amountSettings->getReductionSubs() != 0) {

                $setting->setDefaultSubsAmount($setting->getDefaultSubsAmount() * (1 - ($amountSettings->getReductionSubs()) / 100));
            }


            $this->em->flush();
            $this->flasher->addSuccess('Les montants ont été edités !');

            $this->responsableTracker->saveTracking($this::SETTING_AMOUNT_ACTIVITY, $this->getUser());
        }


        return $this->render('settings/index.html.twig', [
            'amountForm' => $amountForm->createView(),
            'setting' => $setting,
        ]);
    }

    #[Route('/settings/sells/delete', name: 'app_settings_sells_delete', methods: ['DELETE'])]
    /**
     * sellsHistoryDelete
     *
     * @param  mixed $saleRepository
     * @return Response
     */
    public function sellsHistoryDelete(SaleRepository $saleRepository): Response
    {

        $sales = $saleRepository->findAll();

        foreach ($sales as $sale) {
            $this->em->remove($sale);
            $this->em->flush();
        }

        $this->flasher->addInfo('L historique des ventes a été supprimé !!');

        $this->responsableTracker->saveTracking($this::SETTING_SALES_ACTIVITY, $this->getUser());

        return $this->redirectToRoute('app_settings');
    }

    #[Route('/settings/clientsTracking/delete', name: 'app_settings_clientsTracking_delete', methods: ['DELETE'])]

    // public function clientsTrackingHistoryDelete(ServiceRepository $serviceRepository): Response
    // {
    //     $services = $serviceRepository->findAll();

    //     foreach ($services as $service) {
    //         $this->em->remove($service);
    //         $this->em->flush();
    //     }

    //     $this->flasher->addInfo('L historique des activités des responsables a été supprimé !!');


    //     return $this->redirectToRoute('app_settings');
    // }


    #[Route('/settings/userTracking/delete', name: 'app_settings_userTracking_delete', methods: ['DELETE'])]

    /**
     * userTrackingHistoryDelete
     *
     * @param  mixed $request
     * @return Response
     */
    public function userTrackingHistoryDelete(ServiceRepository $serviceRepository): Response
    {
        $services = $serviceRepository->findAll();

        foreach ($services as $service) {
            $this->em->remove($service);
            $this->em->flush();
        }

        $this->flasher->addInfo('L historique des activités des responsables a été supprimé !!');

        $this->responsableTracker->saveTracking($this::SETTING_USER_TRACKING_ACTIVITY, $this->getUser());

        return $this->redirectToRoute('app_settings');
    }
}
