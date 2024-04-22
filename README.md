breve descrizione

Istruzioni per il collegamento
ho usato un DB MySQL - bisogna configurarlo all'interno del file .env 

All'interno dell'applicazione ci sono 3 commandi per popolare i DB con i dati forniti:

    name: 'app:discount_importer',description: 'Import Discounts Record From Static ENDPOINT'
    name: 'app:shop_importer',description: 'Import Shops Record From Static ENDPOINT'
    name: 'app:csv_shop_importer',description: 'Import Shops Record from CSV File given the Path as an input Parameter'

Una volta popolato il DB per visualizzare i dati sono stati creati 2  ENDPOINT:

- Endpoint degli shop: http://localhost/shops
- Endpoint degli utenti: http://localhost/users