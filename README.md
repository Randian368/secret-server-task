# About

## Technologies used
**Server:** Apache HTTP   
**Language:** PHP 7.4.1   
**Framework:** No frameworks were used

## Project description
**Database:** The database for this API service contains a single table named <em>secret</em>, the columns of which are detailed in the following table:
![Columns of the secret data table](/about/image/database_secret_server_table_secret.jpg)

**API Response types:** The API can respond with XML or JSON data based on the Accept HTTP request header. The Accept header is supported in a case-insensitive manner in accordance with  RFC 2616.  

**Extendability:** Additional response formats could be easily added without modifying existing code. All that would be needed for an additional response format is a new class that implements `ResponseFormatterInterface` and conforms to the specific classname format required by `ResponseFromatterFactory`.

**Hosting**: This API is hosted at: http://api.secret-server.randian368.link/v1/  
As specified in swagger.yaml, the following endpoints have been implemented:
- HTTP GET http://api.secret-server.randian368.link/v1/secret/{hash}
- HTTP POST http://api.secret-server.randian368.link/v1/secret

**Code quality**: The codebase was written in Object Oriented style and uses PSR-0 autoloading with Composer. I would say it is about 6/10 adherent to clean code principles.

Most classes, and some of their less self-evident methods do have docblock-style comments explaining at least their purpose/responsibility.
