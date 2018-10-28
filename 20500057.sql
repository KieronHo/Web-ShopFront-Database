-- PART 1 CREATING THE TABLES
-- Create Customers Table that holds the customer information
CREATE TABLE Customers (
    FirstName VARCHAR(30),
    LastName VARCHAR(30),
    PhoneNumber VARCHAR(30),
    Address VARCHAR(60),
    CId INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (CId)
);
	insert into Customers (FirstName, LastName, PhoneNumber, Address)
	values ("Gavin", "Flea", 39173041710, "1 Green Close");
	insert into Customers (FirstName, LastName, PhoneNumber, Address)
	values ("Billy", "Burr", 800834124, "10 Balders Lane");
	insert into Customers (FirstName, LastName, PhoneNumber, Address)
	values ("Andrew", "Geyser", 4303058328, "5 Wilders Street");
	insert into Customers (FirstName, LastName, PhoneNumber, Address)
	values ("Sara", "Connor", 101010110010, "8 Oroboros Loop");
	insert into Customers (FirstName, LastName, PhoneNumber, Address)
	values ("Hanz", "Simmer", 123098123098, "21 Joseph Ave");
	insert into Customers (FirstName, LastName, PhoneNumber, Address)
	values ("Jack", "Wall", 3995583713, "89 Jade Way");
	insert into Customers (FirstName, LastName, PhoneNumber, Address)
	values ("Michelle", "Lynn", 100948910, "5 Star Crescent");
	insert into Customers (FirstName, LastName, PhoneNumber, Address)
	values ("Mick", "Flippley", 0431990183, "4 Novatell Loop");
	insert into Customers (FirstName, LastName, PhoneNumber, Address)
	values ("Bear", "Horn", 101101101101, "101 Intervention Street");
	insert into Customers (FirstName, LastName, PhoneNumber, Address)
	values ("Samuel", "Fischer", 911033765, "3 Echelon Close");

-- Create Products Table that hold the product information
CREATE TABLE Products (
    Name VARCHAR(30),
    Cost DOUBLE(9 , 2 ),
    Description VARCHAR(300),
    Stock INT,
    PId INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (PId)
);
	insert into Products (Name, Cost, Description, Stock)
	values ("Halo Zero", 99.05, "Mister Thief Origin Story", 120);
	insert into Products (Name, Cost, Description, Stock)
	values ("Splinner Cell", 99.05, "Proper best gen Stealth", 25);
	insert into Products (Name, Cost, Description, Stock)
	values ("Xbox Revolution", 500.00, "Nexter Gen Console", 57);
	insert into Products (Name, Cost, Description, Stock)
	values ("Bows Headset", 290.00, "Quality Audio from Bows", 22);
	insert into Products (Name, Cost, Description, Stock)
	values ("Viscious(tm) simu-controller", 80.00, "Universal Cross Controller", 18);
	insert into Products (Name, Cost, Description, Stock)
	values ("Decent(tm) Graphics Card", 700, "4k 100fps enabled multi-threading", 14);
	insert into Products (Name, Cost, Description, Stock)
	values ("Gift Card 49.95", 49.95, "In-store use only", 250);
	insert into Products (Name, Cost, Description, Stock)
	values ("Gift Card 100", 100.00, "In-store use only", 1000);
	insert into Products (Name, Cost, Description, Stock)
	values ("Gift Card 150", 150.00, "In-store use only", 600);
	insert into Products (Name, Cost, Description, Stock)
	values ("Gift Card 200", 200.00, "In-store use only", 200);

