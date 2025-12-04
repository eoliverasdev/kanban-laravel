# Kanban Laravel

Aplicación Kanban desarrollada con **Laravel** y **MySQL**. Permite la gestión completa de tableros y notas, incluyendo creación, edición, eliminación y organización por estados. El proyecto incluye autenticación básica, interfaz responsive y sigue una estructura limpia basada en MVC.

---

## 🚀 Funcionalidades

- CRUD completo de tableros.
- CRUD completo de notas asociadas.
- Sistema de autenticación (login / register).
- Drag & drop.
- Gestión de estados de las notas (To do, Doing, Done).
- Interfaz responsive con Blade + CSS.
- Validación de formularios.
- Uso de migraciones y seeders.
- Control de versiones con Git.

---

## 🛠️ Tecnologías utilizadas

- **Laravel 10**
- **PHP 8**
- **MySQL**
- **Blade**
- **TailwindCSS**
- **JavaScript**
- **Git**

---

## 📂 Estructura del proyecto (simplificada)
/app
/Http
/Controllers
/Middleware
/resources
/views
layouts.blade.php
kanban/
notes/
/routes
web.php
/database
migrations/


---

## ⚙️ Instalación

1. Clonar el repositorio:
   bash
   git clone https://github.com/eoliverasdev/kanban-laravel.git

2. Instalar dependencias:
   composer install
   npm install && npm run build

3. Crear archivo .env
   cp .env.example .env

4. Generar key:
   php artisan key:generate

5. Configurar base de datos .env

6. Ejecutar migraciones
   php artisan migrate

7. Levantar servidor
   php artisan serve


## ▶️ Uso

Regístrate o inicia sesión.

Crea un tablero desde el panel principal.

Añade notas a cada tablero.

Cambia el estado de cada nota (To Do → Doing → Done).

(Opcional) Usa drag & drop cuando esté disponible.

## 📸 Capturas del proyecto

## 🧠 Aprendizajes y retos técnicos

Implementación de autenticación utilizando Laravel Breeze.

Gestión de relaciones One-to-Many entre tableros y notas.

Uso de migraciones y seeders para mantener versiones de la base de datos.

Aplicación del patrón MVC en un entorno real.

Organización del código siguiendo buenas prácticas de Laravel.

Uso práctico de Git para control de versiones durante el desarrollo.
