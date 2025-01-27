# Order Payment Api

Laravel-based API for managing orders and payments, with a focus on clean code
principles and extensibility. The system should allow adding new payment gateways with
minimal effort.

## Setup
- composer install
- php artisan migrate
-  php artisan jwt:secret 
- copy .env.example in new .env file

## Postman Collection
- please import postman collection from project main dir (Order Payment Api.postman_collection.json)



## steps to add new Payment-Gatway
- create new class for paymentgateway in  "app\Services\PaymentGatways\Classes"
- this class must extend "AbstractPaymentGateway" class
- add paymentgateway config in env and use it int the new class
- you can create new interface for the specific behavior for this gate way in "app\Services\PaymentGatways\Interfaces"
- add new id in "PaymentGateway" Enum
- add gatway entry point class  in "getPaymentEntrypoint" method
- create new migration file to add this id in "payments -> payment_gateway" table



## Testing
- php artisan test
-  php artisan test --coverage-html=coverage
