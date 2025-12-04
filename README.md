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
```
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
```

---

## ⚙️ Instalación

1. Clonar el repositorio:
   ```bash
   git clone https://github.com/eoliverasdev/kanban-laravel.git
   
2. Instalar dependencias:
   ```
   composer install
   npm install && npm run build

3. Crear archivo .env:
   cp .env.example .env

4. Generar key:
   ```
   php artisan key:generate

5. Configurar base de datos .env

6. Ejecutar migraciones
   ```
   php artisan migrate
   
7. Levantar servidor
   ```
   php artisan serve

## ▶️ Uso

1. Regístrate o inicia sesión.

2. Crea un tablero desde el panel principal.

3. Añade notas a cada tablero.

4. Cambia el estado de cada nota (To Do → Doing → Done).

5. (Opcional) Usa drag & drop cuando esté disponible.

## 📸 Capturas del proyecto

<img width="817" height="688" alt="image" src="https://github.com/user-attachments/assets/60ff4b34-4116-41f4-84f8-cee8ec74237d" />

<img width="646" height="412" alt="image" src="https://github.com/user-attachments/assets/d81c197f-5c86-4b5f-aea5-4948c2d9d466" />

<img width="455" height="572" alt="image" src="https://github.com/user-attachments/assets/5741f379-1c67-42d8-8666-716a583d3446" />

<img width="1413" height="586" alt="image" src="https://github.com/user-attachments/assets/f41f78d3-e5c0-407f-b47c-de1d45532dd4" />

<img width="1410" height="576" alt="image" src="https://github.com/user-attachments/assets/6a930d09-b313-42f6-8260-f4b1ae4a1867" />

## 🧠 Aprendizajes y retos técnicos

-Implementación de autenticación utilizando Laravel Breeze.

-Gestión de relaciones One-to-Many entre tableros y notas.

-Uso de migraciones y seeders para mantener versiones de la base de datos.

-Aplicación del patrón MVC en un entorno real.

-Organización del código siguiendo buenas prácticas de Laravel.

-Uso práctico de Git para control de versiones durante el desarrollo.
