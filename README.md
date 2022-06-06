# DT173G, Projektuppgift, Webbtjänst
Skapad av Emma Forslund, emfo2102@student.miun.se, 2022

Detta repository innehåller en REST-webbtjänst skapad till slutprojektet till kursen DT173G, Webbutveckling III. API:et är uppbyggt med PHP och hanterar bokningar, maträtter, drycker och inloggning. Detta repo innehåller även klasser, en installations-fil, en konfigurerings-fil och en PHP-fil där det går att registrera användare. Funktionalitet för CRUD(Create, Read, Update, Delete) är implementerad. Detta API används av två olika webbplatser, en webbplats som är ett administrationsgränssnitt och en webbplats som presenterar restaurangen Trattoria Romantico.

## Länkar
### Restaurangen Trattoria Romantico
* https://studenter.miun.se/~emfo2102/writeable/trattoriaromantico/index.html
### Administrationsgränssnitt
* https://studenter.miun.se/~emfo2102/writeable/projekt_admin/login.php

## Installation av databas
Klona detta repo och kör skriptet install.php. Då installeras samtliga tabeller som behövs till denna databas. När skriptet körs töms alla tabeller, bortsett från tabellen med kategorier, som har standard-inserts. 
### Tabeller
| Tabellnamn  | Fält |
| ------------- | ------------- |
| Drink  | **drink_id** INT(11) **drink_name** VARCHAR(128) **drink_description** TEXT **drink_price** VARCHAR(128) **drink_category_id** INT(11)  |
| Food  | **food_id** INT(11) **food_name** VARCHAR(128) **food_description** TEXT **food_price** VARCHAR(128) **food_category_id** INT(11) **food_type_id**|
| Booking | **booking_id** INT(11) **booking_date** VARCHAR(128) **booking_time** VARCHAR(128)  **guest_fname** varchar(128) **guest_ename** varchar(128) **guest_email** varchar(128) **guest_text** TEXT **quantity** INT(2) **created** timestamp NOT NULL DEFAULT current_timestamp() 
| Food_category |  **food_category_id** INT(11) **food_category_name** VARCHAR(128) |
| Drink_category |**drink_category_id** INT(11) **drink_category_name** VARCHAR(128) |
| Food_type | **food_type_id** INT(11) **food_type_name** VARCHAR(128) |
| User | **username** VARCHAR(128) **password** VARCHAR(128) |

## Klasser och metoder
### Klassen User
Denna klass hanterar inloggning och registrering av användare.
#### Properties
* $username - String - Användarens användarnamn
* $password - String -  Lösenord till användaren
* $db - MySQLi-anslutning

#### Methods
* Constructor - Konstruerare som automatiskt anropas när klassen instansieras. Innehåller databas-anslutning. 
* setUser(string username, string password) : bool - Set-metod som kontrollerar att inte username och password är tomma värden och sätter värdena till dom
* registerUser(string username, string password) - Kontrollerar set-metoder, Registrerar användare, hashar lösenord, sanerar input och lagrar användare i databasen
* logIn(string username, string password) : bool - Kontrollerar set-metoder, sanerar input, hämtar data från tabellen user med det angivna användarnamnet och jämför det inmatade lösenordet mot det hashade. Returnerar true om lösenordet stämmer. 
* Destructor - Destruerare som stänger databaskopplingen. 

### Klassen Booking
Denna klass hanterar bokningar.
#### Properties
* db - MySQLi-anslutning
* $booking_id - Int - Bokningars ID/nummer
* $booking_time - String - Bokningens utförande-tid
* $booking_date - String - Bokningens utförande-datum
* $guest_fname - String - Förnamnet för den som gör bokningen
* $guest_ename - String - Efternamnet för den som gör bokningen
* $guest_email - String - Mailadressen till den som gör bokningen
* $guest_email - String - Eventuella önskemål från kunder gällande deras bokningar
* $quantity - Int - Antal gäster till bokningen
#### Methods
* Constructor - Konstruerare som automatiskt anropas när klassen instansieras. Innehåller databas-anslutning. 
* Destructor - Destruerare som stänger databaskopplingen. 
* setBooking(string $booking_date, string $booking_time, string $guest_fname, string $guest_ename, string $guest_email, string $guest_text, int $quantity) :bool - Kontrollerar om samtliga förutom guest_text innehåller tomma värden sätter värdena till dom. Guest_text tillåts ha tomma värden eftersom det inte är krav.
* addBooking (string $booking_date, string $booking_time, string $guest_fname, string $guest_ename, string $guest_email, string $guest_text, int $quantity) - Kontrollerar set-metod, sanerar input, lägger in bokningen i databasen. 
* updateBooking (int $booking_id, string $booking_date, string $booking_time, string $guest_fname, string $guest_ename, string $guest_email, string $guest_text, int $quantity) : bool - Kontrollerar set-metod, sanerar input, sql-fråga med update som uppdaterar bokningen med det angivna ID:et($booking_id)
* getBookings() : array - Hämtar alla bokningar från tabellen booking och ordnar dom efter de nyaste bokningarna längst upp
* getBookingById(int $booking_id): array - Hämtar ut specifik bokning utifrån dess ID($booking_id)

