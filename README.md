# Fjord-CSV

## Import/Export CSV-Data of your CRUD-Models

## Install
Install the package via composer:

```bash
composer require aw-studio/fjord-csv
```

You have to install the node package via npm:

```bash
npm i vendor/aw-studio/fjord-csv
```

In order to extend your Fjord-application, follow the instructions in the fjord documentation about
extending the Fjord Vue application.

Require the package in your `app.js` file:

```js
import Fjord from "fjord";

require('fjord-csv')

const store = {};

new Fjord({
  store
});
```

Include the compiled version of your `app.js` file in your `config/fjord.php` file

```php
return [
    ...
    'assets' => [
        // Set path to the app.js file.
        'js' => '/path/to/compiled/app.js',
    ],
    ...
];
```
