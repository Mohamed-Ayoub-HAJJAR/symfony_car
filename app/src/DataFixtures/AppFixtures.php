<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Feature;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            Feature::CAT_SECURITY => [
                'ABS', 'ESP', 'Airbags frontaux', 'Airbags latéraux', 
                'Fixations Isofix', 'Projecteurs LED', 'Freinage d\'urgence'
            ],
            Feature::CAT_COMFORT => [
                'Climatisation automatique', 'Sièges chauffants', 'Régulateur de vitesse', 
                'Limiteur de vitesse', 'Direction assistée', 'Démarrage sans clé'
            ],
            Feature::CAT_MULTIMEDIA => [
                'GPS Tactile', 'Apple CarPlay / Android Auto', 'Bluetooth', 
                'Prise USB-C', 'Système Hi-Fi Premium', 'Ordinateur de bord'
            ],
            Feature::CAT_EXTERIOR => [
                'Jantes alliage 18"', 'Peinture métallisée', 'Toit ouvrant panoramique', 
                'Rétroviseurs dégivrants', 'Vitres surteintées'
            ],
        ];

        foreach ($data as $categoryName => $featureNames) {
            foreach ($featureNames as $name) {
                // On cherche si la feature existe déjà
                $existing = $manager->getRepository(Feature::class)->findOneBy(['name' => $name]);
                if (!$existing) {
                    $feature = new Feature();
                    $feature->setName($name);
                    $feature->setCategory($categoryName);
                    $manager->persist($feature);
                }
            }
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
