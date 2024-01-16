<?php

namespace App\Repository;

use App\Entity\Playlist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Playlist>
 *
 * @method Playlist|null find($id, $lockMode = null, $lockVersion = null)
 * @method Playlist|null findOneBy(array $criteria, array $orderBy = null)
 * @method Playlist[]    findAll()
 * @method Playlist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlaylistRepository extends ServiceEntityRepository
{
    /**
     * accès aux formations de la playlist
     * @const string
     */
    const PFORMATION = 'p.formations';
    /**
     * accès a l'id de la playlist
     * @var string
     */
    const PID = 'p.id';
    /**
     * accès au nom de la playlist
     * @var string
     */
    const PNAME = 'p.name';
    
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Playlist::class);
    }

    public function add(Playlist $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Playlist $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    
    /**
     * Retourne toutes les playlists triées sur le nom de la playlist
     * @param type $champ
     * @param type $ordre
     * @return Playlist[]
     */
    public function findAllOrderByName($ordre): array{
        return $this->createQueryBuilder('p')
                ->leftjoin(self::PFORMATION, 'f')
                ->groupBy(self::PID)
                ->orderBy(self::PNAME, $ordre)
                ->getQuery()
                ->getResult();       
    } 
	
    /**
     * Enregistrements dont un champ contient une valeur
     * ou tous les enregistrements si la valeur est vide
     * @param type $champ
     * @param type $valeur
     * @param type $table si $champ dans une autre table
     * @return Playlist[]
     */
    public function findByContainValue($champ, $valeur, $table=""): array{
        if($valeur==""){
            return $this->findAllOrderByName('ASC');
        }    
        if($table==""){      
            return $this->createQueryBuilder('p')
                    ->leftjoin(self::PFORMATION, 'f')
                    ->where('p.'.$champ.' LIKE :valeur')
                    ->setParameter('valeur', '%'.$valeur.'%')
                    ->groupBy(self::PID)
                    ->orderBy(self::PNAME, 'ASC')
                    ->getQuery()
                    ->getResult();              
        }else{   
            return $this->createQueryBuilder('p')
                    ->leftjoin(self::PFORMATION, 'f')
                    ->leftjoin('f.categories', 'c')
                    ->where('c.'.$champ.' LIKE :valeur')
                    ->setParameter('valeur', '%'.$valeur.'%')
                    ->groupBy(self::PID)
                    ->orderBy(self::PNAME, 'ASC')
                    ->getQuery()
                    ->getResult();              
            
        }           
    }    
    /**
     * Retourne le nombre de formation de la p^^aylist
     * @return int
     */
    public function getCountFormations(): int
    {
        return $this->formations->count();
    }   
    /**
     * Retourne toutes les playlist ordonée par nombre de formations
     * @param type $ordre
     * @return array
     */
    public function findAllOrderedByFormationCount($ordre): array {
    return $this->createQueryBuilder('p')
        ->leftJoin(self::PFORMATION, 'f')
        ->groupBy(self::PID)
        ->orderBy('COUNT(f.id)', $ordre)
        ->getQuery()
        ->getResult();
}

    public function findAllForOnePlaylist($id){
        
    }
}
