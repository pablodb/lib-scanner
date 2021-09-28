
# Lib Scanner


## Config inicial

- Instalar dependencias
```bash
  composer install
```

- Crear archivo de variables de entorno y configurar parametros de conexion a la Base de datos
```bash
  cp .env.dist .env
```
    

## Comandos disponibles

**- Ver comandos disponibles**
```bash
  ./bin/lib-scanner
```

- Inicializar estructura de la base de datos (asume que la DB esta creada).

```bash
  ./bin/lib-scanner db:inicializar
```

**- Crear arbol de dependencias**

```bash
  ./bin/lib-scanner lib:tree DIRECTORY [--export]
```
Parametros disponibles

`DIRECTORY:` Directorio donde se encuentran los proyectos a escanear

`--export: ` Exporta el arbol a un archivo

Ejemplo de uso:

  ```bash
  ./bin/lib-scanner lib:tree /home/mis_proyectos

  ./bin/lib-scanner lib:tree /home/mis_proyectos --export
```


**- Buscar proyectos/librerias que dependen de una libreria**

```bash
  ./bin/lib-scanner lib:search DIRECTORY LIBRARY [BRANCH]
```
Parametros disponibles

`DIRECTORY:` Directorio donde se encuentran los proyectos a escanear

`LIBRARY: ` Nombre de la librería a buscar

`BRANCH: ` Nombre del branch a buscar

Ejemplo de uso:

  ```bash
  ./bin/lib-scanner lib:search /home/mis_proyectos phpunit/phpunit

  ./bin/lib-scanner lib:search /home/mis_proyectos phpunit/phpunit master
```

**NOTA:** Al buscar con el parametro BRANCH, los resultados que coincidan con el branch buscado se mostraran en verde. Los resultados que coincidan con la libreria pero no con el branch se mostraran en blanco.


**- Buscar proyectos/librerias que dependen de una libreria (DESDE LA DB)**

```bash
  ./bin/lib-scanner lib:search-from-db DIRECTORY LIBRARY
```
Parametros disponibles

`DIRECTORY:` Directorio donde se encuentran los proyectos a escanear

`LIBRARY: ` Nombre de la librería a buscar

Ejemplo de uso:

  ```bash
  ./bin/lib-scanner lib:search-from-db /home/mis_proyectos phpunit/phpunit

  ./bin/lib-scanner lib:search-from-db /home/mis_proyectos phpunit/phpunit
```
