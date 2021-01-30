<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Varatoimari / Laitetietokanta - Raportti</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/all.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="d-flex flex-column min-vh-100">
        <header class="column" style="padding-bottom:0">
            <div class="jumbotron text-center bg-dark" style="margin-bottom:0;border-radius:0;">
            <div class="container">
                    <h1 class="text-light">Varatoimari - laitetietokanta</h1>
                    <h3 class="text-light">14.08.2020</h3>
                    <h4 class="text-light">[TTMS0900] + [TTMS0500]</h4>
                    <div class="col-md-3 mx-auto text-white bg-dark">
                        <ul class="list-group">
                            <li class="list-group-item bg-dark">Alatalo, Tatu [N4927]</li>
                            <li class="list-group-item bg-dark">Bordi, Tuukka [M2296]</li>
                            <li class="list-group-item bg-dark">Halkosaari, Lassi [L3779]</li>
                        </ul>
                    </div>
                    
                </div>
            </div>
        </header>

        <div class="bg-secondary">
            <!-- main content -->
            <div id="content" class="bg-secondary">
                <div class="col-md-8 mx-auto bg-secondary">
                    <div class="card bg-light mb-5 mt-3">
                        <div class="card-header text-center">
                            <h2>Tehtävän kuvaus</h2>
                        </div>
                        <div class="card-body px-5">
                            <div class="mx-auto" style="max-width: 75%;">
                            <p>Harjoitustyö tehtiin osana Web-ohjelmointi [TTMS0500] ja Web-palvelinohjelmointi [TTMS0900] opintojaksoja.
                            Toimeksiannoksi saimme tehdä verkkosivun Varatoimari-yritykselle. Sivusto sisältää muokattavan laitetietokannan kaikista yrityksen asiakkaiden laitteista. 
                            Laitetietokantaa pystyy muokkaamaan selkeällä käyttöliittymällä ja sen kohderyhmänä toimii toimeksiantaja.</p>
                            <p>Harjoitustyön esittelyvideo löytyy tästä youtube-linkistä: <a href="https://www.youtube.com/watch?v=FTuJoErPsvg" target="_blank" class="font-weight-bold">Esittelyvideo</a>
                            <p>Tarkka suunnitelma löytyy tästä gitlab-linkistä: <a href="https://gitlab.labranet.jamk.fi/webkurssit/tuotetietokanta-harjoitustyo-web-kurssit/-/blob/master/suunnitelma.md" target="_blank" class="font-weight-bold">Harjoitustyön suunnitelma</a>
                            </p>
                            <p>Sivua pääsee käyttämään tästä linkistä: <a href="http://164.90.189.40/harjoitustyo/public/" target="_blank" class="font-weight-bold">Varatoimari - Laitetietokanta</a></p>
                            <p>
                            Sivustolle voi kirjautua tunnuksilla '<span class="font-weight-bold">testi@testi</span>' ja salasana '<span class="font-weight-bold">testi</span>'
                            </p>
                            </div>
                        </div>
                    </div>
                    <div class="card bg-light mb-5">
                        <div class="card-header text-center">
                            <h2>Käytännön toteutuksen selostus</h2>
                        </div>
                        <div class="card-body mx-auto">
                            <div class="mx-auto" style="max-width: 75%;">
                                <h3 class="mt-5 mb-3"><u>PHP/Laravel</u></h3>
                                    <p class="font-weight-bold mt-3">Käyttäjien käsittely ja kirjautuminen</p>
                                        <p>Kirjatumistoiminnot on hoidettu Laravlin sisäänrakennetuilla ominaisuuksilla. 
                                        Uusien käyttäjien luonti rekisteröintilomakkeella on disabloitu luvattoman pääsyn estämiseksi. 
                                        Uusia käyttäjä voi luoda tosin CSV-tiedostoista kirjautumisen jälkeen. Käyttäjien salasanat on hashatty tietokannassa ja uusille käyttäjille tulee luoda vähintään kahdeksan merkkinen salasana.
                                        </p>
                                    <p class="font-weight-bold mt-4">Tietokantaoperaatiot</p>
                                        <p>Tietokantaoperaatioissa käytetään pääasiassa Eloquentia eli Laravelin mukana tullutta tietokantarajapintaa. 
                                        Muutamassa asiassa käytetään esim DB-kirjastoa. Koska sivustolle haluttiin Ajax-toiminnallisuus, 
                                        tehtiin Ajaxin käytön helpottamiseksi universaali Eloquentia ja osin DB-kirjastoa käyttävä DatabaseController.
                                        </p>
                                    <p class="font-weight-bold">Asiakkaiden selausnäkymä (dashboard)</p>
                                        <p>Toimeksiantajan toivomus, oli että dashboardissa näkyisi viisi viimeksi muokattua laitetta, viisi uusinta käyttäjää ja viisi uusinta asiakasta. 
                                        Tähän päästiin osin tekemämme sort-toiminnon avulla. Toteutuksessamme näkyy asiakas ja muutama asiakkaan oleellisempia tietoja. 
                                        Asiakasta klikkaamalla pääsee asiakasnäkymään, josta kerromme seuraavaksi.
                                        </p>
                                    <p class="font-weight-bold">Asiakasnäkymä ja sen toiminnot</p>
                                        <p>Asiakasnäkymä on näkymistämme monipuolisin. Näkymästä voi sekä muokata asiakkaan omia tietoja, 
                                        listata asiakkaan käyttäjät eli työntekijät sekä muokata laitteita klikkaamalla (tämä ominaisuus jäi tosin kesken).
                                        Asiakkaan omien tietojen muokkaus tehdään Ajaxilla, joskin lomake on tehty PHP:llä. 
                                        Ajaxin virkaa hoitaa Jquery, joka ottaa yhteyden DatabaseControlleriin. Syötteen validointi tapahtuu itse tekemämme validaattorin kautta. 
                                        Lomakkeessa on yksinkertainen validointi, ja viime kädessä “validoinnin” tekee tietokanta (esim. foreign key constraints).
                                        </p>
                                    <p class="font-weight-bold">CSV-toiminnallisuus</p>
                                        <p>Täysin PHP:llä tehty toteutus. Validointi suoraan regexpillä, tietokantayhteys PDO:lla. 
                                        Yksi harvoista sivuston osasta, joka voisi toimia hyvin myös ilman Laravel-kehystä, joskin Controlleri pitäisi siinä tapauksessa muuntaa johonkin toiseen muotoon.
                                        </p>
                                        <p>
                                        CSV-toiminnallisuus perustuu pitkälti CsvControlleriin, joka löytyy sijainnista <a class="font-weight-bold" href="http://164.90.189.40/harjoitustyo_palautus/main_source_codes/app/Http/Controllers/00-php-syntax-highlighter.php?action=show&fiilu=CsvController.php" target="_blank">CsvController</a>. Kyseisen controllerin avulla voidaan verkkosivulle ladata tietyn muotoisia CSV-tiedostoja (lataus mahdollisuus Device:lle, Customerille ja Userille), joiden sisältö laitetaan MySQL tietokantaan controllerin asetusten mukaisesti. Tiedostojen muodot ovat toimeksiantajan haluamia tiedostojen muotoja. Device tiedoston muoto tulee toimeksiantajan valmiiden tiedostojen muodosta kuten myös customer. User lomake on implementoitu, että pystyy lisäämään officen contactiluettelosta otetun CSV-tiedoston suoraan. Lomake löytyy osoitteesta <a href="http://164.90.189.40/harjoitustyo/public/import" target="_blank" class="font-weight-bold">Import</a>. Testauksessa käytetyt lisättävät tiedostot löytyy osoitteesta <a class="font-weight-bold" href="http://164.90.189.40/harjoitustyo_palautus/csv_testilisäystiedostoja" target="_blank">Testaustiedostot</a>. Jos olisi ollut enemmän aikaa, tähän olisi myös lisätty tiedoston lataus palvelimelta eli exporttaus toiminto.
                                        </p>
                                    <p class="font-weight-bold">Sivuilla liikkuminen</p>
                                        <p>Navbar, käyttäjäkohtainen drop-down (sisältää esim. Logout-linkin) sekä linkit sivuilla. 
                                        Spesiaalitoimintona joillain sivuilla välilehdet, jotka toimivat Bootstrapilla ja JQueryllä. 
                                        Dashboardissa myös sorttaustoiminto jQueryn lisäosan DataTablesin avulla. Kaikki muu navigointi on renderöity Laravelilla.
                                        </p>
                                    <p class="font-weight-bold">Tietokannan käsittely</p>
                                        <p>Pääosin kaikki tietokantaan liittyvä tehdään ottamalla yhteys johonkin Controlleriin, joka käyttää hyväksi Eloquent mallia tietokantamuokkauksissa. 
                                        Jopa Ajax-jutut menevät lopulta jonkun Eloquent mallin läpi. Ainoa poikkeus tästä on CSV-vienti sivustolle, jossa tietokantayhteys hoituu PDO:lla.
                                        </p>
                                <h3 class="mt-5 mb-3"><u>Javascript/React</u></h3>
                                    <p>Teimme toki jotain webohjelmoinninkin saralla. Lassi teki Käyttäjän, laitteen ja asiakkaanlisäyslomakkeen, allekirjoittanut (Tuukka) teki universaalia lisäys/ja muokkauslomaketta (joka jäi kesken) ja Tatu teki paljon hienoja juttuja UI:n ja Ajaxin parissa JQueryllä, DataTables-kirjastolla, ja Bootstrapin Javascript-toiminnoilla.
                                    Ajatus oli, että kaikki lisäys- ja muokkauslomakkeet toimisivat Ajaxilla. Näin se pääasiassa menikin. 
                                    Asiaa hidasti vain se, että Backend piti tehdä ensin (tämän takia laskisin myös backendin teon webohjelmoinnin piiriin).
                                    </p>
                                    <p>
                                    Addnewitemistä lisätietoa: Toteutettiin Reactilla verkkosivulle osoitteeseen <a href="http://164.90.189.40/harjoitustyo/public/addnewitem" target="_blank" class="font-weight-bold">Addnewitem</a>. Siellä on mahdollisuus lisätä uusi laite, asiakas tai käyttäjä tietokantaan. Aluksi valitaan mitä halutaan lisätä ja sen jälkeen täytetään lomake tiettyjen vaatimusten mukaisesti. Nämä vaatimukset näkyvät vain virheviestissä, koska työ tulee toimeksiantajalle joka tietää mitä niihin pitää lisätä niin niitä ei näe selkeästi muualta. Select valikkoihin haetaan databasesta sivun latauksen yhteydessä kaikki tiedot. Validointi näihin löytyy kansiosta <a class="font-weight-bold" href="http://164.90.189.40/harjoitustyo_palautus/main_source_codes/app/Http/Requests" target="_blank">/main_source_codes/app/Http/Requests</a>. Parannusehdotuksena kaikkiin tietokantoihin datan lisääminen (Päätettiin jättää tästä pois niin Tuukka saa tehtyä myös reactilla hommia), lomakkeiden ja tietojen muokkaus samoja lomakkeita hyväksikäyttäen.
                                    </p>
                            </div>
                            <div class="mx-auto mt-5" style="max-width: 75%;">
                                <i>Klikkaa kuvia niin ne avautuvat uuteen välilehteen</i>
                            </div>
                            <div class="card mb-4 mx-auto" style="max-width: 75%;">
                                <div class="card-header font-weight-bold">Tietokanta</div>
                                <div class="card-body">
                                    <a href="/harjoitustyo_palautus/images/entity_relations.png" target="_blank">
                                        <img src="/harjoitustyo_palautus/images/entity_relations.png" class="rounded mx-auto d-block img-fluid" alt="entity relations" width="450" height=>
                                    </a>
                                    <br>
                                    <p>Tietokannan luontiin käytetyt migraatio-tiedostot ja populointi-scripti löytyy <a class="font-weight-bold" href="http://164.90.189.40/harjoitustyo_palautus/main_source_codes/database/" target="_blank">tästä</a></p>
                                </div>
                            </div>
                            <div class="card mb-4 mx-auto" style="max-width: 75%;">
                                <div class="card-header font-weight-bold">Päänäkymät</div>
                                <div class="card-body">
                                    <a href="/harjoitustyo_palautus/images/paanakymat.png" target="_blank">
                                        <img src="/harjoitustyo_palautus/images/paanakymat.png" class="rounded mx-auto d-block img-fluid" alt="päänäkymät" width="450" height=>
                                    </a>
                                    <br>
                                    <p>Tässä esiteltynä kansionäkymät kontrollereineen sekä mihin tuotantovaiheeseen ne jäivät.</p>
                                </div>
                            </div>
                            <div class="card mb-4 mx-auto" style="max-width: 75%;">
                                <div class="card-header font-weight-bold">Sivustokohtaiset toiminnot</div>
                                <div class="card-body">
                                    <a href="/harjoitustyo_palautus/images/sivustokohtaiset toiminnot.png" target="_blank">
                                        <img src="/harjoitustyo_palautus/images/sivustokohtaiset toiminnot.png" class="rounded mx-auto d-block img-fluid" alt="päänäkymät" width="450" height=>
                                    </a>
                                    <br>
                                    <p>Tässä esiteltynä sivustokohtaiset toiminnot ja niitä hoitavat kontrollerit, sekä mihin tuotantovaiheeseen ne jäivät. Lisäksi kerrotaan, käytetäänkö toiminnallisuutta javascriptin vai php:n kautta. </p>
                                </div>
                            </div>
                            <div class="card mb-4 mx-auto" style="max-width: 75%;">
                                <div class="card-header font-weight-bold">Addnewitem swim lane</div>
                                <div class="card-body">
                                    <a href="/harjoitustyo_palautus/images/addnewitem.png" target="_blank">
                                        <img src="/harjoitustyo_palautus/images/addnewitem.png" class="rounded mx-auto d-block img-fluid" alt="päänäkymät" width="450" height=>
                                    </a>
                                    <br>
                                    <p>Swim lane -vuokaavio Addnewitem -toiminnallisuudesta</p>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="card bg-light mb-5">
                    <div class="card-header text-center">
                            <h2>Lähdekoodit</h2>
                        </div>
                        <div class="card-body mx-auto">
                            <p>Sovelluksen "olennaiset" lähdekoodit löytyvät täältä: 
                                <a href="http://164.90.189.40/harjoitustyo_palautus/main_source_codes/" target="_blank" class="font-weight-bold">Main source codes</a>
                            </p>
                            <P>Sovelluksen <span class="font-weight-bold">kaikki</span> lähdekoodit löytyy täältä: 
                                <a href="http://164.90.189.40/harjoitustyo_palautus/all_source_codes/" target="_blank" class="font-weight-bold">All source codes</a>
                            </p>
                        </div>
                    </div>
                    <div class="card bg-light mb-5">
                    <div class="card-header text-center">
                            <h2>Ajankäyttö</h2>
                        </div>
                        <div class="card-body mx-auto">
                            <h4>Ryhmän työnjaon suunnittelu</h4>
                            <p>Alustavasti suunnittelimme tehtävät seuraavasti (näiden lisäksi teimme myös paljon muutakin, jota emme ajatelleet alussa tekemämme):</p>
                            <p><span class="font-weight-bold">Lassi:</span> Laravelin perusmäärittelyt (routet, controllerit, kirjautumisominaisuudet), CSV->tietokanta ja tietokanta->CSV. Tietojen lisäyssivu.</p>
                            <p><span class="font-weight-bold">Tuukka:</span> Mysql tietokanta ja Laravelin migraatiotiedostot. Ajax mietintöjä.</p>
                            <p><span class="font-weight-bold">Tatu:</span> Bladeja ja bootstrappia. Salasanan generointi. Graafien luontia.</p><br>
                            <p>Yhteensä käytimme aikaa harkkatyöhön noin 250 tuntia. Tehtävät tarkentuivat ja muuttuivat, niin lopulliset sekä tarkat tehtävät ajankäyttöineen löytyy alta.</p>
                            <p>Linkki gitlabista löytyvään taulukkoon: <a href="https://gitlab.labranet.jamk.fi/webkurssit/tuotetietokanta-harjoitustyo-web-kurssit/-/blob/master/todo.md" target="_blank" class="font-weight-bold">Kaikki tehdyt tehtävät ja ajankäyttö</a></p>
                        </div>
                    </div>
                    <div class="card bg-light mb-5">
                    <div class="card-header text-center">
                            <h2>Itsearvio</h2>
                        </div>
                        <div class="card-body mx-auto">
                            <div class="mx-auto" style="max-width: 75%;">
                                <p>
                                Ryhmätyö meni ryhmän oman arvion mukaan erittäin hyvin. Ryhmädynamiikka toimi hyvin ja tehtävänjaon ansiosta pystyimme tekemään töitä suhteellisen itsenäisesti omilla saroillamme. Lisäksi hyvää ryhmähenkeä oli selvästi havaittavissa ja toimimmekin tosi hyvin tiimissä. Autoimme toinen toisiamme paljon ja näin jokainen edistyi omalla osa-alueellaan. Jotenkin oli koko ajan selkeää että mitä tehdään seuraavaksi (osittain myös sen takia, että olimme tiheään kontaktissa toimeksiantajaan). Kaiken kaikkiaan meni tosi hyvin, ja jotain keroo myös se, että toimeksiantaja kertoi jopa olevansa valmis suosittelemaan meitä kaikkia ansioluetteloissamme tämän työn perusteella! Kaavioista näkyy hyvin mitä saimme aikaan ja mitä jäi kesken tai puuttumaan, sekä mahdollisia kehityskohteita tulevaisuudessa.
                                </p>
                            </div>
                            <div class="card bg-light mb-5 mx-auto" style="max-width: 75%;">
                            <div class="card-header text-center">
                                    <h4>Alatalo, Tatu [N4927]</h4>
                                </div>
                                <div class="card-body">
                                    <h5>Web-palvelinohjelmointi</h5>
                                    <p>Päätimme käyttää tekemääni virtuaalipalvelinta johon olin jo asentanut Laravelin johon tarvitsi vain luoda uusi projekti. Alustavan tehtävän jaon mukaan minulle jäi näkymien ja bootstrapin tekeminen web-palvelinohjelmoinnin osalta. Teinkin kaikki näkymät paitsi /addnewitem.</p>
                                    <p>Todella uutena asiana oli bootstrapin käyttöönotto. Laravelin mukana tuli Bootstrap3 ja se piti päivittää versioon 4. Iso osa käytetystä ajasta meni näkymien ulkonäön panostamiseen sekä käyttöliittymän hiomiseen. Bootstrap ja varsinkin sen muotoilut tulikin todella tutuksi. Näkymien taulukoiden luonnissa on käytetty laravelin ehtolauseita mm. jos jokin tieto puuttuu tai esim. tietyn rivin taustavärin muuttaminen aktiivisuuden mukaan. Lisäksi tein Customer- ja DeviceControllerin, jotka käyttävät Tuukan tekemiä Eloquent-malleja. Tein myös loppuun Tuukan tekemän DeviceStoreRequest validaattorin ja loin loput validaattoritiedostot (CustomerStoreRequest.php ja UserStoreRequest.php, löytyy app/Http/Requests/).</p>
                                    <h5 class="mt-4">Web-ohjelmointi</h5>
                                    <p>Keskityinkin sitten web-ohjelmoinnin osalta käyttöliittymän tekemiseen sekä asiakkaan muokkaamiseen käytettävään lomakkeeseen. Kyseinen lomake käyttää jQueryn ajaxia datan käsittelyyn. Muutenkin kaikki scriptit mitä tein (suurimmaksi osaksi jQueryä), löytyy scriptit.js tiedostosta. Se sisältää myös salasanan luonti-funktion joka on toimeksiantajan vaatimusten mukainen (pituus 8 merkkiä, 1 numero, 1 pieni kirjain, 1 iso kirjain sekä erikoismerkki). Tätä pääsee kokeilemaan laitteenmuokkaus-sivulla (klikkaa asiakkaan laitetteen device-token -linkkiä). Löysin myös jQueryn lisäosan datatablen, joka muuttaa taulukot hyvinkin kätevään ja käytettävään muotoon. Lisäsi hirveästi arvoa sivulle.</p>
                                    <p>Vaikka en tosin tehnytkään react-komponentteja, pystytin react-ympäristön projektiin asentamalla react-paketin muita varten. Sekä neuvoin sen käytössä.</p>
                                    <h5 class="mt-4">Yhteenveto</h5>
                                    <p>Arvosanaehdotukseni web-palvelinohjelmointiin on <span class="font-weight-bold">5</span> ja web-ohjelmointiin <span class="font-weight-bold">4.5</span>.</p>
                                    <p>Omalta osalta olen tyytyväinen lopputulokseen. Harmittavana takaiskuna oli tämän viikon (vko33) aikataulu, koska poikani sairastui enkä pystynyt käyttämään niin paljon aikaa mitä aioin (esim. laitteen tietojen muokkaus jäi kesken). Yhteensä projektiin sain kuitenkin käytettyä 71 tuntia ja opin mielestäni paljon uutta mm. Bootstrap tuli todella tutuksi, joka on mielestäni olennainen osa molempia opintojaksoja. Lisäksi varsinkin laravelin backend-puoli (ajax) oli todella mielenkiintoinen ja kätevän oloinen. Lisäksi huolehdin siitä, että sivustoa pystyy sujuvasti käyttämään mobiililla jota testailin usein sekä chromen testityökaluilla, että omalla puhelimella. Omasta mielestäni toimiva käyttöliittymä tuo paljon arvoa itse projektille. Isona osana projektia myös oli tiimityö jossa autoimme toisia ongelmissa mm. Tuukan kanssa backend ja Lassin kanssa Addnewitem-komponentti.</p>
                                </div>
                            </div>
                            <div class="card bg-light mb-5 mx-auto" style="max-width: 75%;">
                            <div class="card-header text-center">
                                    <h4>Bordi, Tuukka [M2296]</h4>
                                </div>
                                <div class="card-body mx-auto">
                                    <h5>Web-palvelinohjelmointi</h5>

