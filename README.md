Sncf SDK
========

This repository contains a small library to consume Sncf web service in order to get new trains at a given station.


First and last arguments are UIC codes and can be found [here][UIC].
It's a unique ID defining a station.

Usage:
```php
$api = new Juuuuuu\Sncf\Api('87386003', 'username', 'password', ['87384008']);
$trains = $api->getNextTrains();
```

[UIC]: https://ressources.data.sncf.com/explore/dataset/referentiel-gares-voyageurs
