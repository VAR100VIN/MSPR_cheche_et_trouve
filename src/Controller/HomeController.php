<?php

namespace App\Controller;
use Psr\Log\LoggerInterface;
use App\Repository\PlantRepository;
use App\Repository\UserRepository;
use App\Repository\FindRepository;
use App\Entity\Plant;
use DateTime;
use App\Entity\Find;
use App\Entity\User;
use App\Form\FindType;
use App\Form\PlantType;
use App\Form\EditProfilType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Admin\FindCrudController;
use Symfony\Bundle\FrameworkBundle\Controller\PlantController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/home')]
class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    
    #[Route('/play', name: 'app_play')]
    public function play(LoggerInterface $logger, Request $request ,PlantRepository $plantRepository,UserRepository $userrepository, EntityManagerInterface $em,FindRepository $findrepository): Response
    {
        $find = new Find();
        $nonce_valide = false;
        $user= $this->getUser();
        $date=date('Y-m-d');
        $date=new DateTime();
        $result = $date->format('Y-m-d H:i:s');
        $filename=$result.'_'.'.png';
        $form = $this->createForm(FindType::class, $find);
        $form->handleRequest($request);
        $plant = $plantRepository->findOneBy(["name"=>$request->get('plant')]);
        $latitude = $request->get('latitude');
            $longitude = $request->get('longitude');
            $data = $request->get('image');
        if ($request->isXmlHttpRequest()){
            

            
            $logger->info($data);
            $image = explode('base64,',$data);
            // $fic=fopen('/public/assets/medias/uploads/'.$filename,"wb");//
            // fwrite($image,base64_decode($image[1]));
            $find = $form->getData();
            $find->setUser($user);
            $find->setPlant($plant);
            $find->setDate($date);
            $find->setLatitude($latitude);
            $find->setLongitude($longitude);
            $find->setUrl($filename);
            $findrepository->save($find,True);
            $plantRepository->save($plant,True);
            $em->persist($find);
            $em->flush();
            echo 'Ajout réussi';
            $user->setExp($user->getExp()+1);
            $userrepository->save($user,True);
        }
         
        return $this->render('home/play.html.twig', [
            'plants' => $plantRepository->Game($this->getUser()),
            /*'nbr_utilisateur' => $findrepository->get_userfound($plant),*/
            'form' => $form->createView()
        ]);
    }
    #[Route('/play/succes', name: 'app_succes')]
    public function succes(PlantRepository $plantRepository,UserRepository $userrepository): Response
    {
        return $this->render('home/playafter.html.twig', [
            'plants' => $plantRepository->Succes($this->getUSer()),
        ]);
    }
    #[Route('/profil', name: 'app_profil')]
    public function profil(FindRepository $findRepository): Response
    {
        $finds = $findRepository->findAll();
        return $this->render('home/profil.html.twig', [
            'controller_name' => 'HomeController',
            'finds' => $finds,
        ]);
    }

    #[Route('/profil/edit', name: 'app_profil_edit')]
    public function editProfil(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $userPasswordHasher):Response
    {
        $user = $this->getUser();
        $form = $this->createForm(EditProfilType::class, $user, ['attr' => ['class' => 'disp-f al-c f-d-c']]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('ModifPassword')->getData()
                )
            );
            $em = $doctrine->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash("message", "profil mis à jour");
            return $this->redirectToRoute('app_profil');
        }

        return $this->render('home/editprofil.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/profil/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        $session = new Session();
        $session->invalidate();

        return $this->redirectToRoute('app_register', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/stats', name: 'app_stats')]
    public function stats(FindRepository $findRepository, PlantRepository $plantRepository, EntityManagerInterface $entityManager ): Response
    {
        $plants = $plantRepository->findAll();
        $finds = $findRepository->findAll();

        foreach ($plants as $plant){
            $rawSql="select url from image where plant_id=:plant_id;";
            $stmt = $entityManager->getConnection()->prepare($rawSql);
            $images_array=$stmt->executeQuery([":plant_id"=>$plant->getId()])->fetchAllAssociative();
    
            $imageProcess = [];
            foreach ($images_array as $image) {
                $imageProcess[] = $image['url'];
            }
            $plant->setImages($imageProcess);
        }
        

        return $this->render('home/stats.html.twig', [
            'controller_name' => 'HomeController',
            'finds' => $finds,
            'plants' => $plants,
        ]);
    }

    #[Route('/stats/{id}', name: 'app_plant_show', methods: ['GET'])]
    public function show(Plant $plant): Response
    {
        return $this->render('home/showplant.html.twig', [
            'plant' => $plant,
        ]);
    }
}
