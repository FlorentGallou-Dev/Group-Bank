<?php

namespace App\Controller;

use App\Entity\User;

use App\Entity\Account;
use App\Form\AccountType;
use App\Form\OperationType;
use App\Repository\AccountRepository;
use App\Repository\OperationRepository;

use App\Entity\Operation;

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
    #[Route('/account/single/{id}', name: 'singleAccount')]
    public function singleAccount(int $id): Response
    {   
        // $operationRepository = $this->getDoctrine()->getRepository(Operation::class);
        // $operations = $operationRepository->find($id);
        $accountRepository = $this->getDoctrine()->getRepository(Account::class);
        $account = $accountRepository->find($id);
        dump($account);
        return $this->render('bank/singleAccount.html.twig', [
            "account" => $account
        ]);
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
    #[Route('/operation/deposit_withdrawal/{accountId}/{depotRetrait}', name: 'depositWithdrawalPage', methods: ['GET', 'POST'], requirements: ['accountId' => '\d+', 'depotRetrait' => '\d+'])]
    public function depositWithdrawalPage(int $accountId ,int $depotRetrait,AccountRepository $accountRepository, OperationRepository $operationRepository, Request $request): Response
    {
        $operation = new Operation();
        $account = $accountRepository->find($accountId);
        $soldeActuel = $account->getAmount();
        
        if ($depotRetrait === 1) {
            $ope = 'dépot';   
        }
        elseif($depotRetrait === 2){
            $ope = 'retrait';
        }

        $form = $this->createForm(OperationType::class, $operation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           $operation->setOperationType($ope);
           $operation->setRegistered(new \DateTime());
           $operation->setAccount($account);

           if ($depotRetrait === 1) {
                $newSolde = $soldeActuel + $operation->getOperationAmount();
            }
            elseif($depotRetrait === 2){
                $newSolde = $soldeActuel - $operation->getOperationAmount();
            }

            $operation->getAccount()->setAmount($newSolde);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($operation);
            $entityManager->flush();

            return $this->redirectToRoute('singleAccount', ['id' => $accountId]); 
        }

        return $this->render('bank/depositWithdrawalPage.html.twig', [
            'form' => $form->createView(),
            'account' => $account,
            'ope' => $ope,
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