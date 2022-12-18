@extends('layouts.app')

@section('content')
    @include('common.errors')
    <div class="row">

      <div id="splosni-pogoji">
      
      <h5>Splošni pogoji</h5>

      

      <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
      </p>
      
      <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
      </p>
      <!--


      <h5>Kako rezerviram?</h5>
      <p>Uporabnikom spletne aplikacije sta omogočena dva načina iskanja razpoložljivosti opreme. Uporabniki lahko najprej izberejo termin in se jim na podlagi tega prikazuje le oprema, ki je na voljo v izbranem časovnem oknu.
      Druga možnost je iskanje točno določenega artikla preko filtra kategorij in velikosti. V tem primeru uporabnik želen termin izbere na samostojnem prikazu opreme.</p>
      <p>Ko uporabnik določi termin rezervacije in seznam opreme, je potrditev registracije možna s plačilom akontacije v višini 40€. Akontacija se uporabniku, v primeru uspešnega in pravočasnega vračila opreme v celoti povrne.
      Plačilo je možno s karticami Visa, MasterCard in Maestro.</p>


      <h5>Kdaj in kje kolo prevzamem / vrnem?</h5>
      <p>Kolo bo pred izdajo še enkrat pregledano s strani naših serviserjev in pripravljeno na vašo kolesarsko avanturo. Kolesa lahko prevzamete v naši trgovini Šport 11 v Kranju (Šuceva ulica 21, 4000 Kranj) v času delovnih ur. Enako velja za vračilo kolesa.
        <br>
      - Rezervirano kolo lahko za naslednji dan prevzamete dan prej po 15:00 (v kolikor je kolo na voljo za prevzem).
      <br>
      - Izposojeno kolo je potrebno vrniti do 12:00 ure, v nasportnemprimeru se zamuda obračuna po ceniku.

      <h5>Kako plačam?</h5>
      <p>
      Plačilo najema kolesa opravite pred prevzemom v naši trgovini s kreditno kartico (Mastercard, Visa...), katera bo navedana tudi na najemni pogodbi. <br>
      * S seboj potrebujete veljaven osebni dokument in kreditno kartico na kateri vam rezerviramo sredstva za varščino kolesa, ki se vam vrnejo nazaj na račun, ko kolo vrnete v primeru, da je kolo v takem stanju kot ste ga prevzeli. V kolikor na kolesu pride do poškodb, najemnik sam plača nastalo škodo in stroške popravila.
      </p>


      <h5>Pogoji najema:</h5>
      <p>
      1. Stranka s podpisom jamči za resničnost podatkov. 2. Artikel za naslednji dan je možno prevzeti dan prej po 15:00 uri v kolikor je ta že na voljo za izposojo. 3. Če stranka zamudi z vračilom ji obračunamo dodatne dni najema. 4. ŽigaŠport d.o.o., PE Šport 11 lahko v primeru nevračila artikla po petih dneh vnovči založena sredstva in prijavi odtujitev artikla organom Policije. 5. V primeru, da stranka vrne artikel poškodovan, poškodbe pa niso posledica normalne uporabe, se stranki zaračuna popravilo artikla po veljavnem ceniku servisnih storitev ŽigaŠport d.o.o. V kolikor artikla ni mogoče popraviti, je stranka dolžna plačati maloprodajno ceno artikla. 6. Uporaba izposojenega artikla je na lastno odgovornost! 7. Pri izposoji stranka obvezno predloži osebni dokument. 8. Stranki vrnemo kavcijo, ko vrne nepoškodovan artikel. 9. Artikel je potrebno vrniti do 12:00 ure, v nasprotnem primeru se zamuda obračuna po ceniku! 10. Artikel je možno v poslovalnici rezervirati s predhodnim plačilom najema (plača število dni najema). 11. Izposojeni artikel je potrebno vrniti v poslovalnico, kjer je bil izposojen. Uporabnik/najemnik s podpisom najemnega obrazca izjavlja, da je opremo natančno pregledal in da na njej ni napak. Izjavlja, da izposojeno opremo zna pravilno uporabljati in prevzema vso odgovornost za škodo, ki bi morebiti nastala v zvezi z njeno uporabo. Vnaprej se odpovedujee vsem zahtevkom zoper podjtetja ŽigaŠport d.o.o. v zvezi z uporabo izposojene opreme. Jamči, da bo opremo uporabljal samo podpisnik/najemnik in v primeru, da bi kakršnakoli škoda v zvezi z izposojeno opremo nastala tretjim osebam prevzema vso odgovornost za nastalo škodo in bo po potrebi podjetje ŽigaŠport d.o.o. v celoti odškodoval.
      </p>

      <h5>Varstvo osebnih podatkov:</h5>
      <p>
      ŽigaŠport d.o.o., Milje 57, 4212 Visoko (Šport 11, kot upravljalec osebnih podatkov), bo ime, priimek, naslov, telefonsko številko, e-naslov ter tip in številko osebnega dokumenta stranke (v nadaljevanju podatki), ki jih ta navede v tem obrazcu in so potrebni za izvajanje najemne pogodbe, obdeloval le z namenom sklenjene najemne pogodbe.
      V času obdelave podatkov ima stranka pravico do dostopa do podatkov, ki se nanašajo nanjo, ter do popravka, omejitve obdelave, prenosljivosti ali izbrisa vseh ali posameznih podatkov, skladno z veljavnimi predpisi, in sicer pisno z zahtevo, posredovano na sedež podjetja ŽigaŠport d.o.o., Milje 57, 4212 Visoko ali preko elektronske pošte info@11-11.si. Stranka ima pravico do ugovora in do vložitve pritožbe pri Informacijskem pooblaščencu.
      Podatke, ki jih na podlagi pogodbenih obveznosti in zakonskih predpisov obdeluje za namen izvajanja najemne pogodbe, bo ŽigaŠport d.o.o. (Šport 11) hranil 6 let od zaključka najema oz. skladno z zakonsko določenimi roki hrambe.
      Uporabnik osebnih podatkov je ŽigaŠport d.o.o. (PE Šport 11). Pooblaščeno osebo za varstvo osebnih podatkov v podjetju ŽigaŠport d.o.o. je možno kontaktirati na e-mail info@11-11.si.
      </p>-->
    </div>
@endsection
