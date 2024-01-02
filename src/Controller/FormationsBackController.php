<?php
namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationForm;
use App\Form\FormationFormAdd;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controleur des formations
 *
 * @author emds
 */
class FormationsBackController extends AbstractController {

    /**
     * 
     * @var FormationRepository
     */
    private $formationRepository;
    
    /**
     * 
     * @var CategorieRepository
     */
    private $categorieRepository;
    /**
     * 
     * const string
     */
    const PAGEFORMATIONSBACK = "pages/back/formationsBack.html.twig";
    
    function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository) {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository= $categorieRepository;
    }
    
    /**
     * @Route("/formationsback", name="formationsBack")
     * @return Response
     */
    public function index(): Response{
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGEFORMATIONSBACK, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/formationsback/tri/{champ}/{ordre}/{table}", name="formationsBack.sort")
     * @param type $champ
     * @param type $ordre
     * @param type $table
     * @return Response
     */
    public function sort($champ, $ordre, $table=""): Response{
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGEFORMATIONSBACK, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }     
    
    /**
     * @Route("/formationsback/recherche/{champ}/{table}", name="formationsBack.findallcontain")
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    public function findAllContain($champ, Request $request, $table=""): Response{
        $valeur = $request->get("recherche");
        $formations = $this->formationRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGEFORMATIONSBACK, [
            'formations' => $formations,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }  
    
    /**
     * @Route("/formationsback/formation/{id}", name="formationsBack.showone")
     * @param type $id
     * @return Response
     */
    public function showOne($id): Response{
         $formation = $this->formationRepository->find($id);
        $formationCategories = $formation->getCategories();
        return $this->render('pages/back/formationback.html.twig', [
            'formations' => $formation,
            'categories' => $formationCategories,
            'formation' => $formation
        ]);        
    }   
    
    /**
     * @Route("/formationsback/{id}", name="formationBack_delete")
     * @param type $id
     * @return Response
     */
    public function deleteOne($id): Response {
    $formation = $this->formationRepository->find($id);
    if ($formation) {
        // Supprime la formation de la playlist si nécessaire
        $playlist = $formation->getPlaylist();
        if ($playlist) {
            $playlist->removeFormation($formation);
        }

        // Supprime la formation de la base de données
        $this->formationRepository->remove($formation, true);
    }

    // Rediriger vers une autre page après la suppression
    return $this->redirectToRoute('formationsBack');
}
/**
 * @Route ("/formationsback/edit/{id}", name="formationBack_edit")
 * @param Request $request
 * @param type $id
 * @return Response
 */
public function editFormation(Request $request, $id): Response {
    $formation = $this->formationRepository->find($id);

    if (!$formation) {
        throw $this->createNotFoundException('No formation found for id '.$id);
    }

    $form = $this->createForm(FormationForm::class, $formation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Enregistrer les modifications
        $this->getDoctrine()->getManager()->flush();
        error_log("formulaire valide et envoyé");
        // Redirection vers la liste des formations 
        return $this->redirectToRoute('formationsBack');
    } else {
        error_log("formulaire non valide ou non envoyé");
    }

    return $this->render('pages/back/formationbackedit.html.twig', [
        'form' => $form->createView(),
        'formation' => $formation // Ajoute la formation au formulaire
    ]);
}
/**
 * @Route ("/formationsback/add/1", name="formationBack_add")
 * @param Request $request
 * @return Response
 */
public function AjoutFormation(Request $request) : Response{
    $formation = new Formation();
    $form = $this->createForm(FormationFormAdd::class, $formation);
    $form->handleRequest($request);
    
     if ($form->isSubmitted() && $form->isValid()) {
        // Enregistrer les modifications
        $this->getDoctrine()->getManager()->persist($formation);
        $this->getDoctrine()->getManager()->flush();
       
        // Redirection vers la liste des formations 
        return $this->redirectToRoute('formationsBack');
    } 

    return $this->render('pages/back/formationbackadd.html.twig', [
        'form' => $form->createView(),
        'formation' => $formation // Ajoute la formation au formulaire
    ]);
}


    
}