<p>Tätä osiota tein kyllä ihan järkyttävän paljon, varmaan 60 tuntia kokonaisuudessaan, mutta opittuakin tuli paljon. Vastuunani oli käytännössä kaikki tietokantaan liittyvä, paitsi CSV-toiminnoissa (joskin sielläkin käytettiin hyväksi tekemiäni Eloquent malleja). Toteutin backendin Ajaxille DatabaseControllerin muodossa. Lisäksi DeviceController ja CustomerController, joita Tatu työsti, käyttävät Eloquent-malleja.  Loin migraatiotiedostot ja tein käsin sql-populointiskriptin. Tein myös DeviceStoreRequest validaattorin lähes valmiiksi, ennen kuin annoin työn Tatulle kun piti päästä Reactin kimppuun.
<br><br>
Olin vahvasti se kaveri, jolta kysyttiin neuvoja, kun asia liittyi tietojen hakuun, luontiin tai muokkaukseen tietokannasta. Toki pitää sanoa, että olimme siitä hyvä ryhmä, että autoimme toisiamme paljon ja varmasti minäkin olen saanut apua sen ties missä, myös backend-jutuissa. Antaisin itselleni tästä arvosanan <b>5</b>, koska sain todella paljon aikaan ja siitä oli paljon hyötyä koko projektin näkökulmasta.</p>

