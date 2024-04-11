Reddit Clone Project
--------------------

Introductie:
Dit is een eenvoudige kloon van Reddit, waarmee gebruikers posts kunnen maken en afbeeldingen kunnen uploaden.

Vereisten:
- PHP 7.4 of hoger
- MySQL 5.7 of hoger
- Apache webserver met mod_rewrite ingeschakeld

Installatie:
1. Plaats alle bestanden in de document root van je webserver (bijvoorbeeld `htdocs` in XAMPP).
2. Importeer `database.sql` in je MySQL-database om de benodigde tabellen aan te maken.
3. Configureer de databaseverbinding in `config.php` met de juiste gebruikersnaam, wachtwoord en databasenaam.

Gebruik:
- Bezoek `index.php` om de homepage te zien en posts te bekijken.
- Gebruik `register.php` om een nieuw account aan te maken.
- Met `login.php` kun je inloggen op je account.
- Via `create_post.php` kun je een nieuwe post maken met of zonder afbeelding.

Bestandsstructuur:
- `config.php`: Database configuratie.
- `create_post.php`: Formulier voor het maken van een nieuwe post.
- `uploads/`: Directory waar geüploade afbeeldingen worden opgeslagen.

Opmerkingen:
- Zorg ervoor dat de `uploads/` directory schrijfrechten heeft. Gebruik `chmod` om de permissies in te stellen als je op een Unix-achtig systeem bent.

Contact:
Voor vragen kun je me bereiken via andrew.siha@gmail.com
