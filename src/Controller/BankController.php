<?php

namespace App\Controller;

use App\Entity\User;

use App\Entity\Account;
use App\Form\AccountType;
use App\Repository\AccountRepository;

use App\Entity\Operation;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
        return $this->render('bank/accountsList.html.twig', [
            "accounts" => $accounts,
        ]);
    }
    
    //PAGE D'AFFICHAGE D'UN SEUL COMPTE
    #[Route('/account/single/{id}', name: 'singleAccount')]
    public function singleAccount(int $id): Response
    {   
        $accountRepository = $this->getDoctrine()->getRepository(Account::class);
        $account = $accountRepository->find($id);
        // To make sure User can see only his account.
        if ($account->getUser() === $this->getUser()) {
            return $this->render('bank/singleAccount.html.twig', [
                "account" => $account
            ]);
        }
        else {
            return $this->redirectToRoute('accountsList');
        }
    }
    
    //PAGE D'AJOUT D'UN NOUVEAU COMPTE
    #[Route('/account/new_account', name: 'addAccountPage')]
    public function addAccountPage(Request $request): Response
    {
        $account = new Account();
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $account->setOpeningDate(new \DateTime());
            $account->setUser($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($account);
            $entityManager->flush();
            return $this->redirectToRoute('accountsList');
        }
        return $this->render('bank/addAccountPage.html.twig', [
            'form' => $form->createView(),
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