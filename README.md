# Descrizione

Questo progetto è un'applicazione sviluppata con Symfony che fornisce API per accedere ai dati relativi agli shop e alle offerte.

## Requisiti

- PHP >= 7.4
- MySQL
- Symfony
- Doctrine

## Installazione

1. Clonare questo repository sul proprio ambiente locale.
2. Eseguire `composer install` per installare le dipendenze.
3. Copiare il file `.env.example` e rinominarlo in `.env`, quindi configurare le variabili d'ambiente necessarie come ad esempio la connessione al database.
4. Eseguire `php bin/console doctrine:database:create` per creare il database.
5. Eseguire `php bin/console doctrine:migrations:migrate` per eseguire le migrazioni e creare le tabelle nel database.

### API

Le seguenti API sono disponibili:

- `/api/v1/offers/{shopID}` [GET]: Restituisce le offerte dello shop specificato ordinandole in modo crescente per prezzo.
- `/api/v1/offers/{countryCode}` [GET]: Ritorna le offerte presenti nel paese specificato, includendo anche gli shop in cui trovare il prodotto.

## Fonti Dati

- API per gli shop
- CSV per gli shop
- API per le offerte