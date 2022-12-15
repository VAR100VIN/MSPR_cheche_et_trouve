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
    public function load(ObjectManager $manager){   
        // $product = new Product();
        // $manager->persist($product);
        

            $data = './src/DataFixtures/Ressources/Models/plants.json';
            $json = file_get_contents($data);
           
            $json2 = json_decode($json,true);

         
   
            foreach ($json2 as $plant){
                $tableauplant[]=$plant["name"];
                $tableauImages[$plant["name"]]=$plant["photos"];
                $tableauDescriptionsBefore[$plant["name"]]=$plant["before"];
                $tableauDescriptionsAfter[$plant["name"]]=$plant["after"];
                $tableauXP[$plant["name"]]=$plant["level"];
            }
            
            
            foreach($tableauplant as $plantName) {
                foreach($tableauImages[$plantName] as $image){
                    $plantImage=new Image();
                    $plantImage->setUrl(["/assets/medias/".$image]);
                    $manager->persist($plantImage);
                    $manager->flush();
    
                };     
                $plant = new Plant();
                $plant->setName($plantName);
                $plant->setdescription("");
                $plant->setdescriptionAfter("");
                $plant->setLevel($tableauXP[$plantName]);
                $plant->setIsShow(1);
                $plantImage->setIdPlant($plant);
                $manager->persist($plant);
                $manager->flush();
            
            foreach($tableauDescriptionsBefore[$plantName] as $infoElement){
                $element= new Description();
                $element->setAfter(0);
                $element->setPlant($plant);
                $element->setText($infoElement["text"]);
                if (isset($infoElement["title"])){
                    $element->setTitle($infoElement["title"]);
                }
                if (isset($infoElement["logo"])){
                    $element->setLogo($infoElement["logo"]);
                }
                $manager->persist($element);
                $manager->flush();
                
            }
            foreach($tableauDescriptionsAfter[$plantName] as $infoElement){
                $element= new Description();
                $element->setAfter(1);
                $element->setPlant($plant);
                $element->setText($infoElement["text"]);
                if (isset($infoElement["title"])){
                    $element->setTitle($infoElement["title"]);
                }
                if (isset($infoElement["logo"])){
                    $element->setLogo($infoElement["logo"]);
                }
                $manager->persist($element);
                $manager->flush();
                
            }
      }
    }
}
