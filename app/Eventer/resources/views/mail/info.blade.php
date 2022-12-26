<h3>Obvestilo</h3>
<div>Oseba {{ $customer->name . ' ' . $customer->surname }}, je danes {{ date('d.m.Y h:i') }} potrdila rezervacijo
od {{ date('d.m.Y h:i', strtotime($rent->rental_from)) }} do {{ date('d.m.Y h:i', strtotime($rent->rental_to)) }},
v restavraciji {{ App\Category::find($products[0]->category_id)->name }}.

<br><br>
<i>To je sistemsko generirano sporočilo, zato nanj ne odgovarjajte.</i>
