<?php
namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieForm;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoriesBackController extends AbstractController {
    
    private $formationRepository;
    private $categorieRepository;  
    
    public function __construct( 
            CategorieRepository $categorieRepository,
            FormationRepository $formationRepository) {
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRepository;
    }
    
    /**
     *  @Route("/categoriesback", name="categoriesBack")
     * @return Response
     */
    public function index(): Response {
        $categories = $this->categorieRepository->findAll();
        $formations = $this->formationRepository->findAll();
        $formCategorie = $this->createForm(CategorieForm::class);

        return $this->render("pages/back/categoriesback.html.twig", [
            'categories' => $categories,
            'formations' => $formations,
            'form' => $formCategorie->createView()
        ]);
    }
    
    
    /**
     * @Route("/categoriesback/{id}", name="categoriesback.delete")
     * @param type $id
     * @return Response
     */
    public function deleteOne($id): Response {
    $categorie = $this->categorieRepository->find($id);

    if ($categorie && $categorie->getFormations()->isEmpty()) {
        
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($categorie);
            $entityManager->flush();
        } 
        return $this->redirectToRoute('categoriesBack');
    }

    
    /**
     * @Route ("/categoriesback/add/1", name="categoriesback.add")
     * @param Request $request
     * @return Response
     */
   public function AjoutCategorie(Request $request) : Response {
    $categorie = new Categorie();
    $formCategorie = $this->createForm(CategorieForm::class, $categorie);
    $formCategorie->handleRequest($request);

    if ($formCategorie->isSubmitted() && $formCategorie->isValid()) {
       
        $categorieExistante = $this->categorieRepository->findOneByName($categorie->getName());
        if (!$categorieExistante) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($categorie);
            $entityManager->flush();

            return $this->redirectToRoute('categoriesBack');
      
    }

    return $this->render("pages/back/categoriesback.html.twig", [
        'form' => $formCategorie->createView(),
        'categories' => $categorie
    ]);
   }
   
 }  

    
    
}
