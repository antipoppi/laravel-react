$(document).ready(function(){

    // muutetaan tarvittavat taulukot datatableiksi
    $('#dashboardTable').DataTable( {
        "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]]
    } );
    $('#customerDevicesTable').DataTable();
    $('#customerUsersTable').DataTable();

    // customers sivun kortin välilehtien valinta ja näyttäminen
    $('#customerCard a').on('click', function (e) {
        e.preventDefault()
        $(this).tab('show')
    });

    // customer-info välilehden taustavärin muuttaminen aktiivisuuden mukaan
    $(document).on("click", "#customerActive", function(){
        var cardBody = $(this).closest('.card-body');
        // jos aktiivinen väri vihertävä muuten punertava
        if(this.checked) {
            cardBody.css('background', 'linear-gradient(45deg,rgb(2,170,176,0.1),rgb(0,205,172,0.4))');
        } else {
            cardBody.css('background', 'linear-gradient(45deg,rgb(238,156,167,0.5),rgb(255,221,225,0.2))');
        }
    });

    // laitteen-muokkaus formin taustavärin muuttaminen aktiivisuuden mukaan
    $(document).on("click", "#deviceActive", function(){
        var cardBody = $(this).closest('.card-body');
        // jos aktiivinen väri vihertävä muuten punertava
        if(this.checked) {
            cardBody.css('background', 'linear-gradient(45deg,rgb(2,170,176,0.1),rgb(0,205,172,0.4))');
        } else {
            cardBody.css('background', 'linear-gradient(45deg,rgb(238,156,167,0.5),rgb(255,221,225,0.2))');
        }
    });

    // näytä salasana
    // kun klikataan .eyeicon-luokan ikonia
    $(document).on("click", ".eyeicon", function(){
        if ($(this).hasClass('fa-eye')){
            // jos luokka sisältää ikonin silmät auki vaihdetaan se silmät kiinni olevaan
            // ja vaihdetaan viereisen input-kentän tyyppi tekstiksi
            $(this).removeClass('fa-eye').addClass('fa-eye-slash');
            $(this).closest('td').find('input').prop('type', 'text');
            $('#deviceAdminPwd').prop('type', 'text');
        }
        else if ($(this).hasClass('fa-eye-slash')){
            // jos luokka sisältää ikonin silmät kiinni vaihdetaan se silmät auki olevaan
            // ja vaihdetaan viereisen input-kentän tyyppi salasanaksi
            // toimii myös laitteen muokkauslomakkeessa
            $(this).removeClass('fa-eye-slash').addClass('fa-eye');
            $(this).closest('td').find('input').prop('type', 'password');
            $('#deviceAdminPwd').prop('type', 'password');
        }
    });

    // event listener jos customerInfoForm muuttuu
    $("#customerInfoForm :input").change(function() {
        $("#customerInfoForm").data("changed",true);
    });

    // Kun asiakkaan muokkauslomake lähetetään
    $(document).on("submit", "#customerInfoForm", function(event){
        event.preventDefault();
        // jos inputit eivät ole muuttuneet
        if (!$("#customerInfoForm").data("changed")) {
            $("#customerAlert").removeClass().html("").removeAttr("style");
            $("#customerAlert").addClass("alert alert-danger mt-2 mb-2").html("Customer update failed: Can not save same info");
        } else {
            // laitetaan input-kenttien arvot muuttujiin
            let csrf = $("input[type='hidden']").val();
            let id = $("#customerID").val();
            let token = $("#customerToken").val();
            let name = $("#customerName").val();
            let address = $("#customerAddress").val();
            let contactPers = $("#customerContactPers").val();
            let notes = $("#customerNotes").val();
            let active = null;
            if ($('#customerActive').prop('checked')) {
                active = 1;
            } else {
                active = 0;
            }
            // laitetaan muuttujat json-objektiksi
            let data = {db: "customers",id: id,customer_token: token,name: name,address: address,contact_person_name: contactPers,active: active,notes: notes};
            // määritetään ajax ja ajetaan se
            $.ajax({
                url: "http://164.90.189.40/harjoitustyo/public/update",
                type:'get',
                headers: {
                    'X-CSRF-TOKEN': csrf
                },
                data: data,
                dataType: 'json',
                success: function(response) {
                    // jos päivitys onnistuu, poistetaan customerAlertista tyylit ja sisältö (jos sellaisia oli ennestään)
                    // muokataan se onnistuneeksi ja ilmoitetaan siitä, fadeout 7s ja jos nimi vaihtui vaihdetaan NameHeaderin sisältö
                    $("#customerAlert").removeClass().html("").removeAttr("style");
                    $("#customerAlert").addClass("alert alert-success mt-2 mb-2").html("Customer updated succesfully!");
                    $("#customerAlert").fadeOut(7000);
                    $("#customerNameHeader").html(name);
                    //console.log(response);
                    $("#customerInfoForm").data("changed",false);
                },
                error: function(xhr) {
                    // Jos päivitys epäonnistuu, poistetaan customerAlertista tyylit ja sisältö (jos sellaisia oli ennestään)
                    // muokataan se epäonnistuneeksi ja ilmoitetaan siitä, fadeoutia ei ole. 
                    let err = xhr.responseJSON;
                    //console.log(err.message);
                    $("#customerAlert").removeClass().html("").removeAttr("style");
                    $("#customerAlert").addClass("alert alert-danger mt-2 mb-2").html("Customer update failed: <br>" + err.message);
                }
            });
        }  
    });

    // salasanan-luontipainikkeen event listener
    $('#genPwdBtn').click(function(){
        // luo salasanan ja asettaa sen oikeaan kenttään
        let pw = luoSalasana();
        $("#genPwdField").val(pw);
    })
});

// salasananluonti-funktio
function luoSalasana() {
    // alustetaan muuttujat
    // tässä on pituus kovakoodattu 8 merkkiin, vaikka tarkistuksessa on laitettu maks 128 (Jatkokehittelyä varten, esim slideri josta saa valita salasanan pituuden)
    let pituus = 8;
    // merkkeihin voi lisätä halutessaan lisää erikoismerkkejä
    let merkit = "abcdefghijklmnopqrstuvwxyzåäöABCDEFGHIJKLMNOPQRSTUVWXYZÅÄÖ0123456789!%?+_-=@#";
    let pw = "";
    let n = merkit.length;
    // salasanan tulee sisältää numero 0-9, pieni sekä iso kirjain ja erikoismerkki, pituus 8-128 merkkiä
    let check = /^(?=.*\d)(?=.*([a-z]|å|ä|ö))(?=.*([A-Z]|Å|Ä|Ö))(?=.*[^a-zåäöA-ZÅÄÖ0-9])(?!.*\s).{8,128}$/;

    for (let i = 0; i < pituus; ++i) {
        // Math.Random valitsee luvun väliltä 0-1 ja se kerrotaan n:llä
        // Math.Floor valitsee suurimman kokonaisluvun joka on vähemmän tai yhtä paljon kuin valittu luku
        // lisätään salasanaan merkki kyseisen kokonaisluvun kohdalta
        pw += merkit.charAt(Math.floor(Math.random() * n));
    }
    // jos salasana ei mene regexistä läpi, luodaan uusi salasana. Muuten palautetaan salasana.
    if (!pw.match(check)) {
        return luoSalasana();
    } else {
        return pw;
    }
}

