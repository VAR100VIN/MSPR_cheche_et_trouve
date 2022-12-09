<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Plant;
use App\Entity\User;
use App\Entity\PlantImages;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager){
        // $product = new Product();
        // $manager->persist($product);


            $data = './src/DataFixtures/Ressources/Models/plants.json';
            $json = file_get_contents($data);
            print_r($json);
            $json2 = json_decode($json,true);

            print_r($json2);



            foreach($tableauplant as $plantName) {
                $plant = new Plant();
                $plant->setName($plantName);
                $plant->setLevel();//a changer
                $plant->setDisplay(1);
                $manager->persist($plant);
                $manager->flush();
                $j=0;
                foreach($tableauImages[$plantName] as $image){
                $j+=1;
                $plantImage=new PlantImages();
                $plantImage->setPlant($plant);
                $plantImage->setImage("fixtures/".$image);
                $manager->persist($plantImage);
                $manager->flush();
            };
      }
    }
}