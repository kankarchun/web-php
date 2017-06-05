
SET storage_engine = InnoDB;

-- Create and use database
drop database IF EXISTS projectDB;
create database projectDB character set utf8;
use projectDB; 

ALTER DATABASE projectDB CHARACTER SET utf8 COLLATE utf8_unicode_ci;


-- Create table
drop table IF EXISTS airline;

CREATE TABLE IF NOT EXISTS airline (

 AirlineCode VARCHAR(2) NOT NULL,

 Password VARCHAR(10) NOT NULL,
 
 airlineName VARCHAR(20) NOT NULL,

  icon VARCHAR(20) NOT NULL,
 
 PRIMARY KEY (AirlineCode)
)
ENGINE = InnoDB;

DROP TABLE IF EXISTS flightclass ;



CREATE TABLE flightclass (

  flightNo VARCHAR(6) NOT NULL,
  class VARCHAR(10) NOT NULL,
  airlineCode VARCHAR(2) NOT NULL,
  price_adult INT(11) NOT NULL,
  price_children INT(11) NOT NULL,
  price_infant INT(11) NOT NULL,
  tax INT(11) NOT NULL,
  PRIMARY KEY (FlightNo, Class),
 
 FOREIGN KEY (AirlineCode)
    REFERENCES airline (AirlineCode)

)
ENGINE = InnoDB;

DROP TABLE IF EXISTS flightschedule ;



CREATE TABLE flightschedule (

  flightNo VARCHAR(6) NOT NULL,
  depDateTime DATETIME NOT NULL,
  arrDateTime DATETIME NOT NULL,
  depAirport VARCHAR(3) NOT NULL,
  arrAirport VARCHAR(3) NOT NULL,
  flyMinute INT(11) NOT NULL,
  airCraft VARCHAR(10) NOT NULL,
  PRIMARY KEY (FlightNo,DepDateTime),
   FOREIGN KEY (FlightNo)
    REFERENCES flightclass (FlightNo)
)
ENGINE = InnoDB;


DROP TABLE IF EXISTS customer;



CREATE TABLE customer (

  CustID VARCHAR(4) NOT NULL,

  Password VARCHAR(10) NOT NULL,

  Surname VARCHAR(15) NOT NULL,

  GivenName VARCHAR(30) NOT NULL,

  DateOfBirth DATE NOT NULL,

  Gender VARCHAR(1) NOT NULL,

  Passport VARCHAR(15) NOT NULL,

  MobileNo VARCHAR(15) NOT NULL,

  Nationality VARCHAR(15) NOT NULL,

  BonusPoint INT(11) DEFAULT 0,

  PRIMARY KEY (CustID)
)
ENGINE = InnoDB;

DROP TABLE IF EXISTS staff ;



CREATE TABLE staff (

  StaffID VARCHAR(10) NOT NULL,

  Name VARCHAR(25) NOT NULL,

  Sex VARCHAR(1) NOT NULL,

  Branch VARCHAR(4) NOT NULL,

  Email VARCHAR(25) NOT NULL,

  Password VARCHAR(10) NOT NULL,

  Post VARCHAR(25) NOT NULL,

  Supervisor VARCHAR(10) DEFAULT NULL,

  PRIMARY KEY (StaffID),
  FOREIGN KEY (Supervisor)
    REFERENCES staff (StaffID)

)
ENGINE = InnoDB;

DROP TABLE IF EXISTS flightbooking ;



CREATE TABLE flightbooking (
 
 BookingID VARCHAR(7) NOT NULL,
 
 OrderDate DATE NOT NULL,

 StaffID VARCHAR(10) NOT NULL,

 CustID VARCHAR(4) NOT NULL,

 AdultNum INT(11) NOT NULL,

 ChildNum INT(11) NOT NULL,

 InfantNum INT(11) NOT NULL,
 
 AdultPrice INT(11) NOT NULL,

 ChildPrice INT(11) NOT NULL,
 
 InfantPrice INT(11) NOT NULL,
 
 TotalAmt INT(11) NOT NULL,

 FlightNo VARCHAR(6) NOT NULL,

 Class VARCHAR(10) NOT NULL,
 
 DepDateTime DATETIME NOT NULL,

 PRIMARY KEY (BookingID, FlightNo,DepDateTime),

FOREIGN KEY (CustID)
    REFERENCES customer (CustID)
,
FOREIGN KEY (StaffID)
    REFERENCES staff (StaffID)
,
FOREIGN KEY (FlightNo , Class)
    REFERENCES flightclass (FlightNo , Class)
,
FOREIGN KEY (FlightNo,DepDateTime)
    REFERENCES flightschedule (FlightNo,DepDateTime)

)
ENGINE = InnoDB;

DROP TABLE IF EXISTS hotel ;



CREATE TABLE hotel (

 HotelID INT(11) NOT NULL,

 Password VARCHAR(10) NOT NULL,

 ChiName VARCHAR(50) NOT NULL,
 
 EngName VARCHAR(80) NOT NULL,
 
 Star DECIMAL(4,1) NULL,

 Rating DECIMAL(4,1) NOT NULL,
 
 Country VARCHAR(30) NOT NULL,
 
 City VARCHAR(30) NOT NULL,

 District VARCHAR(30) NOT NULL,
 
 Address VARCHAR(255) NOT NULL,

 Tel VARCHAR(20) NOT NULL,
 
 PRIMARY KEY (HotelID)
)
ENGINE = InnoDB;

DROP TABLE IF EXISTS room ;


CREATE TABLE room (

  HotelID INT(11) NOT NULL,

  RoomType VARCHAR(45) NOT NULL,

  NonSmoking TINYINT(1) NOT NULL,

  RoomNum INT(11) NOT NULL,

  RoomSize INT(11) NOT NULL,

  AdultNum INT(11) NOT NULL,

  ChildNum INT(11) NOT NULL,

  RoomDesc VARCHAR(50) NULL,
 
 Price INT(11) NOT NULL,
 
 PRIMARY KEY (HotelID, RoomType),
 
 FOREIGN KEY (HotelID)
    REFERENCES hotel (HotelID)

)
ENGINE = InnoDB;

DROP TABLE IF EXISTS hotelbooking ;



CREATE TABLE hotelbooking (

  BookingID VARCHAR(7) NOT NULL,

  OrderDate DATE NOT NULL,

  StaffID VARCHAR(10) NOT NULL,

  CustID VARCHAR(4) NOT NULL,

  HotelID INT(11) NOT NULL,

  RoomType VARCHAR(45) NOT NULL,

  Price INT(11) NOT NULL,

  RoomNum INT(11) NOT NULL,

  TotalAmt INT(11) NOT NULL,

  Checkin DATE NOT NULL,
 
 Checkout DATE NOT NULL,
 
 Remark VARCHAR(255) NOT NULL,
 
 PRIMARY KEY (BookingID),
 
FOREIGN KEY (CustID)
    REFERENCES customer (CustID)
,
FOREIGN KEY (StaffID)
    REFERENCES staff (StaffID),
  
FOREIGN KEY (HotelID , RoomType)
    REFERENCES room (HotelID , RoomType)

)
ENGINE = InnoDB;
