# online-shopping-with-advanced-admin-page
Updated version
#Use Xampp as your local host


online shopping system with both admin and user layouts.

admin login details  Email=valentinemoraa@gmail.com and Password=Valentine123.

Add this code in your xampp database 'onlineshop' to create another table for payments 
CREATE TABLE mpesa_payments (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    address VARCHAR(255) NOT NULL,
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100),
    zip VARCHAR(20),
    mpesa_amount DECIMAL(10, 2) NOT NULL,
    mpesa_phone VARCHAR(15) NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user_info(user_id)
);
