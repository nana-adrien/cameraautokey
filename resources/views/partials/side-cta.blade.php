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
</div>
