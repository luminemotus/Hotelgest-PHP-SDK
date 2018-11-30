HotelGest SDK for PHP
=====

Este script te permitirá generar los formularios para la integración de la pasarela de pago de Redsys (antes Sermepa / Servired).

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


