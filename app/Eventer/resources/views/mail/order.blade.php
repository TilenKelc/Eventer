<img src="https://izposoja.11-11.si/company_images/logo.png" width="150" height="58" alt="">
<br><br>

<h3>Pozdravljeni {{ $customer->name . ' ' . $customer->surname }},</h3>
<div>obveščamo vas, da se je plačilo rezervacije opreme izvedlo uspešno,
za izposojo od {{ date('d.m.Y', strtotime($rent->rental_from)) }} do {{ date('d.m.Y', strtotime($rent->rental_to)) }}
za naslednjo opremo:
<ul>
    @foreach($products as $product)
        <li>Artikel: {{ $product->name }}</li>
    @endforeach
</ul>
Vrednost že plačane akontacije: 40 EUR.
Skupni znesek izposoje izbrane opreme plačate ob prevzemu in znaša {{ $rent->total_price }} EUR.</div>
<br>
Lep pozdrav,
ekipa Sport11
<br><br>
<i>To je sistemsko generirano sporočilo, zato nanj ne odgovarjajte.</i>
