@php $tel = preg_replace('/\s+/', '', $settings['phone']); @endphp
<div class="sidebar-sticky">
    <x-sc icon="fas fa-bolt" title="{{ $sideTitle ?? 'Besoin d’une intervention ?' }}" sub="Réponse immédiate, 24h/24" class="side-card">
        <a href="{{ route('home') }}#commande" class="btn-red">Demande en ligne</a>
        <a href="tel:{{ $tel }}" class="btn-outline"><i class="fas fa-phone-alt"></i> {{ $settings['phone'] }}</a>
        <div class="trust-list">
            <span class="trust-item"><span class="tdot"></span>Prix annoncé avant intervention</span>
            <span class="trust-item"><span class="tdot"></span>Paiement après la prestation</span>
            <span class="trust-item"><span class="tdot"></span>Garantie 1 mois sur chaque clé</span>
        </div>
    </x-sc>
    @isset($links)
        <x-sc icon="fas fa-list" title="{{ $linksTitle ?? 'Nos autres services' }}">
            <div class="link-list">
                @foreach ($links as $href => $text)
                    <a href="{{ $href }}"><i class="fas fa-angle-right" style="color:var(--red)"></i> {{ $text }}</a>
                @endforeach
            </div>
        </x-sc>
    @endisset
    <x-sc icon="fas fa-tools" title="Besoin d’un garage ?" sub="{{ $settings['garage_name'] }}">
        <div class="key-cross">
            <img src="{{ asset('images/logo-3k-auto-services.webp') }}" alt="" width="54" height="50" loading="lazy">
            <p style="font-size:13.5px;color:var(--muted)">Mécanique, vidange, diagnostic, freins, clim : notre garage partenaire à Ndogsimbi.</p>
        </div>
        <a href="{{ route('garage') }}" class="btn-outline" style="justify-content:center;width:100%;margin-top:10px"><i class="fas fa-wrench"></i> Découvrir le garage 3K</a>
    </x-sc>
</div>
