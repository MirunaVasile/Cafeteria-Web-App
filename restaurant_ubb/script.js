// ==========================================
// BLOC VANILLA JAVASCRIPT
// ==========================================
document.addEventListener("DOMContentLoaded", () => {

    // EXERCIȚIUL 1: VALIDĂRI FORMULARE
    // ------------------------------------------
    function actualizeazaBulina(idBulina, esteValid, valoare) {
        const bulina = document.getElementById(idBulina);
        if (!bulina) return;

        if (valoare.length === 0) {
            bulina.className = "bulina";
        } else if (esteValid) {
            bulina.className = "bulina bulina-verde";
        } else {
            bulina.className = "bulina bulina-rosie";
        }
    }

    // 1.A. FORMULAR LOGIN
    const emailLoginInput = document.getElementById("email_id");
    const parolaLoginInput = document.getElementById("parola_id");

    if (emailLoginInput && parolaLoginInput) {
        emailLoginInput.addEventListener("input", function () {
            const val = this.value;
            const isValid = /^[a-zA-Z0-9\._@]+$/.test(val) &&
                (val.match(/@/g) || []).length === 1 &&
                (val.match(/\./g) || []).length >= 1;
            actualizeazaBulina("bulina_email", isValid, val);
        });

        parolaLoginInput.addEventListener("input", function () {
            const val = this.value;
            const isValid = /[A-Z]/.test(val) &&
                /[a-z]/.test(val) &&
                /[0-9]/.test(val) &&
                /!/.test(val);
            actualizeazaBulina("bulina_parola", isValid, val);
        });
    }

    // 1.B. FORMULAR ÎNREGISTRARE
    const formInregistrare = document.forms["formular_inregistrare"];

    if (formInregistrare) {
        const regexText = /^[a-z0-9]+$/;

        document.getElementById("username").addEventListener("input", function () {
            actualizeazaBulina("bulina_user", regexText.test(this.value), this.value);
        });

        document.getElementById("porecla").addEventListener("input", function () {
            actualizeazaBulina("bulina_porecla", regexText.test(this.value), this.value);
        });

        document.getElementById("email_reg").addEventListener("input", function () {
            const val = this.value;
            const isValid = /^[a-zA-Z0-9\._@]+$/.test(val) &&
                (val.match(/@/g) || []).length === 1 &&
                (val.match(/\./g) || []).length >= 1;
            actualizeazaBulina("bulina_email_reg", isValid, val);
        });

        document.getElementById("parola_reg").addEventListener("input", function () {
            const val = this.value;
            const isValid = /[A-Z]/.test(val) &&
                /[a-z]/.test(val) &&
                /[0-9]/.test(val) &&
                /!/.test(val);
            actualizeazaBulina("bulina_parola_reg", isValid, val);
        });

        document.getElementById("telefon").addEventListener("input", function () {
            const regexTelefon = /^\(\+\d+\)\s\d{3}\s\d{3}\s\d{3}$/;
            actualizeazaBulina("bulina_telefon", regexTelefon.test(this.value), this.value);
        });

        function valideazaData(dataStr, format) {
            const separator = '/';
            if (!dataStr.includes(separator)) return false;
            const partiData = dataStr.split(separator);
            const partiFormat = format.split(separator);
            if (partiData.length !== 3 || partiFormat.length !== 3) return false;
            let zi, luna, an;
            for (let i = 0; i < 3; i++) {
                if (partiData[i].length !== partiFormat[i].length) return false;
                let valoareNumerica = parseInt(partiData[i], 10);
                if (isNaN(valoareNumerica)) return false;
                if (partiFormat[i].toLowerCase() === 'zz') zi = valoareNumerica;
                if (partiFormat[i].toLowerCase() === 'll') luna = valoareNumerica;
                if (partiFormat[i].toLowerCase() === 'aaaa' || partiFormat[i].toLowerCase() === 'aa') {
                    an = valoareNumerica;
                    if (partiFormat[i].toLowerCase() === 'aa' && an < 100) {
                        an += 2000;
                    }
                }
            }
            if (luna < 1 || luna > 12) return false;
            const zileInLuna = new Date(an, luna, 0).getDate();
            if (zi < 1 || zi > zileInLuna) return false;
            return true;
        }

        const inputData = document.getElementById("data_nastere");
        const selectFormat = document.getElementById("format_data");

        if (inputData && selectFormat) {
            function executaValidareData() {
                const formatAles = selectFormat.value;
                const dataIntrodusa = inputData.value;
                const esteValida = valideazaData(dataIntrodusa, formatAles);
                actualizeazaBulina("bulina_data", esteValida, dataIntrodusa);
            }
            inputData.addEventListener("input", executaValidareData);
            selectFormat.addEventListener("change", executaValidareData);
        }

        // CORECTAT: Logica de submit pentru a permite procesarea PHP
        formInregistrare.addEventListener("submit", function (event) {
            const bulineRosii = formInregistrare.querySelectorAll(".bulina-rosie");
            
            if (bulineRosii.length > 0) {
                // Dacă sunt erori, oprim trimiterea
                event.preventDefault(); 
                alert("Te rugăm să corectezi câmpurile marcate cu roșu înainte de a trimite!");
            } 
            // Dacă nu sunt erori, lăsăm browserul să trimită datele natural către procesare_inregistrare.php
        });
    }

    // EXERCIȚIUL 3: Sortare Tabel
    // ------------------------------------------
    const tabeleSortabile = document.querySelectorAll(".tabel-sortabil");
    tabeleSortabile.forEach(tabel => {
        const anteturi = tabel.querySelectorAll("thead th");
        const tbody = tabel.querySelector("tbody");

        if (!tbody || anteturi.length === 0) return;

        function extrageTextCurat(celula) {
            if (!celula) return "";
            const clona = celula.cloneNode(true);
            const tooltip = clona.querySelector(".info-extra");
            if (tooltip) tooltip.remove();
            return clona.textContent.trim().toLowerCase();
        }

        anteturi.forEach((th, indexColoana) => {
            if (th.classList.contains("fara-sortare")) return;
            th.style.cursor = "pointer";
            th.dataset.directie = 1;

            th.addEventListener("click", () => {
                const directieCurenta = parseInt(th.dataset.directie);
                const randuri = Array.from(tbody.querySelectorAll("tr"));

                randuri.sort((randA, randB) => {
                    const celulaA = randA.querySelectorAll("td")[indexColoana];
                    const celulaB = randB.querySelectorAll("td")[indexColoana];
                    const textA = extrageTextCurat(celulaA);
                    const textB = extrageTextCurat(celulaB);

                    const numA = parseFloat(textA.replace(/[^0-9.-]+/g, ""));
                    const numB = parseFloat(textB.replace(/[^0-9.-]+/g, ""));

                    if (!isNaN(numA) && !isNaN(numB)) {
                        return (numA - numB) * directieCurenta;
                    }
                    if (textA < textB) return -1 * directieCurenta;
                    if (textA > textB) return 1 * directieCurenta;
                    return 0;
                });

                randuri.forEach(rand => tbody.appendChild(rand));
                th.dataset.directie = directieCurenta * -1;
            });
        });
    });

    // EXERCIȚIUL 4: Listă animată (Text Slider)
    // ------------------------------------------
    const listaAnunturi = document.getElementById("lista-anunturi");
    if (listaAnunturi) {
        const elementeLista = listaAnunturi.querySelectorAll("li");
        const btnPrev = document.getElementById("btn-prev-text");
        const btnNext = document.getElementById("btn-next-text");
        let indexCurent = 0;
        const intervalTimp = 4000;
        let timerAnunturi;

        function afiseazaElement(index) {
            elementeLista.forEach(li => li.classList.remove("activ"));
            elementeLista[index].classList.add("activ");
        }

        function slideUrmator() {
            indexCurent++;
            if (indexCurent >= elementeLista.length) {
                indexCurent = 0;
            }
            afiseazaElement(indexCurent);
        }

        function slideAnterior() {
            indexCurent--;
            if (indexCurent < 0) {
                indexCurent = elementeLista.length - 1;
            }
            afiseazaElement(indexCurent);
        }

        function pornesteTimer() {
            timerAnunturi = setInterval(slideUrmator, intervalTimp);
        }

        function reseteazaTimer() {
            clearInterval(timerAnunturi);
            pornesteTimer();
        }

        btnNext.addEventListener("click", function () {
            slideUrmator();
            reseteazaTimer();
        });

        btnPrev.addEventListener("click", function () {
            slideAnterior();
            reseteazaTimer();
        });

        pornesteTimer();
    }

    // EXERCIȚIUL 5: Controale Slider Imagini
    // ------------------------------------------
    const galerieWrapper = document.querySelector(".galerie-wrapper");
    if (galerieWrapper) {
        const imaginiMari = document.querySelectorAll(".img-ascunsa-mare");
        const btnPlayPause = document.getElementById("btn-play-pause");
        const checkRepetare = document.getElementById("check-repetare");
        const selectInterval = document.getElementById("select-interval");
        let indexImagine = -1;
        let esteInPlay = false;
        let timerSlider;

        function ascundeToateImaginile() {
            imaginiMari.forEach(img => img.classList.remove("activata-js"));
        }

        function arataImaginea(index) {
            ascundeToateImaginile();
            if (index >= 0 && index < imaginiMari.length) {
                imaginiMari[index].classList.add("activata-js");
            }
        }

        function slideUrmatorAuto() {
            indexImagine++;
            if (indexImagine >= imaginiMari.length) {
                if (checkRepetare.checked) {
                    indexImagine = 0;
                } else {
                    opresteSlider();
                    indexImagine = -1;
                    ascundeToateImaginile();
                    return;
                }
            }
            arataImaginea(indexImagine);
        }

        function pornesteSlider() {
            esteInPlay = true;
            btnPlayPause.innerHTML = '<i class="fa-solid fa-pause"></i> Pauză';
            btnPlayPause.classList.replace("btn-primar", "btn-secundar");
            const milisecunde = parseInt(selectInterval.value);
            if (indexImagine === -1) {
                indexImagine = 0;
                arataImaginea(indexImagine);
            }
            timerSlider = setInterval(slideUrmatorAuto, milisecunde);
        }

        function opresteSlider() {
            esteInPlay = false;
            btnPlayPause.innerHTML = '<i class="fa-solid fa-play"></i> Play';
            btnPlayPause.classList.replace("btn-secundar", "btn-primar");
            clearInterval(timerSlider);
        }

        btnPlayPause.addEventListener("click", function () {
            if (esteInPlay) {
                opresteSlider();
            } else {
                pornesteSlider();
            }
        });

        selectInterval.addEventListener("change", function () {
            if (esteInPlay) {
                opresteSlider();
                pornesteSlider();
            }
        });
    }

    // EXERCIȚIUL 6: Acceptare / Respingere Rezervări
    // ------------------------------------------
    const tabelRezervari = document.querySelector(".tabel-stilizat tbody");
    if (tabelRezervari) {
        tabelRezervari.addEventListener("click", function (event) {
            const buton = event.target;
            if (buton.tagName === "BUTTON") {
                const textButon = buton.textContent.trim();
                const randTabel = buton.closest("tr");

                if (!randTabel) return;

                const containerStatus = randTabel.querySelector("td[data-label='Status']");
                const containerActiuni = randTabel.querySelector(".actiuni-flex");

                if (textButon === "Acceptă") {
                    randTabel.classList.add("rand-acceptat");
                    randTabel.classList.remove("rand-respins");
                    if (containerStatus) {
                        containerStatus.innerHTML = '<span class="status-tag confirmat">Acceptată</span>';
                    }
                    if (containerActiuni) {
                        containerActiuni.innerHTML = "";
                    }
                }
                else if (textButon === "Respinge") {
                    randTabel.classList.add("rand-respins");
                    randTabel.classList.remove("rand-acceptat");
                    if (containerStatus) {
                        containerStatus.innerHTML = '<span class="status-tag respins">Respinsă</span>';
                    }
                    if (containerActiuni) {
                        containerActiuni.innerHTML = "";
                    }
                }
            }
        });
    }
}); 


