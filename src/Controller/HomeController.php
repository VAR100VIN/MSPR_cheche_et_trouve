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
    // #[Route('/api',name: 'api_loc')]
    // public function test(PlantRepository $plantRepository,UserRepository $userrepository,FindRepository $findrepository): Response
    // {
    //     $find = new Find;
    //     $plant = $plantRepository->findOneBy(array('level'=>'1'),array('id'=>'desc'),1);
    //     $user= $this->getUser();
    //     // $plant = $plantrepository->findOneBy(["id"=>$_POST['plant']]);
    //     // $latitude = $_POST['latitude'];
    //     // $longitude = $_POST['longitude'];
    //     $data = $_POST['image'];
    //     $date=date('Y-m-d');
    //     $image = explode('base64,',$data);
    //     $filename=$user->getName().'_'.$image.$date.'.png';
    //     // $fic=fopen('images/photo/'.$filename,"wb");//
    //     // fwrite($fic,base64_decode($image[1]));
    //     // fclose($fic);
    //     $date=new DateTime();
    //     $find->setUser($user);
    //     $find->setPlant($plant);
    //     $find->setLatitude($latitude);
    //     $find->setLongitude($longitude);
    //     $find->setDate($date);
    //     $find->setUrl($filename);
    //     $findrepository->save($find,True);
    //     $response=new Response();
    //     return $response;
    // }
    
    #[Route('/play', name: 'app_play')]
    public function play(LoggerInterface $logger, Request $request ,PlantRepository $plantRepository,UserRepository $userrepository, EntityManagerInterface $em,FindRepository $findrepository): Response
    {
        $find = new Find();
        $nonce_valide = false;
        
        
        // 
        $plant = $plantRepository->findOneBy(array('level'=>'1'),array('id'=>'desc'),1);
        $user= $this->getUser();
        $date=date('Y-m-d');
        $date=new DateTime();
        $result = $date->format('Y-m-d H:i:s');
        $filename=$result.'_'.'.png';
        
        $form = $this->createForm(FindType::class, $find);
        $form->handleRequest($request);
        $latitude = $request->get('latitude');
            $longitude = $request->get('longitude');
            $data = $request->get('image');
        if ($request->isXmlHttpRequest()){
            

            
            $logger->info($data);
            $image = explode('base64,',$data);
                $fic=fopen('/public/assets/medias/uploads/'.$filename,"wb");//
                fwrite($fic,base64_decode($image[1]));
            $find = $form->getData();
            $find->setUser($user);
            $find->setPlant($plant);
            $find->setDate($date);
            $find->setLatitude($latitude);
            $find->setLongitude($longitude);
            $find->setUrl($filename);
            $findrepository->save($find,True);
            $em->persist($find);
            $em->flush();
            echo 'Ajout réussi';
            return $this->render('home/playafter.html.twig', [
                'plants' => $plantRepository->findBy(array('level'=>'1'),array('id'=>'desc'),1), # TODO : Please remove the level = 1
            ]);
        }
            
            // if (isset($_POST['nonce'])) {   // le nonce est stocké dans un champ caché du formulaire
                
            //     $nonce_valide = verifier_nonce($_POST['nonce'], 'enregistrer');
    
            // }
            // // $user= $userrepository->find($_POST['user']);
            // // $plant = $plantrepository->find($_POST['plant']);
            // if ($nonce_valide) {
                
                
            // }
            // // $find->setLatitude($latitude);
            // // $find->setLongitude($longitude);
            
           
        return $this->render('home/play.html.twig', [
            'plants' => $plantRepository->Game(1,true,$this->getUser()),
            'form' => $form->createView()
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
