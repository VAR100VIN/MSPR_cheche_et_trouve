<?php

namespace App\Controller;
use App\Repository\PlantRepository;
use App\Repository\UserRepository;
use App\Repository\FindRepository;
use App\Repository\ImageRepository;
use App\Repository\FindRepository;
use App\Entity\Plant;
use DateTime;
use App\Entity\Find;
use App\Entity\User;
use App\Form\FindType;
use App\Form\PlantType;
use App\Form\EditProfilType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    #[Route('/api',name: 'api_loc')]
    public function test(PlantRepository $plantRepository,UserRepository $userrepository,FindRepository $findrepository): Response
    {
        $find = new Find;
        
        $response=new Response();
        return $response;
    }
    
    #[Route('/play', name: 'app_play')]
    public function play(Request $request ,PlantRepository $plantRepository,UserRepository $userrepository, EntityManagerInterface $em,FindRepository $findrepository): Response
    {
        $find = new Find();
        // $user= $userrepository->find($_POST['user']);
        // $plant = $plantrepository->find($_POST['plant']);
        // $latitude = $_POST['latitude'];
        // $longitude = $_POST['longitude'];
        // $date=date('Y-m-d');
        // $date=new DateTime();
        // $find->setLatitude($latitude);
        // $find->setLongitude($longitude);
        // $find->setPlant($plant);
        // $find->setUser($user);
        // $find->setDate($date);
        // $findrepository->save($find,True);
        $form = $this->createForm(FindType::class, $find);
        $form->handleRequest($request);
        // $user= $userrepository->find($_POST['user']);
        // $plant = $plantrepository->find($_POST['plant']);
        
        if ($form->isSubmitted()&& $form->isValid()){
            $find = $form->getData();
            // $latitude = $_POST['latitude'];
            // $longitude = $_POST['longitude'];
            // $find->setLatitude($latitude);
            // $find->setLongitude($longitude);
            // $findrepository->save($find,True);
            //move le fichier
            // recuperer son nom
            // $filename=$user->getName().'_'.$plant->getName().'.png';
            // $find->setUrl($filename);
            // $lat = addslashes($_POST['lat']);
            // $lng = addslashes($_POST['lng']);
            // $find->setLatitude($latitude);
            // $find->setLongitude($longitude);
            $em->persist($find);
            $em->flush();
            echo 'Ajout réussi';
            return $this->render('home/playafter.html.twig', [
                'plants' => $plantRepository->findBy(array('level'=>'1'),array('id'=>'desc'),1), # TODO : Please remove the level = 1
            ]);
        }
        return $this->render('home/play.html.twig', [
            'plants' => $plantRepository->findBy(array('level'=>'1'),array('id'=>'desc'),1),
            'form' => $form->createView()
        ]);
    }

    #[Route('/profil', name: 'app_profil')]
    public function profil(): Response
    {
        return $this->render('home/profil.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/profil/edit', name: 'app_profil_edit')]
    public function editProfil(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $userPasswordHasher):Response
    {
        $user = $this->getUser();
        $form = $this->createForm(EditProfilType::class, $user);

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
    public function stats(FindRepository $findRepository, PlantRepository $plantRepository ): Response
    {
        $plants = $plantRepository->findAll();
        $finds = $findRepository->findAll();
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
