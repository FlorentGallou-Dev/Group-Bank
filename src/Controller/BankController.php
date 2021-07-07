<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\User;
use App\Entity\Operation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class BankController extends AbstractController
{
    //PAGE D'ACCUEIL AVEC LA LISTE DES COMPTES
    #[Route('/', name: 'accountsList')]
    //#[Route('/account/my_accounts', name: 'accountsList')]
    public function accountsList(): Response
    {
        $accounts = $this->getUser()->getAccounts();
        // $operationRepository = $this->getDoctrine()->getRepository(Operation::class);
        // $operation = $operationRepository->findOneBy(array('id' => ));
        return $this->render('bank/accountsList.html.twig', [
            "accounts" => $accounts,
            // "operation" => $operation
        ]);
    }

    //PAGE D'AFFICHAGE D'UN SEUL COMPTE
    #[Route('/account/single', name: 'singleAccount')]
    public function singleAccount(): Response
    {
        return $this->render('bank/singleAccount.html.twig', [
        ]);
    }
    
    //PAGE D'AJOUT D'UN NOUVEAU COMPTE
    #[Route('/account/new_account', name: 'addAccountPage')]
    public function addAccountPage(): Response
    {
        return $this->render('bank/addAccountPage.html.twig', [
        ]);
    }

    //PAGE DE CLOTURE DE COMPTE
    #[Route('/account/close_account', name: 'closeAccountPage')]
    public function closeAccountPage(): Response
    {
        return $this->render('bank/closeAccountPage.html.twig', [
        ]);
    }

    //PAGE D'OPERATION DEPOT/RETRAIT
    #[Route('/operation/deposit_withdrawal', name: 'depositWithdrawalPage')]
    public function depositWithdrawalPage(): Response
    {
        return $this->render('bank/depositWithdrawalPage.html.twig', [
        ]);
    }

    //PAGE D'OPERATION DE TRANSFERT
    #[Route('/operation/transfer', name: 'transferPage')]
    public function transferPage(): Response
    {
        return $this->render('bank/transferPage.html.twig', [
        ]);
    }

    //PAGE DES MENTIONS LEGALES
    #[Route('/legals', name: 'legalsPage')]
    public function legalsPage(): Response
    {
        return $this->render('bank/legalsPage.html.twig', [
        ]);
    }

    //PAGE DES STATISTIQUES ------------------------------------------ A FINIR SI LE TEMPS
    #[Route('/statistics', name: 'statisticsPage')]
    public function statisticsPage(): Response
    {
        return $this->render('bank/statisticsPage.html.twig', [
        ]);
    }

    //PAGE ACTUALITES ------------------------------------------ A FINIR SI LE TEMPS
    #[Route('/blog', name: 'blogPage')]
    public function blogPage(): Response
    {
        return $this->render('bank/blogPage.html.twig', [
        ]);
    }
    
}