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
        // Compte administrateur : ADMIN_EMAIL / ADMIN_PASSWORD.
        // Le mot de passe « password » n'est accepté qu'en développement local.
        $adminPassword = env('ADMIN_PASSWORD') ?: (app()->isLocal() ? 'password' : null);

        if ($adminPassword === null) {
            throw new \RuntimeException('ADMIN_PASSWORD doit être défini hors développement local.');
        }

        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@cameraautokey.fr')],
            ['name' => 'Administrateur', 'password' => $adminPassword, 'is_admin' => true],
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
            ['🔑', 'Remplacement de toutes les clés', 'cle-voiture-perdue', 75000, 'Plus une seule clé pour votre voiture ? Nous en créons une neuve à partir du véhicule, sans aucun double.'],
            ['🚚', 'Clés de camions et bus', 'cle-camion-perdue', 110000, 'Porteurs, tracteurs routiers, minibus : nous remettons votre véhicule de travail en service, sur votre parc ou à l’atelier.'],
            ['🏍️', 'Clés de motos et tricycles', 'cle-moto-perdue', 60000, 'Moto, scooter ou tricycle immobilisé faute de clé : taille et codage d’une clé neuve.'],
            ['🚪', 'Ouverture sans clé', 'ouverture-vehicule-sans-cle', 35000, 'Clés oubliées à l’intérieur ? Nous ouvrons votre véhicule proprement, sans casse.'],
            ['💻', 'Codage de clé', 'programmation-cle-automobile', 40000, 'Clé qui ouvre mais ne démarre plus, télécommande muette : nous la recodons avec nos outils de diagnostic.'],
            ['🛠️', 'Réparation de télécommande', 'reparation-telecommande-voiture', 15000, 'Coque fendue, boutons enfoncés, pile ou circuit fatigué : on remet votre clé en état.'],
            ['🩺', 'Diagnostic électronique', 'diagnostic-auto-voiture', 20000, 'Lecture des défauts, voyants allumés, antidémarrage : on cherche la vraie panne avant de remplacer quoi que ce soit.'],
        ];

        foreach ($services as $i => [$icon, $title, $slug, $price, $excerpt]) {
            Service::updateOrCreate(['slug' => $slug], [
                'icon' => $icon,
                'title' => $title,
                'price_from' => $price,
                'excerpt' => $excerpt,
                'position' => $i,
                'content' => $excerpt."\n\nLe travail se fait à notre boutique de Douala ou là où se trouve votre véhicule, avec nos machines de taille, nos outils de codage et un stock de clés vierges. Le prix vous est annoncé en FCFA avant de commencer, et vous ne payez qu’après avoir essayé la clé.\n\nGarantie d’un mois sur chaque clé, extensible à douze mois.",
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
                    'description' => "Copie, codage et remplacement des clés {$name} à Douala, avec des outils de diagnostic compatibles avec l’antidémarrage {$name}.",
                ]);
            }
        }
    }

    private function seedTestimonials(): void
    {
        $items = [
            ['Jean-Paul Mbarga', 'Douala – Bonapriso', 'Clé de ma Corolla perdue un dimanche soir : le technicien est venu à Bonapriso en moins d’une heure et m’a refait une clé programmée devant moi. Service impeccable.'],
            ['Aïcha Bello', 'Douala – Akwa', 'Double de clé télécommande pour mon RAV4 fait à la boutique en 30 minutes, prix annoncé respecté. Bien moins cher que chez le concessionnaire.'],
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
            ['Quel est le prix d’une clé de voiture ?', 'Comptez à partir de 25 000 FCFA pour la copie d’une clé mécanique. Le montant dépend du véhicule, du type de clé et de la gamme choisie ; il vous est toujours confirmé avant de commencer.'],
            ['Peut-on faire une clé si je n’ai plus l’ancienne ?', 'Oui. Nous lisons les informations du véhicule pour tailler et coder une clé neuve, puis nous pouvons effacer les clés perdues de la mémoire du véhicule.'],
            ['Combien de temps faut-il ?', 'En boutique, une copie de clé prend souvent une trentaine de minutes. Pour un déplacement, le délai dépend du quartier et de la circulation : nous vous donnons une heure de passage au téléphone.'],
            ['Comment se passe le paiement ?', 'Rien à payer lors de la demande. Vous réglez après avoir essayé la clé : Orange Money, MTN Mobile Money, espèces ou carte bancaire.'],
            ['Que dois-je présenter ?', 'La carte grise du véhicule et une pièce d’identité au même nom. Sans ces documents, nous ne fabriquons pas de clé : c’est une protection contre le vol.'],
            ['La clé est-elle garantie ?', 'Oui, un mois sur chaque clé. Vous pouvez choisir une garantie de douze mois au moment de la demande.'],
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