// ==========================================
// JQUERY (LABORATORUL 4)
// ==========================================
$(document).ready(function () {

    // EXERCIȚIUL 1: Slider Vertical 
    // -----------------------------------------------------------------
    if ($('.modul-slider').length) {
        const inaltimeCard = 300;
        const gapCard = 30;
        const miscareTotala = inaltimeCard + gapCard;

        let intervalSlider;
        let isAnimating = false;

        $('.lista-3d .card-3d:first').addClass('activ-3d');

        function actualizeazaInaltime() {
            let nrImagini = parseInt($('#setare-imagini').val()) || 1;
            let inaltimeCalculata = (inaltimeCard * nrImagini) + (gapCard * (nrImagini - 1));
            $('.viewport').css('height', (inaltimeCalculata + 20) + 'px');
        }

        function slideSus() {
            if (isAnimating) return;
            isAnimating = true;

            let $lista = $('.lista-3d');
            let $primul = $lista.children('.card-3d').first();
            let $urmatorul = $primul.next();

            $primul.removeClass('activ-3d');
            $urmatorul.addClass('activ-3d');

            $lista.animate({
                marginTop: -miscareTotala
            }, 600, function () {
                $primul.appendTo($lista);
                $lista.css('marginTop', 0);
                isAnimating = false;
            });
        }

        function slideJos() {
            if (isAnimating) return;
            isAnimating = true;

            let $lista = $('.lista-3d');
            let $primul = $lista.children('.card-3d').first();
            let $ultimul = $lista.children('.card-3d').last();

            $ultimul.prependTo($lista);
            $lista.css('marginTop', -miscareTotala);

            $primul.removeClass('activ-3d');
            $ultimul.addClass('activ-3d');

            $lista.animate({
                marginTop: 0
            }, 600, function () {
                isAnimating = false;
            });
        }

        function pornesteAutomat() {
            clearInterval(intervalSlider);
            let secunde = parseInt($('#setare-secunde').val()) || 3;
            intervalSlider = setInterval(slideSus, secunde * 1000);
        }

        actualizeazaInaltime();
        pornesteAutomat();

        $('#setare-imagini').on('input change', actualizeazaInaltime);
        $('#setare-secunde').on('input change', pornesteAutomat);

        $('.slider-wrapper').hover(
            function () {
                $('.sageata').fadeIn(300);
                clearInterval(intervalSlider);
            },
            function () {
                $('.sageata').fadeOut(300);
                pornesteAutomat();
            }
        );

        $('#btn-jos').on('click', slideSus);
        $('#btn-sus').on('click', slideJos);
    }


    // EXERCIȚIUL 2. C): Indicator Rotund de Progres
    // -----------------------------------------------------------------
    function updateProgressBar(puncteCurente) {
        const limitaMaxima = 100;

        if (puncteCurente > limitaMaxima) puncteCurente = limitaMaxima;
        if (puncteCurente < 0) puncteCurente = 0;

        let puncteRamase = limitaMaxima - puncteCurente;
        $('#puncte-ramase').text(puncteRamase);

        if (puncteRamase === 0) {
            $('#mesaj-reducere').html('<i class="fa-solid fa-gift"></i> Ai deblocat reducerea!')
                .css('background-color', '#2ecc71');
            $('.indicator-progres').css('stroke', '#2ecc71');
        } else {
            $('#mesaj-reducere').html('Încă <span id="puncte-ramase">' + puncteRamase + '</span> puncte pentru reducere')
                .css('background-color', '#e67e22');
            $('.indicator-progres').css('stroke', 'var(--culoare-secundara)');
        }

        let valoareCurenta = parseInt($('#puncte-curente').text()) || 0;

        $({ contor: valoareCurenta }).animate({ contor: puncteCurente }, {
            duration: 600,
            step: function (now) {
                $('#puncte-curente').text(Math.round(now));
            },
            complete: function () {
                $('#puncte-curente').text(puncteCurente);
            }
        });

        const circumferinta = 125.66;
        let procent = puncteCurente / limitaMaxima;
        let offsetFinal = circumferinta - (procent * circumferinta);

        if ($('.indicator-progres').length) {
            $('.indicator-progres')[0].style.strokeDashoffset = offsetFinal;
        }
    }

    if ($('.sectiune-indicator').length) {
        const selectorWidgetPuncte = '.widget-card:nth-child(2) .valoare';
        let puncteStart = parseInt($(selectorWidgetPuncte).text()) || 0;
        $('#puncte-curente').text(puncteStart);

        setTimeout(function () {
            updateProgressBar(puncteStart);
        }, 100);

        $('#adauga-puncte').off('click').on('click', function () {
            let puncteAcum = parseInt($(selectorWidgetPuncte).text()) || 0;
            if (puncteAcum < 100) {
                puncteAcum += 10;
                if (puncteAcum > 100) puncteAcum = 100;
                $(selectorWidgetPuncte).text(puncteAcum);
                updateProgressBar(puncteAcum);
            }
        });

        $('#scade-puncte').off('click').on('click', function () {
            let puncteAcum = parseInt($(selectorWidgetPuncte).text()) || 0;
            if (puncteAcum > 0) {
                puncteAcum -= 10;
                if (puncteAcum < 0) puncteAcum = 0;
                $(selectorWidgetPuncte).text(puncteAcum);
                updateProgressBar(puncteAcum);
            }
        });
    }


    // EXERCIȚIUL 2: DROPDOWN-URI DINAMICE
    // -----------------------------------------------------------------
    const selectJudet = document.getElementById("judet-select");
    const selectOras = document.getElementById("oras-select");

    if (selectJudet && selectOras) {
        const dateLocatii = {
            "Alba": ["Alba Iulia", "Sebeș", "Aiud"],
            "București": ["Sector 1", "Sector 2", "Sector 3"],
            "Cluj": ["Cluj-Napoca", "Turda", "Dej"],
            "Iași": ["Iași", "Pașcani", "Hârlău"],
            "Timiș": ["Timișoara", "Lugoj", "Jimbolia"]
        };

        $(selectJudet).empty().append('<option value="">-- Alege Județ --</option>');
        $(selectOras).empty().append('<option value="">-- Alege Județ întâi --</option>').prop('disabled', true);

        for (let judet in dateLocatii) {
            $(selectJudet).append($('<option>', { value: judet, text: judet }));
        }

        $(selectJudet).on('change', function () {
            const judetSelectat = $(this).val();
            $(selectOras).empty();
            if (!judetSelectat) {
                $(selectOras).append('<option value="">-- Alege Județ întâi --</option>').prop('disabled', true);
            } else {
                $(selectOras).prop('disabled', false).append('<option value="">-- Alege Oraș --</option>');
                if (dateLocatii[judetSelectat]) {
                    dateLocatii[judetSelectat].forEach(oras => {
                        $(selectOras).append($('<option>', { value: oras, text: oras }));
                    });
                }
            }
        });
    }

    // EXERCIȚIUL 3: WIDGET FEEDBACK
    // -----------------------------------------------------------------
    if ($('#widget-feedback').length) {
        let ratingCurent = 0;
        let istoricRecenzii = []; 

        $('#buton-feedback-toggle').on('click', function () {
            $('#panou-feedback').slideToggle(300);
        });

        $('.stea-feedback').on('mouseenter', function () {
            let valoareHover = $(this).data('valoare');
            $('.stea-feedback').each(function () {
                if ($(this).data('valoare') <= valoareHover) $(this).addClass('activa');
                else $(this).removeClass('activa');
            });
        }).on('mouseleave', function () {
            $('.stea-feedback').each(function () {
                if ($(this).data('valoare') <= ratingCurent) $(this).addClass('activa');
                else $(this).removeClass('activa');
            });
        });

        $('.stea-feedback').on('click', function () {
            ratingCurent = $(this).data('valoare');
            $('#zona-text-feedback').slideDown(400);
        });

        $('#trimite-feedback').on('click', function () {
    if (ratingCurent === 0) return; 

    let $buton = $(this);
    let comentariu = $('#text-feedback').val().trim();

    $buton.html('<i class="fa-solid fa-spinner fa-spin"></i> Se trimite...').prop('disabled', true);

    // TRIMITEM DATELE CĂTRE SERVER
    $.ajax({
        url: 'procesare_recenzie.php',
        method: 'POST',
        data: {
            nota: ratingCurent,
            comentariu: comentariu
        },
        success: function(response) {
            let res = JSON.parse(response);
            if(res.status === "success") {
                // Afișăm mesajul de succes
                $('#zona-text-feedback, .stele-container').slideUp(300);
                $('#mesaj-succes-feedback').fadeIn(400);

                // Adăugăm vizual recenzia în listă fără refresh
                let stele = '';
                for (let i = 1; i <= 5; i++) {
                    stele += (i <= ratingCurent) ? '<i class="fa-solid fa-star"></i>' : '<i class="fa-regular fa-star"></i>';
                }
                let htmlNou = `<div class="comentariu-item"><strong>Nota ${ratingCurent}/5</strong><p>${comentariu || '<em>Fără comentariu</em>'}</p></div>`;
                $(htmlNou).prependTo('#lista-comentarii').hide().slideDown(400);

                // Resetăm widget-ul după 2 secunde
                setTimeout(function () {
                    $('#panou-feedback').slideUp(400, function() {
                        location.reload(); // Refresh pentru a actualiza media de sus
                    });
                }, 2000);
            }
        }
    });
});
    }
}); // Sfârșit script corect