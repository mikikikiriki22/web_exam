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
      $dbname = 'web_final';
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
      #echo "Connected successfully";
    } catch (PDOException $e) {
      echo "Connection failed: " . $e->getMessage();
    }
  }

  /** TODO
   * Implement DAO method used to get employees performance report
   */
  public function employees_performance_report() {
    $query = "SELECT 
            e.employeeNumber AS id,
            CONCAT(e.firstName, ' ', e.lastName) AS full_name,
            e.email,
            IFNULL(SUM(p.amount), 0) AS total
        FROM employees e
        LEFT JOIN customers c ON e.employeeNumber = c.salesRepEmployeeNumber
        LEFT JOIN payments p ON c.customerNumber = p.customerNumber
        GROUP BY e.employeeNumber, e.firstName, e.lastName, e.email
        ORDER BY e.employeeNumber";
    
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /** TODO
   * Implement DAO method used to delete employee by id
   */
  public function delete_employee($employee_id) {
     $query = "DELETE FROM employees WHERE employeeNumber = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->execute(['id' => $employee_id]);
  }

  /** TODO
   * Implement DAO method used to edit employee data
   */
  public function edit_employee($employee_id, $data) {
    $query = "UPDATE employees 
              SET firstName = :first_name, lastName = :last_name, email = :email 
              WHERE employeeNumber = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->execute([
        'first_name' => $data['first_name'],
        'last_name'  => $data['last_name'],
        'email'      => $data['email'],
        'id'         => $employee_id
    ]);
    // Return the updated employee
    $select = $this->conn->prepare("SELECT employeeNumber AS id, firstName, lastName, email FROM employees WHERE employeeNumber = :id");
    $select->execute(['id' => $employee_id]);
    return $select->fetch(PDO::FETCH_ASSOC);
  }

  /** TODO
   * Implement DAO method used to get orders report
   */
  public function get_orders_report() {
    $query = "SELECT 
                o.orderNumber AS order_number,
                SUM(od.quantityOrdered * od.priceEach) AS total_amount
              FROM orders o
              JOIN orderdetails od ON o.orderNumber = od.orderNumber
              GROUP BY o.orderNumber
              ORDER BY o.orderNumber";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

  /** TODO
   * Implement DAO method used to get all products in a single order
   */
  public function get_order_details($order_id) {$query = "SELECT 
                p.productName AS product_name,
                od.quantityOrdered AS quantity,
                od.priceEach AS price_each
              FROM orderdetails od
              JOIN products p ON od.productCode = p.productCode
              WHERE od.orderNumber = :order_id";
    $stmt = $this->conn->prepare($query);
    $stmt->execute(['order_id' => $order_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);}
}
