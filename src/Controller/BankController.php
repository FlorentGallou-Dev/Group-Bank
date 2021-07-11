<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;

use App\Entity\Account;
use App\Form\AccountType;
use App\Repository\AccountRepository;

use App\Entity\Operation;
use App\Form\OperationType;
use App\Repository\OperationRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

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
    public function accountsList(): Response
    {

        $accounts = $this->getUser()->getAccounts(); // gets the actualy connected user accounts list

        return $this->render('bank/accountsList.html.twig', [
            "accounts" => $accounts,
        ]);
    }
    
    //PAGE D'AFFICHAGE D'UN SEUL COMPTE
    #[Route('/account/single/{id}', name: 'singleAccount')]
    public function singleAccount(int $id, AccountRepository $accountRepository): Response
    {   
        $user = $this->getUser()->getId(); //gets the actualy connected user

        //Making sure account data being load is current User's data ans gets the selected account
        $account = $accountRepository->findOneBy(array('id' => $id, 'user' => $user));

        return $this->render('bank/singleAccount.html.twig', [
            "account" => $account
        ]);
    }
    
    //PAGE D'AJOUT D'UN NOUVEAU COMPTE
    #[Route('/account/new_account', name: 'addAccountPage')]
    public function addAccountPage(Request $request): Response
    {
        $accounts = $this->getUser()->getAccounts(); // gets the actualy connected user accounts list
        
        $account = new Account();

        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $account->setOpeningDate(new \DateTime()); //Adds the now date time to the account
            $account->setUser($this->getUser()); //Adds the connected user to the account

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
    #[Route('/account/close_account/{id}', name: 'closeAccountPage', requirements: ['id' => '\d+'])]
    public function closeAccountPage(int $id, AccountRepository $accountRepository ,Request $request): Response
    {
        $user = $this->getUser()->getId(); //gets the actualy connected user
        $account = $accountRepository->findOneBy(array('id' => $id, 'user' => $user)); // gets the actualy connected user account selected
        
        if ($account) {
            $removeRequest = $this->getDoctrine()->getManager(); //Prepare the request

            $this->addFlash(
                'success',
                "Votre compte a bien été supprimé"
            );                                                     //Adds a success message

            $removeRequest->remove($account);                   //Sets the remove methode
            $removeRequest->flush();                            //Pass the request
        }
        elseif(!$account) {
            $this->addFlash(
                'danger',
                "N'essayez pas de supprimer les comptes des autres"
            );                                                     //Adds an error message if user tries to delete another user account
        }
        return $this->redirectToRoute('accountsList');
    }

    //PAGE D'OPERATION DEPOT/RETRAIT
    #[Route('/operation/deposit_withdrawal/{accountId}/{depotRetrait}', name: 'depositWithdrawalPage', methods: ['GET', 'POST'], requirements: ['accountId' => '\d+', 'depotRetrait' => '\d+'])]
    public function depositWithdrawalPage(int $accountId ,int $depotRetrait,AccountRepository $accountRepository, Request $request): Response
    {
        $operation = new Operation();
        $user = $this->getUser()->getId(); // gets the actualy connected user
        $account = $accountRepository->findOneBy(array('id' => $accountId, 'user' => $user)); // gets the actualy connected user account selected
        
        //To make sure that form for deposit/withdrawal only get shown if it's current user's account and prevent abuse
        if ($account) {

            $soldeActuel = $account->getAmount(); //gets the actual balance of the selected account 

            if ($depotRetrait === 1) { //verify the operation type 1 means deposit
                $ope = 'crédit';   
            }
            elseif($depotRetrait === 2){ //verify the operation type 1 means withdraw
                $ope = 'débit';
            }

            $form = $this->createForm(OperationType::class, $operation);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $operation->setOperationType($ope); //Add werever it's a deposit or a withdraw 
                $operation->setRegistered(new \DateTime()); //Add now time of opetation
                $operation->setAccount($account); //Add the actual account we are working on

                //Apply operation on account balance
                if ($depotRetrait === 1) {
                    $newSolde = $soldeActuel + $operation->getOperationAmount();
                }
                elseif($depotRetrait === 2){
                    $newSolde = $soldeActuel - $operation->getOperationAmount();
                }
                
                $operation->getAccount()->setAmount($newSolde); //Update account balance

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($operation);
                $entityManager->flush();

                return $this->redirectToRoute('singleAccount', ['id' => $accountId]); // get back to the actual account page
            }

            return $this->render('bank/depositWithdrawalPage.html.twig', [
                'form' => $form->createView(),
                'account' => $account,
                'ope' => $ope,
            ]);
        }

        return $this->redirectToRoute('accountsList');
        
    }

    //PAGE D'OPERATION DE TRANSFERT ------------------------------------------ A FINIR SI LE TEMPS
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