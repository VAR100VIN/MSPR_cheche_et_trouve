<?php

namespace App\Repository;
use App\Entity\User;
use App\Entity\Find;
use App\Entity\Plant;
use App\Repository\UserRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Plant>
 *
 * @method Plant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Plant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Plant[]    findAll()
 * @method Plant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Plant::class);
    }

    public function save(Plant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Plant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function Game(User $user=null):array{
        $plantsToReturn=[];
        if ($user!=null){
            $userid=$user->getId();
            $rawSql="select * from plant where level<=".strval($user->getExp()+1)." and id not in(SELECT plant_id FROM find where user_id=:user_id)and is_show=1 order by RAND() limit ".strval(1).";";
            $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
            $plants_array=$stmt->executeQuery([":user_id"=>$userid])->fetchAllAssociative();
            $plantsToReturn = [];
            foreach ($plants_array as $array) {
                $plant=$this->find($array["id"]);
                //$plant = Plant::fromArray($array);
                $plantsToReturn[] = $plant;
            }
        }
        return $plantsToReturn;
    }
    public function Succes(User $user=null):array{
        $plantsToReturn=[];
        if ($user!=null){
            $userid=$user->getId();
            $rawSql="select * from plant where id in(SELECT plant_id FROM find where user_id=:user_id)order by id desc limit ".strval(1).";";
            $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
                $plants_array=$stmt->executeQuery([":user_id"=>$userid])->fetchAllAssociative();
                $plantsToReturn = [];
                foreach ($plants_array as $array) {
                    $plant=$this->find($array["id"]);
                    //$plant = Plant::fromArray($array);
                    $plantsToReturn[] = $plant;
                }
        }
        return $plantsToReturn;
        header("Refresh CONTENT=\"10; URL=home/play/succes\"" );
    }
}