### Klassen Drink
#### Properties
* db - MySQLi-anslutning
* $drink_id - Int - En drycks unika id/nummer
* $drink_name - String - Dryckens namn
* $drink_description - String - Dryckens beskrivning
* $drink_price - String - Dryckens pris
* $drink_category - Int - Vilken kategori drycken tillhör
#### Methods
* Constructor - Konstruerare som automatiskt anropas när klassen instansieras. Innehåller databas-anslutning. 
* Destructor - Destruerare som stänger databaskopplingen. 
* setDrink(string $drink_name, string $drink_description, string $drink_price, int $drink_category): bool - Set-metod som kontrollerar att inga tomma värden lagras och sätter värdena
* addDrink(string $drink_name, string $drink_description, string $drink_price, int $drink_category) - Kontrollerar set-metoder, sanerar input, sql-fråga med INSERT till tabellen drink
* updateDrink(int $drink_id, string $drink_name, string $drink_description, string $drink_price, int $drink_category): bool -  Kontrollerar set-metoder, sanerar input, sql-fråga med Update till tabellen drink. Uppdaterar drycken med det id($drink_id) som är angett
* getDrinks(): array - Hämtar alla drycker med SQL-fråga med GET till tabellen drink
* deleteDrink (int $drink_id): bool - Raderar dryck med SQL-fråga med DELETE och det id som den valda drycken har
* getDrinkById(int $drink_id): array - Hämtar en specifik dryck utifrån det id som den angiva drycken har

### Klassen Food
Denna klass hanterar maträtter
#### Properties
* db - MySQLi-anslutning
* $food_id - Int - En maträtts unika id/nummer
* $food_name - String - Maträttens namn
* $food_description - String - Maträttens beskrivning
* $food_price - String - Maträttens pris
* $food_category - Int - Vilken kategori maträtten tillhör exempelvis förrätt/varmrätt/efterrätt
* $food_type - Int - Vilken typ maträtten är exempelvis pizza/grillat/pasta
#### Methods
* Constructor - Konstruerare som automatiskt anropas när klassen instansieras. Innehåller databas-anslutning. 
* Destructor - Destruerare som stänger databaskopplingen. 
* setFood(string $food_name, string $food_description, string $food_price, int $food_category, int $food_type): bool - Set-metod som kontrollerar att inga tomma värden lagras och sätter värdena
* addFood(string $food_name, string $food_description, string $food_price, int $food_category, int $food_type) - Kontrollerar set-metoder, sanerar input, sql-fråga med INSERT till tabellen food
* updateFood(int $food_id, string $food_name, string $food_description, string $food_price, int $food_category, int $food_type): bool - Kontrollerar set-metoder, sanerar input, sql-fråga med Update till tabellen food. Uppdaterar maträtt med det id($food_id) som är angett
* getFood(): array - Hämtar alla maträtter med en SQL-fråga där JOIN används för att få ut kategorins namn, istället för kategorins id
* deleteFood(int $food_id): bool - Tar bort en maträtt utifrån det ID som skickas med i SQL-frågan med DELETE
* getfoodById(int $food_id): array - Hämtar en specifik maträtt utifrån dess ID

## Anvädning av API
Här nedan visas hur de olika API:erna används med metoderna GET, POST, PUT, DELETE
### Bookingapi.php
Länk till API: https://studenter.miun.se/~emfo2102/writeable/projekt_webservice/bookingapi.php)

| Metod  | Ändpunkt | Beskrivning |
| ------ | ------------- | ------------- |
GET      | bookingapi.php|    Hämtar alla lagrade bokningar     |
GET      | bookingapi.php?booking_id=booking_id|    Hämtar en specifik bokning med angivet booking_id     |
POST      | bookingapi.php|    Lagrar bokning, kräver att ett boknings-objekt skickas med |
PUT     | bookingapi.php?booking_id=booking_id|    Uppdaterar specifik bokning. Kräver att ett boknings-objekt skickas med och dess booking_id    |
DELETE | bookingapi.php?booking_id=booking_id | Raderar en bokning med angivet booking_id|

### Foodapi.php
Länk till API: https://studenter.miun.se/~emfo2102/writeable/projekt_webservice/foodapi.php
| Metod  | Ändpunkt | Beskrivning |
| ------ | ------------- | ------------- |
GET | foodapi.php | Hämtar alla lagrade maträtter|
GET | foodapi.php?food_id=food_id | Hämtar specifik maträtt med angivet food_id |
POST | foodapi.php |  Lagrar maträtt, kräver att ett maträtts-objekt skickas med |
PUT  | foodapi.php?food_id=food_id| Uppdaterar specifik maträtt. Kräver att ett maträtt-objekt skickas med och dess food_id   |
DELETE | foodapi.php?food_id=food_id | Raderar en maträtt med angivet food_id|

### Drinkapi.php
Länk till API: https://studenter.miun.se/~emfo2102/writeable/projekt_webservice/drinkapi.php
| Metod  | Ändpunkt | Beskrivning |
| ------ | ------------- | ------------- |
GET | drinkapi.php | Hämtar alla lagrade drycker|
GET | drinkapi.php?drink_id=drink_id| Hämtar specifik dryck med angivet drink_id |
POST | drinkapi.php  |  Lagrar dryck, kräver att ett dryck-objekt skickas med |
PUT  | drinkapi.php?drink_id=drink_id| Uppdaterar specifik dryck. Kräver att ett dryck-objekt skickas med och dess drink_id   |
DELETE | drinkapi.php?drink_id=drink_id| Raderar en dryck med angivet drink_id|

### Loginapi.php
Länk till API: https://studenter.miun.se/~emfo2102/writeable/projekt_webservice/loginapi.php
| Metod  | Ändpunkt | Beskrivning |
| ------ | ------------- | ------------- |
POST | loginapi.php | Loggar in användare. Detta är den enda tillåtna metoden| 
























