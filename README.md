<p align="center">
    <a href="https://getcomposer.org">
        <img src="https://getcomposer.org/img/logo-composer-transparent.png" alt="Composer">
    </a>
</p>
<h1 align="center">Dependency Management for PHP</h1>

Composer helps you declare, manage, and install dependencies of PHP projects.

See [https://getcomposer.org/](https://getcomposer.org/) for more information and documentation.

[![Continuous Integration](https://github.com/composer/composer/workflows/Continuous%20Integration/badge.svg?branch=main)](https://github.com/composer/composer/actions)

### Installation:
--------------------
1. Install XAMPP
2. Install Composer
3. Create Project:
    ```
    composer init
    ```
4. Package Install for PHP:
    ```
    composer require php-mqtt/client:^2.1
    ```
5. Create Virtual Environment for Python Client:
    ```
    python -m venv env
    ```
    then,
    ```
    .\env\Scripts\activate
    ```

7. Run this project (use individual 3 terminal):
   ```
   php src/Controller/MqttServer.php
   ```
   and
   ```
   php -S localhost:8000
   ```
   and
   ```
   python client.py
   ```
8. Or, If you want to run the php server on the terminal,

    ```
    php MqttServer.php
    ```
9. Run the client:
   ```
   python client.py
   ```


Authors
-------

- Md Saifuzzaman Sohan | [GitHub](https://github.com/MSSohan)  | <sohan.cu.eee.17@gmail.com> |
