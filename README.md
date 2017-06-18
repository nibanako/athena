Athena
======

Sistema de monitorización de servidores

Requerimientos
--------------

Necesitas tener instalado [Composer](https://getcomposer.org), [Bower](http://bower.io) y git.

El _document root_ de la aplicación debe estar apuntando a la carpeta `web`.

Además, necesitas que PHP tenga instalada la extensión ssh2. En Debian es tan fácil como:

```bash
$ sudo apt-get install libssh2-php
```

Instalación
-----------

```bash
$ git clone https://github.com/nibanako/athena.git
$ cd athena
$ composer install
$ bower install
```

Estructura
----------

Carpeta | Descripción
------- | -----------
config | Configuración de la aplicación
src | Código fuente de tu aplicación
var | Usos varios
web | Punto de entrada a la aplicación