<h5>Web-ohjelmointi</h5>

<p>Webohjelmointia tein vähän vähemmän kuin palvelinohjelmointia, ehkä noin 25 tuntia puhdasta React-ohjelmointia. Toki lisäisin tämän kurssin hyväksi puolet tekemästäni suunnittelu- ja Ajax-backendin toteutuksesta, joiden kanssa tuntimäärä asettuu lähelle neljääkymmentä ja siten "puhdas" web-palvelinohjelmointi olisi tuntimääränä n. 50 tuntia. 
<br><br>
Ajatukseni oli tehdä React muokkaus- ja lisäyslomake joka toimisi täydessä synkassa backendin kanssa siten, että se hakee backendistä muokattavan taulun tiedot sekä foreign keyt ja luo dynaamisesti lomakkeen näitten tietojen pohjalta (kokeile esim <a href="http://164.90.189.40/harjoitustyo/public/fields?db=customers">Linkki</a> esimerkkinä). Näin tämä React lomake olisi ollut universaali. Se olisi ottanut parametreina vain tietokannan nimen ja mahdolliset kenttien oletusarvot, jos tarkoitus oli muokata olemassaolevaa tietuetta. Pääsin tässä pitkälle ja lomake renderöityykin valinnan perusteella, mutta ongelmaksi syntyi se, että valinnan muuttuessa lomake ei päivittynyt. Lopulta löysin vian (itse asiassa tänään 14.8) mutta aika ei enää riitä muutoksien tekemiseen. Mutta koska tämän työn toimeksiantaja on myös työnantajani, saatan päästä tekemään tätä tulevaisuudessa!
<br><br>
Antaisin itselleni web-ohjelmonnista arvosanan väliltä <b>4,5</b>. Perusteluni tälle on se, että tein backendin, joka mahdollisti Ajax-toiminnot ja autoin vahvasti Lassia hänen oman React-lomakkeensa teossa (käytännössä debuggailtiin yhdessä se, miten JQueryn saa ympättyä Reactin sisälle ja muutamia muita juttuja). Tässäkin on pakko sanoa, että teimme monia asioita ryhmässä. Autoin Tatuakin JQueryn kanssa ja Tatu jelppasi minua validaattorien toteutuksessa. Muuten, tämä kesken oleva React-lomake löytyy täältä: <a href="http://164.90.189.40/harjoitustyo/public/add_any_item">Linkki</a>. Eli tässä tosiaan on se bugi, ettei vaihtoehdon valinnan myötä tapahdu muutosta renderöidyssä lomakkeessa. Bugin voi kiertää lataamalla sivun uudelleen ja valitsemalla uudestaan sen taulun, minkä lomnakkeen haluaa aukaista.</p>
                                </div>
                            </div>
                            <div class="card bg-light mb-5 mx-auto" style="max-width: 75%;">
                                <div class="card-header text-center">
                                    <h4>Halkosaari, Lassi [L3779]</h4>
                                </div>
                                <div class="card-body mx-auto">
                                    <h5>Web-palvelinohjelmointi</h5>
                                    <p>Web-palvelinohjelmoinnin puolelta sain Laravelilla tehtyä yhden suuren ja monimutkaisen CsvController.php tiedoston ja sain sen toimimaan toimeksiantajan vaatimusten mukaisesti, se aiheutti paljolti ongelmia. Lisäksi vaatimukset vaihteli alussa hieman ja sitä piti sitten muokata vielä oletettua enemmän. Käytin myös harjoitustyön loppupuolella aikaa erilaisten ongelmien ratkaisemiseksi. Omaksi arvosanaksi web-palvelinohjelmoinnin harjoitustyöstä arvioisin <b>5</b>, sillä opin todella paljon uutta ja sain hyvin käytettyä vanhaa tietoa tehdessä PHP:lla hommia. Tekemättä jäi itselläni php:llä exportin eli tietokannan taulujen latauslomakkeen teko, koska aikaa meni huomattavasti enemmän importtauksen tekoon kuin olin suunnitellut.</p>
                                    <h5>Web-ohjelmointi</h5>
                                    <p>Tässä osassa harjoitustyötä käytin kaiken aikani luodakseni reactilla lomakkeet, joilla pystytään lisäämään uusia laitteita, käyttäjiä ja asiakkaita. Tässä sain hyvin hyväksikäytettyä sisällössä Tuukan tekemiä modeleita ja ulkonäössä Tatun käyttöönottamaa bootstrappia. Omaksi arvosanaksi web-ohjelmoinnin harjoitustyöstä arvioisin <b>5</b>, koska sain reactillä toimivan ja hyvännäköisen lomakesivun "/addnewitems" olen tähän todella tyytyväinen, mutta kehitettävänä tähän olisi vielä kaikkiin muihin tauluihin lisäykset ja dynaaminen toiminta, että voisi luoda esim. uuden asiakkaan samalla, kun lisää uutta käyttäjää. Autoin Tuukkaa samoissa ongelmissa mihin itse törmäsin, kun hän lähti tekemään myös melkein samanlaista sivua, että saisi tehtyä myös web-ohjelmoinnin kurssille työtä. </p>
                                    <h5>Yhteenveto</h5>
                                    <p>Käytin molempien kurssien aiheisiin aika sopivasti, noin 40h per kurssi eli yhteensä reippaasti yli 80 tuntia ja siihen nähden onnistuin omasta mielestä todella hyvin.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card bg-light mb-5">
                        <div class="card-header text-center">
                            <h2>Linkki</h2>
                        </div>
                        <div class="card-body mx-auto">
                        <a href="http://164.90.189.40/harjoitustyo_palautus/harjoitustyo_palautus.zip" target="_blank" class="font-weight-bold">Linkki kaiken edellisen sisältävään zip-pakettiin.</a>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</body>

</html>
