<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Înregistrare - Restaurant UBB</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="pagina-auth">

    <header style="justify-content: center;">
        <img src="https://cdn-icons-png.flaticon.com/512/3075/3075977.png" alt="Logo">
        <div>
            <h1>Creare Cont Nou</h1>
            <p>Alătură-te comunității noastre</p>
        </div>
    </header>

    <main class="auth-container" style="max-width: 800px;">

        <div style="margin-bottom: 20px;">
            <a href="index.php" class="btn" style="background-color: #95a5a6;"><i class="fa-solid fa-arrow-left"></i> Înapoi la Autentificare</a>
        </div>

        <section class="setari-cont">
            <form action="procesare_inregistrare.php" name="formular_inregistrare" method="POST">

                <h3 style="color: var(--culoare-primara); border-bottom: 2px solid #ecf0f1; padding-bottom: 10px; margin-bottom: 20px;">
                    <i class="fa-solid fa-asterisk" style="color: #e74c3c;"></i> Informații Obligatorii
                </h3>

                <div class="grup-rezervare">
                    <div class="input-iconita">
                        <label for="username">Nume utilizator (doar litere mici, cifre):</label>
                        <i class="fa-solid fa-user"></i>
                        <input type="text" id="username" name="username" required placeholder="ion123">
                        <span class="bulina" id="bulina_user"></span>
                    </div>

                    <div class="input-iconita">
                        <label for="porecla">Poreclă (doar litere mici, cifre):</label>
                        <i class="fa-solid fa-tag"></i>
                        <input type="text" id="porecla" name="porecla" required placeholder="miru">
                        <span class="bulina" id="bulina_porecla"></span>
                    </div>

                    <div class="input-iconita">
                        <label for="email_reg">Adresă E-mail:</label>
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" id="email_reg" name="mail_utilizator" required placeholder="ion@exemplu.ro">
                        <span class="bulina" id="bulina_email_reg"></span>
                    </div>

                    <div class="input-iconita">
                        <label for="parola_reg">Parolă nouă:</label>
                        <i class="fa-solid fa-key"></i>
                        <input type="password" id="parola_reg" name="parola_noua" maxlength="15" required placeholder="Ex: Parola!123">
                        <span class="bulina" id="bulina_parola_reg"></span>
                    </div>

                    <div class="input-iconita">
                        <label for="telefon">Telefon (+40) 777 777 777:</label>
                        <i class="fa-solid fa-phone"></i>
                        <input type="text" id="telefon" name="telefon" required placeholder="(+40) 7xx xxx xxx">
                        <span class="bulina" id="bulina_telefon"></span>
                    </div>

                    <div class="input-iconita">
                        <label for="format_data">Alege formatul datei:</label>
                        <i class="fa-solid fa-gear"></i>
                        <select id="format_data" name="format_data" style="margin-bottom: 10px; cursor: pointer;">
                            <option value="zz/ll/aaaa">zz/ll/aaaa (31/01/2006)</option>
                            <option value="ll/zz/aaaa">ll/zz/aaaa (01/31/2006)</option>
                            <option value="zz/ll/aa">zz/ll/aa (31/01/06)</option>
                        </select>
                    </div>

                    <div class="input-iconita">
                        <label for="data_nastere">Data nașterii:</label>
                        <i class="fa-solid fa-calendar"></i>
                        <input type="text" id="data_nastere" name="data_nastere" required placeholder="Introduceți data...">
                        <span class="bulina" id="bulina_data"></span>
                    </div>
                </div>

                <h3 style="color: var(--culoare-primara); border-bottom: 2px solid #ecf0f1; padding-bottom: 10px; margin-bottom: 20px; margin-top: 30px;">
                    <i class="fa-solid fa-clipboard-user" style="color: var(--culoare-secundara);"></i> Date Suplimentare
                </h3>

                <div class="grup-rezervare">
                    <div class="input-iconita">
                        <label for="varsta">Vârsta:</label>
                        <i class="fa-solid fa-calendar-days"></i>
                        <input type="number" id="varsta" name="varsta" min="18" max="120" value="18" step="1">
                    </div>

                    <div class="input-iconita">
                        <label for="oras">Oraș (Sugestii):</label>
                        <i class="fa-solid fa-city" ></i>
                        <input type="text" id="oras" name="oras" list="lista-orase" placeholder="Scrie sau alege...">
                        <datalist id="lista-orase">
                            <option value="București"></option>
                            <option value="Cluj-Napoca"></option>
                            <option value="Timișoara"></option>
                            <option value="Iași"></option>
                            <option value="Brașov"></option>
                        </datalist>
                    </div>
                </div>

                <div style="margin: 20px 0;">
                    <label style="font-weight: bold; color: var(--culoare-primara);">Genul:</label><br>
                    <label style="margin-right: 15px; cursor: pointer;"><input type="radio" name="gen" value="m"> Masculin</label>
                    <label style="margin-right: 15px; cursor: pointer;"><input type="radio" name="gen" value="f"> Feminin</label>
                    <label style="cursor: pointer;"><input type="radio" name="gen" value="a"> Altul</label>
                </div>

                <div class="input-iconita" style="margin-bottom: 20px;">
                    <label for="ocupatie">Ocupație / Observații:</label>
                    <i class="fa-solid fa-briefcase" style="top: 40px; bottom: auto;"></i>
                    <textarea id="ocupatie" name="ocupatie" rows="3" placeholder="Cu ce vă ocupați?"></textarea>
                </div>

                <div style="margin: 20px 0; padding: 15px; background-color: #f9fbfd; border-radius: 6px; border: 1px solid #e0e6ed;">
                    <input type="checkbox" id="termeni" name="termeni" value="acceptat" required style="transform: scale(1.2); margin-right: 8px; cursor: pointer;">
                    <label for="termeni" style="cursor: pointer; color: var(--culoare-primara);"><strong>Accept termenii și condițiile site-ului.</strong></label>
                </div>

                <div style="display: flex; gap: 15px; justify-content: flex-end; margin-top: 30px;">
                    <button type="reset" class="btn" style="background-color: #e74c3c;"><i class="fa-solid fa-trash-can"></i> Șterge tot</button>
                    <button type="submit" class="btn btn-primar"><i class="fa-solid fa-check"></i> Creează cont</button>
                </div>
            </form>
        </section>
    </main>

    <footer style="margin-top: auto;">
        <p>&copy; Laborator Programare Web - Vasile Miruna</p>
    </footer>
    <script src="script.js?v=2"></script>
</body>
</html>