DELIMITER //

CREATE PROCEDURE insertUser(_first_name varchar(40), _last_name varchar(20), _email varchar(50), _telephone char(10),
    _password varchar(255), _sex enum('M', 'F', 'O'), _date_of_birth date, _country varchar(20), _region varchar(20),
    _province varchar(20), _city varchar(20), _CAP char(5), _street varchar(30), _street_number int(16))

    BEGIN
        DECLARE @userId;
        DECLARE @addressId

        INSERT INTO `Customers` (`first_name`, `last_name`, `email`, `telephone`, `password`, `sex`, `date_of_birth`)
            VALUES (_first_name, _last_name, _email,  _telephone, _password, _sex, _date_of_birth);

        SET @userId = LAST_INSERT_ID();

        INSERT INTO `Addresses` (`country`, `region`, `province`, `city`, `CAP`, `street`, `street_number`)
            VALUES (_country, _region, _province, _city, _CAP, _street, _street_number);

        SET @addressId = LAST_INSERT_ID();

        INSERT INTO `CustomersAddresses` (`customer_id`, `address_id`)
            VALUES (userId, addressId)

    END //
