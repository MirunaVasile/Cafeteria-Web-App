<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rezervare Nouă - Restaurant UBB</title>

    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <img src="https://cdn-icons-png.flaticon.com/512/3075/3075977.png" alt="Logo Restaurant UBB">
        <div>
            <h1>Rezervări Online</h1>
            <p>Procesul de rezervare pentru Restaurantul UBB</p>
        </div>
    </header>

    <div class="layout-principal">

        <nav>
        <h2>Navigare Admin</h2>
        <ul>
            <li><a href="home_admin.html"><strong>Acasă Admin</strong></a></li>
            <li><a href="rezervare_noua_admin.html">Faceți o rezervare</a></li>
            <li><a href="vizualizare_rezervari_admin.html">Vizualizare Rezervări</a></li>
            <li><a href="index.html">Ieșire din sistem</a></li>
        </ul>
        </nav>

        <main>
            <form action="vizualizare_rezervari_admin.html" method="get">

                <section>
                    <h2>Pasul 1: Alegeți data, ora și numărul de persoane </h2>

                    <fieldset>
                        <legend>1. Detalii Programare</legend>

                        <div class="grup-rezervare">
                            <div class="input-iconita">
                                <label for="data_id">Data Rezervării:</label>
                                <i class="fa-regular fa-calendar-days"></i>
                                <input type="date" name="data_rezervare" id="data_id" required>
                            </div>

                            <div class="input-iconita">
                                <label for="ora_id">Ora Sosirii:</label>
                                <i class="fa-regular fa-clock"></i>
                                <input type="time" name="ora_rezervare" id="ora_id" value="19:00" required>
                            </div>

                            <div class="input-iconita">
                                <label for="pers_id">Număr Persoane:</label>
                                <i class="fa-solid fa-users"></i>
                                <input type="number" name="numar_persoane" id="pers_id" min="1" value="4" required>
                            </div>

                        </div>

                        <div style="margin-top: 25px;">
                            <label for="obs_id" style="font-weight: bold; color: var(--culoare-primara); display: block; margin-bottom: 5px;">Observații Speciale:</label>
                            <textarea name="observatii" id="obs_id" rows="2" style="width: 100%; padding: 10px; border: 2px solid #e0e6ed; border-radius: var(--border-radius);"></textarea>
                        </div>
                    </fieldset>
                </section>

                <section>
                    <h2>Pasul 2: Alegeți Masa</h2>

                    <fieldset>
                        <legend>2. Harta Interactivă a Meselor</legend>
                        <p style="margin-bottom: 20px;"><i class="fa-solid fa-hand-pointer"></i> Faceți click pe masa dorită. Mesele ocupate sunt marcate cu roșu.</p>

                        <div class="harta-restaurant">

                            <label class="masa-wrapper masa-libera">
                                <input type="checkbox" name="masa1" value="M1">
                                <img src="icons/masa-22.png" alt="Masa 2 Persoane">
                                <span class="detalii-masa">Masa 1<br><small>Geam</small></span>
                                <div class="tooltip-capacitate"><i class="fa-solid fa-user-group"></i> 2 Persoane</div>
                            </label>

                            <div class="masa-wrapper masa-ocupata">
                                <img src="icons/masa-2.png" alt="Masa Ocupata">
                                <span class="detalii-masa">Masa 2<br><small style="color: #e74c3c;">Ocupat</small></span>
                            </div>

                            <label class="masa-wrapper masa-libera">
                                <input type="checkbox" name="masa3" value="M3">
                                <img src="icons/masa-4.png" alt="Masa 4 Persoane">
                                <span class="detalii-masa">Masa 3<br><small>Terasă</small></span>
                                <div class="tooltip-capacitate"><i class="fa-solid fa-users"></i> 4 Persoane</div>
                            </label>

                            <label class="masa-wrapper masa-libera">
                                <input type="checkbox" name="masa2" value="M2">
                                <img src="icons/masa-2.png" alt="Masa 2 Persoane">
                                <span class="detalii-masa">Masa 4<br><small>Geam</small></span>
                                <div class="tooltip-capacitate"><i class="fa-solid fa-user-group"></i> 2 Persoane</div>
                            </label>

                            <div class="masa-wrapper masa-ocupata">
                                <img src="icons/masa-6.png" alt="Masa Ocupata">
                                <span class="detalii-masa">Masa 5<br><small style="color: #e74c3c;">Ocupat</small></span>
                            </div>

                            <label class="masa-wrapper masa-libera">
                                <input type="checkbox" name="masa4" value="M4">
                                <img src="icons/masa-6.png" alt="Masa 6 Persoane">
                                <span class="detalii-masa">Masa 6<br><small>Terasă</small></span>
                                <div class="tooltip-capacitate"><i class="fa-solid fa-users"></i> 6 Persoane</div>
                            </label>

                        </div>
                    </fieldset>
                </section>

                <p style="text-align: right; margin-top: 20px;">
                    <button type="submit" style="font-size: 1.1rem; padding: 15px 30px;"><i class="fa-solid fa-check"></i> Finalizare și Confirmare</button>
                </p>
            </form>
        </main>

        <aside>
            <h3>Info Harta</h3>
            <div style="margin-top: 20px;">
                <p><span style="display:inline-block; width:15px; height:15px; background:#2ecc71; border-radius:50%;"></span> <b>Verde:</b> Liber (Selectați)</p>
                <p><span style="display:inline-block; width:15px; height:15px; background:#e74c3c; border-radius:50%;"></span> <b>Roșu:</b> Indisponibil</p>
            </div>
        </aside>

    </div>

    <footer>
        <p>&copy; Laborator Programare Web - Vasile Miruna</p>
    </footer>

</body>
</html>