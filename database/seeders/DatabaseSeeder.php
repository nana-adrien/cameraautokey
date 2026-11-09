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
            ['🔑', 'Perte totale de clés auto', 'cle-voiture-perdue', 129, 'Plus aucune clé ? Nous fabriquons et programmons une nouvelle clé sur place, sans passer par le concessionnaire.'],
            ['🚚', 'Perte totale poids lourd', 'cle-camion-perdue', 175, 'Camions, porteurs et semi-remorques : remise en route rapide de votre flotte, directement sur votre site.'],
            ['🏍️', 'Perte totale moto', 'cle-moto-perdue', 109, 'Clé de moto ou de scooter perdue : fabrication et codage d’une nouvelle clé là où se trouve votre deux-roues.'],
            ['🚪', 'Ouverture de véhicule', 'ouverture-vehicule-sans-cle', 89, 'Clé enfermée dans l’habitacle ? Ouverture sans dégât par technique professionnelle, de jour comme de nuit.'],
            ['💻', 'Programmation de clé', 'programmation-cle-automobile', 79, 'Codage OBD, synchronisation de transpondeur et de télécommande sur la grande majorité des marques.'],
            ['🛠️', 'Réparation de télécommande', 'reparation-telecommande-voiture', 39, 'Boutons usés, coque cassée, pile ou circuit défaillant : réparation à moindre coût de votre clé actuelle.'],
            ['🩺', 'Diagnostic automobile', 'diagnostic-auto-voiture', 59, 'Lecture des codes défaut et diagnostic électronique de l’antidémarrage et des calculateurs.'],
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
            'auto' => ['Alfa Romeo', 'Audi', 'BMW', 'Citroën', 'Cupra', 'Dacia', 'DS Automobiles', 'Fiat', 'Ford', 'Honda', 'Hyundai', 'Jaguar', 'Jeep', 'Kia', 'Land Rover', 'Lexus', 'Mazda', 'Mercedes', 'MG Motor', 'Mini', 'Mitsubishi', 'Nissan', 'Opel', 'Peugeot', 'Porsche', 'Renault', 'Seat', 'Skoda', 'Smart', 'Tesla', 'Toyota', 'Volkswagen', 'Volvo'],
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
            ['Thomas Lefèvre', 'Lyon', 'Clé perdue un dimanche soir, le technicien est arrivé en moins d’une heure. Nouvelle clé fabriquée et programmée devant moi. Service impeccable.'],
            ['Sophie Garnier', 'Nanterre', 'Double de clé télécommande pour ma Clio, prix annoncé respecté à l’euro près. Beaucoup moins cher que chez le concessionnaire.'],
            ['Karim Benali', 'Bruxelles', 'Mes clés étaient restées dans le coffre. Ouverture sans aucune rayure et explications très claires. Je recommande.'],
            ['Émilie Dupont', 'Marseille', 'Télécommande qui ne répondait plus : réparée et resynchronisée en vingt minutes. Rapide, propre et professionnel.'],
            ['Marc Lambert', 'Lille', 'Nous gérons une flotte de 12 utilitaires, CAMERAUTOKEY est devenu notre partenaire pour toutes les clés. Réactif et fiable.'],
        ];

        foreach ($items as [$name, $city, $content]) {
            Testimonial::updateOrCreate(['name' => $name], ['city' => $city, 'content' => $content, 'rating' => 5]);
        }
    }

    private function seedFaqs(): void
    {
        $faqs = [
            ['Combien coûte un double de clé de voiture ?', 'Nos tarifs démarrent à 49 € TTC pour une clé simple. Le prix dépend du type de clé, du véhicule et des options. Il est toujours annoncé avant le départ du technicien.'],
            ['Pouvez-vous refaire une clé sans l’originale ?', 'Oui. En cas de perte totale, nous fabriquons une nouvelle clé à partir de la serrure ou des données du véhicule, puis nous la programmons sur place.'],
            ['En combien de temps intervenez-vous ?', 'En urgence, un technicien se met en route immédiatement, généralement en 30 à 60 minutes selon votre zone. Hors urgence, nous fixons un rendez-vous à votre convenance.'],
            ['Quand suis-je débité ?', 'Aucun débit à la commande. Le paiement n’intervient qu’à la fin de la prestation, une fois que vous l’avez validée.'],
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
            ['Alexandre Morel', '06 11 22 33 44', 'Île-de-France'],
            ['Nicolas Petit', '06 55 66 77 88', 'Auvergne-Rhône-Alpes'],
            ['Yassine Haddad', '0470 12 34 56', 'Belgique'],
        ])->map(fn ($t) => Technician::updateOrCreate(['name' => $t[0]], [
            'phone' => $t[1],
            'zone' => $t[2],
            'email' => Str::slug($t[0], '.').'@cameraautokey.fr',
        ]));

        if (Order::exists()) {
            return;
        }

        $calculator = new PriceCalculator;
        $samples = [
            ['auto', 'double', 'remote', 'generic', 1, false, false, 'completed', 'Peugeot 308', 'Paris', '75011', 'Claire Rousseau'],
            ['auto', 'lost', 'smart', 'original', 2, true, true, 'in_progress', 'BMW Série 3', 'Boulogne-Billancourt', '92100', 'Hugo Bernard'],
            ['moto', 'double', 'transponder', 'generic', 1, false, false, 'assigned', 'Yamaha MT-07', 'Lyon', '69003', 'Inès Moreau'],
            ['pl', 'lost', 'remote', 'generic', 2, false, true, 'pending', 'Renault Trucks T', 'Bruxelles', '1000', 'Transports Delcourt'],
            ['auto', 'opening', 'transponder', 'generic', 1, false, true, 'pending', 'Renault Clio IV', 'Marseille', '13008', 'Léa Fontaine'],
            ['auto', 'double', 'simple', 'generic', 1, false, false, 'cancelled', 'Dacia Sandero', 'Lille', '59000', 'Paul Girard'],
        ];

        foreach ($samples as $i => [$vehicle, $need, $keyType, $desired, $qty, $warranty, $emergency, $status, $model, $city, $cp, $name]) {
            $data = [
                'vehicle_type' => $vehicle, 'need' => $need, 'key_type' => $keyType, 'desired_key' => $desired,
                'quantity' => $qty, 'warranty_extension' => $warranty, 'emergency' => $emergency,
            ];

            Order::create($data + [
                'plate' => 'AB-'.(100 + $i * 37).'-CD',
                'brand_model' => $model,
                'year' => 2012 + $i * 2,
                'customer_name' => $name,
                'customer_phone' => '06 12 34 56 '.str_pad((string) ($i * 11), 2, '0', STR_PAD_LEFT),
                'customer_email' => 'client'.($i + 1).'@example.com',
                'address' => (12 + $i * 7).' rue de la République',
                'postal_code' => $cp,
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