-- Create Transactions Table that holds information on each product transaction
CREATE TABLE Transactions (
    TId INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (TId),
    CId INT,
    PId INT,
    NetCost DOUBLE(7 , 2 ),
    Number INT,
    Date DATE
);
	insert into Transactions (CId, PId, Number, NetCost, Date)
	values (1, 1, 1, 99.05, '2017-1-3');
	insert into Transactions (CId, PId, Number, NetCost, Date)
	values (1, 3, 1, 300.00, '2017-1-3');
	insert into Transactions (CId, PId, Number, NetCost, Date)
	values (1, 2, 1, 99.05, '2017-1-3');
	insert into Transactions (CId, PId, Number, NetCost, Date)
	values (3, 7, 3, 149.85, '2017-1-4');
	insert into Transactions (CId, PId, Number, NetCost, Date)
	values (2, 7, 1, 49.95, '2017-1-7');
	insert into Transactions (CId, PId, Number, NetCost, Date)
	values (2, 7, 2, 99.90, '2017-1-7');
	insert into Transactions (CId, PId, Number, NetCost, Date)
	values (2, 3, 1, 300.00, '2017/1-10');
	insert into Transactions (CId, PId, Number, NetCost, Date)
	values (4, 5, 1, 80.00, '2017-1-11');
	insert into Transactions (CId, PId, Number, NetCost, Date)
	values (5, 6, 4, 2800.00, '2017-1-11');
	insert into Transactions (CId, PId, Number, NetCost, Date)
	values (6, 3, 1, 300.00, '2017-1-15');
	insert into Transactions (CId, PId, Number, NetCost, Date)
	values (6, 8, 1, 100.00, '2017-1-15');
	insert into Transactions (CId, PId, Number, NetCost, Date)
	values (6, 7, 3, 149.85, '2017-1-15');
	insert into Transactions (CId, PId, Number, NetCost, Date)
	values (7, 10, 3, 600.00, '2017-1-24');
	insert into Transactions (CId, PId, Number, NetCost, Date)
	values (8, 3, 1, 300.00, '2017-1-28');
	insert into Transactions (CId, PId, Number, NetCost, Date)
	values (7, 7, 1, 49.95, '2017-2-2');
	insert into Transactions (CId, PId, Number, NetCost, Date)
	values (9, 2, 2, 198.10, '2017-2-3');
	insert into Transactions (CId, PId, Number, NetCost, Date)
	values (10, 7, 1, 49.95, '2017-2-4');
	insert into Transactions (CId, PId, Number, NetCost, Date)
	values (10, 3, 1, 300.00, '2017-2-4');
	insert into Transactions (CId, PId, Number, NetCost, Date)
	values (5, 10, 4, 800.00, '2017-2-6');

-- PART 2 Creating Queries
-- Query 1
SELECT 
    Customers.*, COUNT(TId) AS 'Total Transactions'
	FROM
    Transactions
        NATURAL JOIN
    Customers
	GROUP BY CId
	HAVING COUNT(TId) >= 3
	ORDER BY COUNT(TId) DESC;

-- Query 2
SELECT 
    Customers.*, SUM(NetCost) AS 'Total Spend'
FROM
    Customers
        NATURAL JOIN
    Transactions
GROUP BY CId
HAVING SUM(NetCost) >= 500
ORDER BY SUM(NetCost) DESC;

-- Query 3
SELECT 
    Products.*, COUNT(TId) AS 'Purchase Count'
FROM
    Products
        NATURAL LEFT OUTER JOIN
    Transactions
GROUP BY PId
HAVING COUNT(TId) = 0
ORDER BY PId;

-- Query 4
SELECT 
    Products.*,
    CONCAT(FirstName, ' ', LastName) AS 'Single Purchaser Name',
    CId
FROM
    Products
        NATURAL JOIN
    Customers
        NATURAL JOIN
    Transactions
GROUP BY PId
HAVING COUNT(PId) = 1;

-- Query 5
SELECT 
    Products.*, SUM(Number) AS Orders
FROM
    Products
        NATURAL LEFT OUTER JOIN
    Transactions
GROUP BY PId
ORDER BY SUM(Number) DESC;

-- Query 6
SELECT 
    SUM(NetCost) AS 'Total Cost of Sold Products'
FROM
    Transactions;

-- Query 7
DELIMITER ++ 
CREATE FUNCTION CostOfBestBuyers(number INT) RETURNS DOUBLE(9,2) BEGIN DECLARE totalCost DOUBLE(9,2);SELECT 
    SUM(TopCosts)
INTO totalCost FROM
    (SELECT 
        SUM(NetCost) AS TopCosts
    FROM
        Transactions
    GROUP BY CId
    ORDER BY SUM(NetCost) DESC
    LIMIT NUMBER) AS T; RETURN totalCost; END++ 
DELIMITER ;

-- Query 8
CREATE VIEW BuyerCostPerProduct AS
    SELECT 
        CId AS 'Customer ID',
        CONCAT(FirstName, ' ', LastName) AS 'Customer Name',
        PId AS 'Product ID',
        Name AS 'Product Name',
        CURDATE() AS Date,
        SUM(NetCost) AS 'Spending on Product'
    FROM
        Transactions
            NATURAL JOIN
        Customers
            NATURAL JOIN
        Products
    GROUP BY CId , PId;