<img src="https://izposoja.11-11.si/company_images/logo.png" width="150" height="58" alt="">
<br><br>

<h3>Obvestilo</h3>
<div>Oseba {{ $customer->name . ' ' . $customer->surname }}, je danes {{ date('d.m.Y') }} potrdila rezervacijo
od {{ date('d.m.Y', strtotime($rent->rental_from)) }} do {{ date('d.m.Y', strtotime($rent->rental_to)) }}
za naslednjo opremo:
<ul>
    @foreach($products as $product)
        <li>Artikel: {{ $product->name }}, šifra: {{ $product->product_id }}, velikost: {{ App\Size::find($product->size_id)->size }}</li>
    @endforeach
</ul>
Rezervacija je bila potrjena s plačilom 40€, skupna cena izposoje: {{ $rent->total_price }} EUR.</div>
<br><br>
<i>To je sistemsko generirano sporočilo, zato nanj ne odgovarjajte.</i>
