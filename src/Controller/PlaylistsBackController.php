<?php
namespace App\Controller;

use App\Entity\Formation;
use App\Entity\Playlist;
use App\Form\PlaylistForm;
use App\Form\PlaylistFormAdd;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of PlaylistsController
 *
 * @author emds
 */
class PlaylistsBackController extends AbstractController {
    
    /**
     * 
     * @var PlaylistRepository
     */
    private $playlistRepository;
    
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
     * @const string
     */
    const PAGE_PLAYLISTSBACK = "pages/back/playlistsback.html.twig";
    
    function __construct(PlaylistRepository $playlistRepository, 
            CategorieRepository $categorieRepository,
            FormationRepository $formationRespository) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRespository;
    }
    
    /**
     * @Route("/playlistsback", name="playlistsBack")
     * @return Response
     */
    public function index(): Response{
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        foreach($playlists as $playlist){
            $playlist->countFormation = $playlist->getCountFormations();
        }
        return $this->render(self::PAGE_PLAYLISTSBACK, [
            'playlists' => $playlists,
            'categories' => $categories
            
        ]);
    }

    /**
     * @Route("/playlistsback/tri/{champ}/{ordre}", name="playlistsback.sort")
     * @param type $champ
     * @param type $ordre
     * @return Response
     */
    public function sort($champ, $ordre): Response{
        switch($champ){
            case "name":
                $playlists = $this->playlistRepository->findAllOrderByName($ordre);
                break;
            case "countFormations":
                $playlists = $this->playlistRepository->findAllOrderedByFormationCount($ordre);
                break;
            default: 
                $playlists = $this->playlistRepository->findAll();
                break;
        }
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_PLAYLISTSBACK, [
            'playlists' => $playlists,
            'categories' => $categories            
        ]);
    }          
	
    /**
     * @Route("/playlistsback/recherche/{champ}/{table}", name="playlistsback.findallcontain")
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    public function findAllContain($champ, Request $request, $table=""): Response{
        $valeur = $request->get("recherche");
        $playlists = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_PLAYLISTSBACK, [
            'playlists' => $playlists,
            'categories' => $categories,            
            'valeur' => $valeur,
            'table' => $table
        ]);
    }  
    
    /**
     * @Route("/playlistsback/playlist/{id}", name="playlistsback.showone")
     * @param type $id
     * @return Response
     */
    public function showOne($id): Response{
        $playlist = $this->playlistRepository->find($id);
        $playlists[0] = $playlist;
        $playlistCategories = $this->categorieRepository->findAllForOnePlaylist($id);
        $playlistFormations = $this->formationRepository->findAllForOnePlaylist($id);
        $countFormations = count($playlistFormations);
        return $this->render(self::PAGE_PLAYLISTSBACK, [
            'playlists'=> $playlists,
            'playlist' => $playlist,
            'playlistcategories' => $playlistCategories,
            'playlistformations' => $playlistFormations,
            'categories' => $playlistCategories,
            'countFormations' => $countFormations
        ]);        
    }       
    
    /**
     * @Route("/playlistsback/{id}", name="playlistsback.delete")
     * @param type $id
     * @return Response
     */
    public function deleteOne($id): Response{
        $playlist = $this->playlistRepository->find($id);
        if ($playlist) {
            $playlistFormations = $this->formationRepository->findAllForOnePlaylist($id);
            $countFormations = count($playlistFormations);
            if($countFormations == 0){
                $this->playlistRepository->remove($playlist,true);
                $this->getDoctrine()->getManager()->flush();
                return $this->redirectToRoute('playlistsBack');
            }
            
            
        }
        return $this->redirectToRoute('playlistsBack');
    }
    
    /**
 * @Route ("/playlistsback/edit/{id}", name="playlistsback.edit")
 * @param Request $request
 * @param type $id
 * @return Response
 */
public function editPlaylist(Request $request, $id): Response {
    $playlist = $this->playlistRepository->find($id);
    
    if (!$playlist) {
        throw $this->createNotFoundException('No playlist found for id '.$id);
    }

    $form = $this->createForm(PlaylistForm::class, $playlist);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Enregistrer les modifications
        $this->getDoctrine()->getManager()->flush();
        error_log("formaulaire valide et envoyé");
        // Redirection vers la liste des formations 
        return $this->redirectToRoute('playlistsBack');
    } else {
        error_log("formulaire non valide ou non envoyé");
    }

    return $this->render('pages/back/playlistbackedit.html.twig', [
        'form' => $form->createView(),
        'playlist' => $playlist // Ajoute la playlist au formulaire
    ]);
}
/**
 * @Route ("/playlistback/add/1", name="playlistback.add")
 * @param Request $request
 * @return Response
 */
public function AjoutPlaylist(Request $request) : Response{
    $playlist = new Playlist();
    $form = $this->createForm(PlaylistFormAdd::class, $playlist);
    $form->handleRequest($request);
    
     if ($form->isSubmitted() && $form->isValid()) {
        // Enregistrer les modifications
        $this->getDoctrine()->getManager()->persist($playlist);
        $this->getDoctrine()->getManager()->flush();
       
        // Redirection vers la liste des formations 
        return $this->redirectToRoute('playlistsBack');
    } 

    return $this->render('pages/back/playlistbackadd.html.twig', [
        'form' => $form->createView(),
        'playlist' => $playlist // Ajoute la playlist au formulaire
    ]);
}
}
