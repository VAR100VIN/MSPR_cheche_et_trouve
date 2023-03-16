<?php

namespace App\DataFixtures;

use App\Entity\Description;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Plant;
use App\Entity\User;
use App\Entity\PlantImages;
use App\Entity\Image;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $data = './src/DataFixtures/Ressources/Models/plants.json';
        $json = file_get_contents($data);

        $json2 = json_decode($json, true);

        foreach ($json2 as $plant) {
            $plantObj = new Plant();
            $plantObj->setName($plant['name']);
            $plantObj->setLevel($plant['level']);

            $descBefore = "";
            foreach ($plant["before"] as $descBeforeElt){
                $descBefore .= $descBeforeElt['text']."\n";
            }
            $plantObj->setDescription($descBefore);

            $descAfter ="";
            foreach ($plant["after"] as $descAfterElt){
                if(isset($descAfterElt['title'])){
                    $descAfter .= $descAfterElt['title']."\n";
                } 
                if(isset($descAfterElt['logo'])){
                    $descAfter .= "%LOGO%".$descAfterElt['logo']."%LOGO%";
                } 

                $descAfter .= $descAfterElt['text']."\n";        
            }
            $plantObj->setDescriptionAfter($descAfter);
            $plantObj->setIsShow(1);
            $manager->persist($plantObj);
            $manager->flush();

            
            foreach ($plant["photos"] as $imageElt){
                $image = new Image();
                $image->setUrl($imageElt);
                $image->setPlant($plantObj);
                $manager->persist($image);
                $manager->flush();
            }
        }
        
    }
}
