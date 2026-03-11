# Guía de Despliegue en cPanel (Hosting Compartido)

Para desplegar **Saldo Wallet** en cPanel y que funcionen los subdominios correctamente, sigue estos pasos exactos.

## 1. Subir los Archivos
No debes separar el código. Todo el proyecto Laravel es una sola unidad.

1. Comprime toda la carpeta de tu proyecto en un archivo `.zip` (excluyendo `node_modules` y `vendor` para hacerlo más ligero, aunque `vendor` es necesario si no tienes acceso a terminal SSH en el hosting).
   - *Recomendación:* Si no tienes acceso SSH, sube la carpeta `vendor`.
2. Sube el `.zip` al "Administrador de Archivos" de cPanel, dentro de `public_html/saldo.com.co` (o la carpeta raíz que hayas elegido).
3. Descomprímelo.

## 2. Configuración Crítica de Subdominios (IMPORTANTE)
Laravel maneja los subdominios internamente. **No debes tener carpetas separadas para cada subdominio.**

En tu cPanel, ve a **Dominios** (o Subdominios) y edita la **Raíz del documento (Document Root)** de todos tus subdominios (`admin`, `pay`, `api`) para que apunten EXACTAMENTE a la carpeta `public` de tu proyecto principal.

Si tu proyecto está en `/public_html/saldo.com.co`, entonces:

| Dominio | Raíz del Documento (Document Root) |
|---------|------------------------------------|
| `saldo.com.co` | `/public_html/saldo.com.co/public` |
| `pay.saldo.com.co` | `/public_html/saldo.com.co/public` |
| `admin.saldo.com.co` | `/public_html/saldo.com.co/public` |
| `api.saldo.com.co` | `/public_html/saldo.com.co/public` |

**¡Todos deben apuntar a la misma carpeta `public`!** Laravel sabrá qué mostrar dependiendo de si entras por `pay` o por `admin`.

## 3. Base de Datos
1. Crea una base de datos MySQL en cPanel.
2. Importa tu estructura (puedes exportarla de local o ejecutar migraciones si tienes SSH).
3. Edita el archivo `.env` en el servidor con las credenciales de la base de datos del hosting.

## 4. Permisos
Asegúrate de que las carpetas `storage` y `bootstrap/cache` tengan permisos de escritura (775 o 777 si es necesario).

## 5. Enlaces Simbólicos
Si las imágenes no cargan, necesitarás crear el enlace simbólico. Si tienes acceso a terminal en cPanel:
```bash
php artisan storage:link
```
Si no, puedes crear una ruta temporal en `routes/web.php` para ejecutarlo una vez:
```php
Route::get('/link', function () {
    Artisan::call('storage:link');
    return 'Enlace creado';
});
```
