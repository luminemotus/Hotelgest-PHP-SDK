HotelGest SDK for PHP
=====

Este script te permitirá generar los conexion Rest con Hotelgest.

## Instalación

Añade las dependencias vía composer: `"hotelgest/kit": "0.*"`

```bash
composer update
```

## Ejemplo 

Este proceso 

```php


# Cargamos la clase con los parámetros base

$hotel = new KitHotelgest\kit\KitHotelgest( $user, $password );

# Indicamos los campos para el pedido


$returnBooking = $hotel->set_booking($booking);


--------


