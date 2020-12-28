<?php

namespace DB;

class DBAccess {
  
  private const HOST_DB = "localhost";
  private const USERNAME = "root";
  private const PASSWORD = ""; // pwd collegamento database sql
  private const DATABASE_NAME = "MyCandies";

  private $connection;
  
  public function openDBConnection() {
    
    $this->connection = mysqli_connect(DBAccess::HOST_DB, DBAccess::USERNAME, 
DBAccess::PASSWORD, DBAccess::DATABASE_NAME);
    
    mysqli_connect_errno($this->connection);
    // restituisce 0 se la connessione è andata a buon fine
    if(!$this->connection) {
      return false;
    } else {
      return true;
    }
  }

  public function getConnection() {
    return $this->connection;
  }

  public function closeConnection() {
    return $this->connection->close();
  }
}
?>
