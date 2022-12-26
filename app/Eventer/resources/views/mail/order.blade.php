<h3>Pozdravljeni {{ $customer->name . ' ' . $customer->surname }},</h3>
<div>obveščamo vas, da se je plačilo rezervacije izvedlo uspešno,
za termin od {{ date('d.m.Y h:i', strtotime($rent->rental_from)) }} do {{ date('d.m.Y h:i', strtotime($rent->rental_to)) }},
v restavraciji {{ App\Category::find($products[0]->category_id)->name }}.

<br>
Lep pozdrav,
<br><br>
<i>To je sistemsko generirano sporočilo, zato nanj ne odgovarjajte.</i>
