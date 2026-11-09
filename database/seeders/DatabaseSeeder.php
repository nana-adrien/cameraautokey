<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\ContactMessage;
use App\Models\Faq;
use App\Models\Order;
use App\Models\Service;
use App\Models\Technician;
use App\Models\Testimonial;
use App\Models\User;
use App\Services\PriceCalculator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Compte administrateur de développement (modifiable via ADMIN_EMAIL / ADMIN_PASSWORD dans .env).
        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@cameraautokey.fr')],
            ['name' => 'Administrateur', 'password' => env('ADMIN_PASSWORD', 'password'), 'is_admin' => true],
        );

        $this->seedServices();
        $this->seedBrands();
        $this->seedTestimonials();
        $this->seedFaqs();
        $this->seedTechniciansAndOrders();

        ContactMessage::firstOrCreate(['email' => 'julien.martin@example.com'], [
            'type' => 'contact',
            'name' => 'Julien Martin',
            'phone' => '06 00 00 00 01',
            'subject' => 'Devis clé Peugeot 308',
            'message' => 'Bonjour, je souhaiterais un devis pour un double de clé télécommande sur une Peugeot 308 de 2017.',
        ]);
    }

    private function seedServices(): void
    {
        $services = [
            ['🔑', 'Perte totale de clés auto', 'cle-voiture-perdue', 75000, 'Plus aucune clé ? Nous fabriquons et programmons une nouvelle clé sur place, sans passer par le concessionnaire.'],
            ['🚚', 'Perte totale poids lourd', 'cle-camion-perdue', 110000, 'Camions, porteurs et semi-remorques : remise en route rapide de votre flotte, directement sur votre site.'],
            ['🏍️', 'Perte totale moto', 'cle-moto-perdue', 60000, 'Clé de moto ou de scooter perdue : fabrication et codage d’une nouvelle clé là où se trouve votre deux-roues.'],
            ['🚪', 'Ouverture de véhicule', 'ouverture-vehicule-sans-cle', 35000, 'Clé enfermée dans l’habitacle ? Ouverture sans dégât par technique professionnelle, de jour comme de nuit.'],
            ['💻', 'Programmation de clé', 'programmation-cle-automobile', 40000, 'Codage OBD, synchronisation de transpondeur et de télécommande sur la grande majorité des marques.'],
            ['🛠️', 'Réparation de télécommande', 'reparation-telecommande-voiture', 15000, 'Boutons usés, coque cassée, pile ou circuit défaillant : réparation à moindre coût de votre clé actuelle.'],
            ['🩺', 'Diagnostic automobile', 'diagnostic-auto-voiture', 20000, 'Lecture des codes défaut et diagnostic électronique de l’antidémarrage et des calculateurs.'],
        ];

        foreach ($services as $i => [$icon, $title, $slug, $price, $excerpt]) {
            Service::updateOrCreate(['slug' => $slug], [
                'icon' => $icon,
                'title' => $title,
                'price_from' => $price,
                'excerpt' => $excerpt,
                'position' => $i,
                'content' => $excerpt."\n\nNos techniciens CAMERAUTOKEY se déplacent avec un atelier mobile complet : machine de taille, programmateurs et stock de clés compatibles. Le prix vous est communiqué avant le départ du technicien et vous ne réglez qu’une fois la prestation terminée et validée.\n\nChaque clé fabriquée est garantie un mois, avec une extension de garantie possible d’un an.",
            ]);
        }
    }

    private function seedBrands(): void
    {
        $brands = [
            'auto' => ['Acura', 'Alfa Romeo', 'Audi', 'Bentley', 'BMW', 'Cadillac', 'Chevrolet', 'Chrysler', 'Citroën', 'Cupra', 'Dacia', 'DS Automobiles', 'Fiat', 'Ford', 'Honda', 'Hyundai', 'Infiniti', 'Jaguar', 'Jeep', 'Kia', 'Land Rover', 'Lexus', 'Maserati', 'Mazda', 'Mercedes', 'MG Motor', 'Mini', 'Mitsubishi', 'Nissan', 'Opel', 'Peugeot', 'Porsche', 'Renault', 'Seat', 'Skoda', 'Smart', 'Subaru', 'Tesla', 'Toyota', 'Volkswagen', 'Volvo'],
            'pl' => ['Scania', 'Renault Trucks', 'Volvo Trucks', 'Mercedes Trucks', 'DAF', 'Iveco', 'MAN'],
            'moto' => ['Honda Moto', 'Yamaha', 'BMW Motorrad', 'Harley Davidson', 'Kawasaki', 'KTM', 'Ducati', 'Suzuki', 'Piaggio'],
        ];

        foreach ($brands as $category => $names) {
            foreach ($names as $name) {
                Brand::updateOrCreate(['slug' => Str::slug($name)], [
                    'name' => $name,
                    'category' => $category,
                    'description' => "Reproduction, programmation et remplacement de clés {$name} à domicile. Nos techniciens disposent du matériel de diagnostic adapté aux systèmes antidémarrage {$name}.",
                ]);
            }
        }
    }

    private function seedTestimonials(): void
    {
        $items = [
            ['Jean-Paul Mbarga', 'Douala – Bonapriso', 'Clé de ma Corolla perdue un dimanche soir : le technicien est venu à Bonapriso en moins d’une heure et m’a refait une clé programmée devant moi. Service impeccable.'],
            ['Aïcha Bello', 'Douala – Akwa', 'Double de clé télécommande pour mon RAV4 fait à l’atelier d’Akwa en 30 minutes, prix annoncé respecté. Bien moins cher que chez le concessionnaire.'],
            ['Serge Nkoulou', 'Yaoundé – Bastos', 'Mes clés étaient restées dans le coffre du Prado. Ouverture sans aucune rayure et explications très claires. Je recommande vivement.'],
            ['Carine Ewane', 'Douala – Bonamoussadi', 'Ma clé de Hilux était cassée en deux : nouvelle coque, nouvelle lame taillée et programmation le jour même. Rapide et propre.'],
            ['Patrick Fotso', 'Douala – Bassa', 'Nous gérons une flotte de pick-up : CAMER AUTO KEY refait toutes nos clés perdues et programme les doubles. Réactifs et fiables.'],
        ];

        foreach ($items as [$name, $city, $content]) {
            Testimonial::updateOrCreate(['name' => $name], ['city' => $city, 'content' => $content, 'rating' => 5]);
        }
    }

    private function seedFaqs(): void
    {
        $faqs = [
            ['Combien coûte un double de clé de voiture ?', 'Nos tarifs démarrent à 25 000 FCFA pour une clé simple. Le prix dépend du type de clé, du véhicule et des options. Il est toujours annoncé avant l’intervention.'],
            ['Pouvez-vous refaire une clé sans l’originale ?', 'Oui. En cas de perte totale, nous fabriquons une nouvelle clé à partir de la serrure ou des données du véhicule, puis nous la programmons sur place.'],
            ['En combien de temps intervenez-vous ?', 'À Douala, un technicien se met en route immédiatement, généralement en 30 à 60 minutes selon le quartier et la circulation. Vous pouvez aussi venir directement à notre atelier d’Akwa.'],
            ['Comment puis-je payer ?', 'Aucun paiement à la demande. Vous réglez à la fin de la prestation, une fois que vous l’avez validée : espèces, Orange Money, MTN Mobile Money ou carte bancaire.'],
            ['Quels documents dois-je fournir ?', 'La carte grise du véhicule et une pièce d’identité au même nom sont demandées avant toute fabrication de clé, pour des raisons de sécurité.'],
            ['Ma clé est-elle garantie ?', 'Chaque clé fabriquée est garantie un mois. Vous pouvez souscrire une extension de garantie d’un an lors de votre demande.'],
        ];

        foreach ($faqs as $i => [$question, $answer]) {
            Faq::updateOrCreate(['question' => $question], ['answer' => $answer, 'position' => $i]);
        }
    }

    private function seedTechniciansAndOrders(): void
    {
        $technicians = collect([
            ['Emmanuel Ndjock', '+237 677 11 22 33', 'Douala – Akwa / Bonanjo'],
            ['Rodrigue Tchoua', '+237 699 44 55 66', 'Douala – Bonamoussadi / Makepe'],
            ['Blaise Atangana', '+237 655 77 88 99', 'Yaoundé'],
        ])->map(fn ($t) => Technician::updateOrCreate(['name' => $t[0]], [
            'phone' => $t[1],
            'zone' => $t[2],
            'email' => Str::slug($t[0], '.').'@cameraautokey.cm',
        ]));

        if (Order::exists()) {
            return;
        }

        $calculator = new PriceCalculator;
        $samples = [
            ['auto', 'double', 'remote', 'generic', 1, false, false, 'completed', 'Toyota Corolla', 'Douala', 'Bonapriso', 'Jean-Paul Mbarga'],
            ['auto', 'lost', 'smart', 'original', 2, true, true, 'in_progress', 'Toyota Land Cruiser Prado', 'Douala', 'Bonanjo', 'Hervé Ekotto'],
            ['moto', 'double', 'transponder', 'generic', 1, false, false, 'assigned', 'Yamaha Crypton', 'Douala', 'Ndokoti', 'Aïcha Bello'],
            ['pl', 'lost', 'remote', 'generic', 2, false, true, 'pending', 'Mercedes Actros', 'Douala', 'Bassa', 'Transports Ngono'],
            ['auto', 'opening', 'transponder', 'generic', 1, false, true, 'pending', 'Hyundai Tucson', 'Yaoundé', 'Bastos', 'Carine Ewane'],
            ['auto', 'double', 'simple', 'generic', 1, false, false, 'cancelled', 'Nissan Juke', 'Douala', 'Makepe', 'Patrick Fotso'],
        ];

        foreach ($samples as $i => [$vehicle, $need, $keyType, $desired, $qty, $warranty, $emergency, $status, $model, $city, $district, $name]) {
            $data = [
                'vehicle_type' => $vehicle, 'need' => $need, 'key_type' => $keyType, 'desired_key' => $desired,
                'quantity' => $qty, 'warranty_extension' => $warranty, 'emergency' => $emergency,
            ];

            Order::create($data + [
                'plate' => 'LT '.(100 + $i * 37).' '.['AB', 'MC', 'JS', 'ND', 'CE', 'KL'][$i],
                'brand_model' => $model,
                'year' => 2012 + $i * 2,
                'customer_name' => $name,
                'customer_phone' => '+237 690 12 34 '.str_pad((string) ($i * 11), 2, '0', STR_PAD_LEFT),
                'customer_email' => 'client'.($i + 1).'@example.com',
                'address' => ['Rue Joffre, face pharmacie', 'Boulevard de la Liberté', 'Carrefour Ndokoti', 'Zone portuaire, entrepôt 4', 'Rue 1.750, près de l’ambassade', 'Carrefour Makepe Missoké'][$i],
                'district' => $district,
                'city' => $city,
                'estimated_price' => $calculator->estimate($data),
                'status' => $status,
                'technician_id' => in_array($status, ['assigned', 'in_progress', 'completed']) ? $technicians[$i % 3]->id : null,
                'completed_at' => $status === 'completed' ? now()->subDays(5) : null,
                'created_at' => now()->subDays(6 - $i)->subHours($i),
            ]);
        }
    }
}
