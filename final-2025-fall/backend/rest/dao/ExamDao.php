<?php

class ExamDao
{

  private $conn;

  /**
   * constructor of dao class
   */
  public function __construct()
  {
    try {
      /** TODO
       * List parameters such as servername, username, password, schema. Make sure to use appropriate port
       */
      $host = '127.0.0.1';
      $username = 'root';
      $password = '0000';
      $dbname = 'web_exam';
      $port = 3306;

      /** TODO
       * Create new connection
       */
      $this->conn = new PDO(
        "mysql:host=" . $host . ";dbname=" . $dbname . ";port=" . $port,
        $username,
        $password,
        [
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
      );
    } catch (PDOException $e) {
      echo "Connection failed: " . $e->getMessage();
    }
  }

  /** TODO
   * Implement DAO method used to get customer information
   */
  public function get_customers() {
    $query = "SELECT * FROM customers";
    
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /** TODO
   * Implement DAO method used to get customer meals
   */
  public function get_customer_meals($customer_id) {

    $query = "SELECT f.name AS food_name, f.brand AS food_brand, m.created_at AS meal_date
              FROM foods f 
              JOIN meals m 
              ON f.id = m.food_id
              WHERE m.customer_id = " . $customer_id;
    
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /** TODO
   * Implement DAO method used to save customer data
   */
  public function add_customer($data) {
    $query = "INSERT INTO customers (first_name, last_name, birth_date) VALUES (':first_name', ':last_name', ':birth_date')";
    
    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':first_name', $data['first_name']);
    $stmt->bindParam(':last_name', $data['last_name']);
    $stmt->bindParam(':birth_date', $data['birth_date']);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /** TODO
   * Implement DAO method used to get foods report
   */
  public function get_foods_report() {}
}
