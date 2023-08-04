<?php

namespace App\Controller;

use App\Entity\Employes;
use App\Form\EmployesType;
use App\Repository\EmployesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Loader\Configurator\form;

class AppController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('app/index.html.twig', [
            'controller_name' => 'Accueil',
        ]);
    }

    #[Route('/', name: 'accueil')]
    public function accueil(): Response
    {
        return $this->render('app/accueil.html.twig', [
            'title' => 'Page daccueil', 
            
        ]);
        
    }

    


    // #[Route('/blog', name: 'app_blog')]
    // // Une route est définie par deux arguments : son chemin (/blog) dans l'url et son nom (app_blog)
    // public function index(ArticleRepository $repo): Response
    // {
    //     // $repo est instance de la classe ArticleRepository et possède du cout les 4 methodes de base find(), findOneBy(), findAll(), findBy()
    //     $articles = $repo->findAll();
    //     return $this->render('blog/index.html.twig', [
    //         "articles" => $articles
    //         // 'controller_name' => 'BlogController',
    //     ]);
    //     // render() permet d'afficher le contenu d'un template. Elle va chercher directement dans le dossier template
    // }





    #[Route('/blog/modifier/{id}', name:"app_modifier")]
    
    #[Route('/employes/ajout', name: 'ajouter')]
    public function form(Request $request, EntityManagerInterface $manager, Employes $employes = null):response
    {
       
        // if($article == null)
        // {
        //     $article = new Article;  
        // }

        // $form = $this->createForm(ArticleType::class, $article);
        if($employes == null)
        {
            $employes = new Employes;
        }

        $form =$this->createForm(EmployesType::class, $employes);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            // $employes->setCreatedAt(new \Datetime);
            // dd($article);
            // dd($globals->request);

            // persist() va permettre de préparer ma requete SQL a envoyer par rapport a l'objet donné à un argument
            $manager->persist($employes);

            // flush() permet d'executer tout les persist précédent
            $manager->flush();
            // redictToRoute() permet de rediriger vers une autre page de notre site a l'aide du nom de la route

            return $this->redirectToRoute('fichier');
        }


        return $this->render('app/formulaire.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/employes/fichier', name: 'fichier')]
    public function fichier(EmployesRepository $repo)
    {
        $fichier = $repo->findAll();

        return $this->render('app/fichier.html.twig', [
            'fichier'=> $fichier
        ]);
    }


    #[Route('/app/voir/{id}', name:"app_voir")]
    public function voir($id, EmployesRepository $repo)
    {
        $employes = $repo->find($id);
        return $this->render('app/voir.html.twig', [
            'voir' => $employes,
        ]);
    }

    #[Route('/app/supprimer/{id}', name: 'app_supprimer')]
     public function supprimer(Employes $employes, EntityManagerInterface $manager)
     {
        $manager->remove($employes);
        $manager->flush();
        return $this->redirectToRoute('fichier');


     }


}
