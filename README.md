
# backend setup
php > 8.1


git clone https://gitlab.com/vit1236/travel.git
```
cd travel
remane .env.example to .env
```

if your php 8.1 location is "C:\wamp64\bin\php\php8.1.26\php.exe "

#### install php library 
```
C:\wamp64\bin\php\php8.1.26\php.exe C:\ProgramData\ComposerSetup\bin\composer.phar up
or
composer up
```

#### generate css
```
npm i 
npm run build
```

#### generate key for encryption 
```
C:\wamp64\bin\php\php8.1.26\php.exe artisan key:generate
or
php artisan key:generate
```

#### create table and inital test data
```
C:\wamp64\bin\php\php8.1.26\php.exe artisan migrate:fresh --seed
or
php artisan migrate:fresh --seed
```

#### start server =>  http://127.0.0.1
```
C:\wamp64\bin\php\php8.1.26\php.exe  artisan serve --port=80
or
php artisan server --port=80
```




### .env can user sqlite or  mysql base on setting
sqlite
```
 DB_CONNECTION=sqlite
```
mysql
```
 DB_CONNECTION=mysql
 DB_HOST=127.0.0.1
 DB_PORT=3306
 DB_DATABASE=travel
 DB_USERNAME=root
 DB_PASSWORD=
 ```

### test user

setup user at : travel\database\seeders\AdminSeeder.php
```
role: admin  -> can mange RBAC
username:admin@gmail.com
password:admin

role: agent
username:agent@gmail.com
password:agent
```

RBAC setting http://127.0.0.1/admin

### role: admin 
-can mange RBAC
-can see the booking change history
-can see some payment information
-can update booking

### role: agent
-can update booking






Secure Flight Search and Booking System
Your task is to create a basic flight search and booking system with the following requirements:


1. Frontend: 
```
react.js
```
- Implement a search form for users to input origin, destination, and travel dates.
- Display search results with flight options.
- Create a simple booking form to capture passenger details and payment information.



```
Laravel 9
```
2. Backend:
- Develop RESTful APIs to handle search requests and bookings.
- Implement mock flight data (no need for real-time data integration).
```
travel\database\factories\FlightFactory.php
```
- Store booking information securely.


3. Security Requirements:
- Implement secure user authentication and authorization.
- Ensure all data transmissions are encrypted.
```
deploy all of the system on https 
```
- Implement proper input validation and sanitization to prevent SQL injection and XSS attacks.
```
use laravel ORM eloquence. No plain Sql.
```
- Handle and store sensitive information (e.g., payment details).
```
travel\app\Http\Controllers\Api\FlightApiController.php
$booking->credit_card_number = encrypt($request->credit_card_number);
$booking->credit_card_expiry_date = encrypt($request->credit_card_expiry_date);
$booking->credit_card_cvv = encrypt($request->credit_card_cvv);
** encryption payment information **
```

- Implement proper error handling without exposing sensitive information.
- Add basic logging for auditing purposes.
```
log booking at  booking_logs table
```


4. Additional Considerations:
- Follow GDPR/ PDPA principles for handling user data.
- Implement basic role-based access control.
```
RBAC setting http://127.0.0.1/admin
```
- Consider scalability in your design.
Technical Stack:
You may choose your preferred tech stack, but please provide justification for your choices.
