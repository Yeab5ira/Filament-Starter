## Introduction
The Computerized Maintenance Management System (CMMS) for Zero Liquid Discharge (ZLD) wastewater treatment plants is a proposed software solution aimed at streamlining the operation and maintenance of these critical industrial facilities. The software will focus on enhancing asset management, improving operational efficiency, and supporting environmental sustainability in industrial parks.
This system will be designed to track equipment, schedule preventive maintenance, generate work orders, and manage spare parts inventory, ensuring that all critical components of the ZLD process are maintained and operate smoothly. It will also integrate with real-time monitoring systems, allowing plant operators to observe equipment performance and detect issues early to prevent costly downtime or failures.

## Modules
1. User & Role Management
2. Asset Management
3. Work Order Management
4. Preventive Maintenance Scheduling
5. Inventory Management
6. Reporting & Analysis Management
7. Compliance Tracking Management

## Prerequisites

Ensure you have the following installed on your machine:

1. PHP (version 8.0+)
2. Composer
3. MySQL or another compatible database
4. Node.js and npm (for frontend assets)

## Installation Steps

Clone the repository:
``` git clone https://github.com/Yeab5ira/CMMS.git ```
``` cd CMMS ```

Install PHP dependencies: Run the following command to install all required PHP packages via Composer:
``` composer install ```

Install JavaScript dependencies: Use npm to install the necessary JavaScript dependencies:
``` npm install ```

Set up the .env file:

Copy the .env.example file to create a .env file:
``` cp .env.example .env ```

Open .env and configure the following database and other settings:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

Generate the application key:
``` php artisan key:generate ```

Run migrations and seed the database:

Run migrations to create the necessary tables:
``` php artisan migrate ```
Create an admin account
``` php artisan make:filament-user ```
Optionally, seed the database to populate it with initial data:
``` php artisan db:seed ```

Compile frontend assets: Use the following command to compile and bundle JavaScript and CSS assets:
``` npm run dev ```

Start the development server: Run this command to start the Laravel development server:
``` php artisan serve ```

Your application should now be accessible at http://localhost:8000.

## Documentation
### How to make a model auditable?
- Add ``use \OwenIt\Auditing\Auditable;`` trait to your model
- Implement ``Auditable`` on the class from `` OwenIt\Auditing\Contracts\Auditable; ``
#### Example
```
use OwenIt\Auditing\Contracts\Auditable;
class User extends Authenticatable implements Auditable
{
    use \OwenIt\Auditing\Auditable;
}
```

## Packages Used
* https://laravel-auditing.com - Implement laravel project wide auditing functions
* https://filamentphp.com/plugins/bezhansalleh-shield - Implement Roles and Permissions
* https://filamentphp.com/plugins/tapp-network-laravel-auditing - Implement auditing functions in filament panel 
* https://filamentphp.com/plugins/joaopaulolndev-edit-profile - Add profile page in filament
