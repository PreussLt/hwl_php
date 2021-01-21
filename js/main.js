function fahrzeugeClicked() {
    $("#text-area").load("src/getFahrzeugListe.php", // laden der Fahrzeugliste in den 'text-area' container
        function(responseText, textStatus, XMLHttpRequest) {
            $(".continue").on('click', function(event) { //click listener auf alle list elemente
                var id = $(this).attr('id');
                $("#text-area").load("src/getFahrzeugDetails.php?id=" + id); // laden der Fahrzeugdetails in den 'text-area' container
            });
        });
}

function loadFahrzeug(id) { // laden der Fahrzeugdetails in den 'text-area' container
    $("#text-area").load("src/getFahrzeugDetails.php?id=" + id);
}

function fahrzeugeDoClick() {
    fahrzeugeClicked();
}

function fahrzeugAnlegenClicked() {
    $("#text-area").load("src/fahrzeug_anlegen.php");
}

function overlayOn() {
    document.getElementById("overlay").style.display = "block"
}

function overlayOff() {
    document.getElementById("overlay").style.display = "none";
}

function abteilungSchliessen() {
    document.getElementById("abteilungDetail").style.display = "none";
}

function showAbteilungDetail(abt) {
    $("#userListItemContainer").load("src/getAbteilungUser.php?abt=" + abt);

    document.getElementById("abteilungDetail").style.display = "block";
}

function openTab(evt, tabName, id) { //öffnet Tab auf Profilseite
    $('#' + tabName).load("src/" + tabName + "List.php?id=" + id);
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}

function deleteReservierung(rid, mid) {
    if (confirm("Reservierung wirklich löschen?")) {
        dta = { res_id: rid, m_id: mid };
        var formdata = new FormData();
        formdata.append("res_id", rid);
        formdata.append("m_id", mid);
        $.ajax({
            type: "POST",
            url: "src/reservierungLoeschenRequestHandler.php",
            data: formdata,
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) {
                alert(response.message);
            }
        });
        location.reload();
    }

}

function showResDetails(res_id, m_id) {
    if (!(isNaN(res_id) || isNaN(m_id))) {
        alert("show res details");

    } else {
        alert("Übergabe fehler");
    }
}