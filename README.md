# CMS-PHP-PDO
# 
PHP CMS project, Log In, Sign Up, admin, moderator, user roles, add/edit/delete post, categories, language, files, menu, custom block, backup.sql, update website settings.

![banner](http://manowebas.lt/wp-content/themes/manowebas/assets/img/logo.png)
<b>Šiame projekte pritaikytos apsaugos yra šios:</b>
<ul>
<li>Content Security Policy (CSP): Kodo pradžioje yra CSP antraštė, kuri apriboja išorinių išteklių naudojimą. CSP padeda apsaugoti nuo kai kurių įterpimo atakų, tokių kaip Cross-Site Scripting (XSS).</li>

<li>Sesijos saugumas: secureSession() funkcija apsaugo sesiją naudodama saugesnius nustatymus, tokie kaip saugūs slapukai ir sesijos laiko atnaujinimas. Tai apsaugo nuo kai kurių sesijos grobimo atakų.</li>

<li>Įvesties validacija: validateInput() funkcija naudojama, kad pašalintų HTML žymas, tarpus ir kitus nereikalingus simbolius. Tai padeda apsaugoti nuo kai kurių įterpimo atakų.</li>

<li>CSRF žetonų naudojimas: CSRF žetonai naudojami, kad apsaugotų nuo CSRF atakų. generateCSRFToken() funkcija generuoja žetoną, o validateCSRFToken() tikrina, ar pateiktas žetonas yra teisingas.</li>

<li>Slaptažodžio saugumas: Slaptažodžiai yra saugomi naudojant password_hash() funkciją, kuri naudoja stiprų šifravimo algoritmą (ARGON2I). Tai apsaugo slaptažodžius nuo atakų, kurios bando atskleisti slaptažodžius iš duomenų bazės.</li>

<li>Duomenų bazės saugumas: Duomenų bazės užklausos yra paruoštos naudojant PDO paruoštas pareiškimus, kurie apsaugo nuo SQL injekcijos atakų.</li>
  </ul>
  <p>Projektas dar tęsiamas, numatoma pridėti daugiau funkcionalumų</p>
  
  /////// En /////////////////
  
  <ul>
<li>Content Security Policy (CSP): There is a CSP header at the beginning of the code that restricts the use of external resources. CSP helps protect against some injection attacks such as Cross-Site Scripting (XSS).</li>

<li>Session Security: The secureSession() function secures the session using more secure settings such as secure cookies and updating the session time. This prevents some session hijacking attacks.</li>

<li>Input Validation: The validateInput() function is used to remove HTML tags, spaces, and other unnecessary characters. This helps protect against some injection attacks.</li>

<li>Use of CSRF tokens: CSRF tokens are used to protect against CSRF attacks. The generateCSRFToken() function generates a token, and validateCSRFToken() checks that the provided token is valid.</li>

<li>Password security: Passwords are stored using the password_hash() function, which uses a strong encryption algorithm (ARGON2I). This protects passwords from attacks that attempt to reveal passwords from the database.</li>

<li>Database security: Database queries are prepared using PDO prepared statements that protect against SQL injection attacks.</li>
   </ul>
   <p>The project is still ongoing, it is planned to add more functionalities</p>
   <br>
   
Author's address http://www.manowebas.lt
 
Autoriaus adresas http://www.manowebas.lt


