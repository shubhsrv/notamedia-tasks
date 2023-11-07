<?php
/**
 * Class TableCreator
 * Creates a table Test and provides methods to manage and retrieve data.
 *
 * @final
 */
final class TableCreator
{
    private $db;

    /**
     * TableCreator constructor.
     * Initializes the database connection and creates the Test table.
     */
    public function __construct()
    {
        $this->db = new mysqli("localhost", "username", "password", "database");

        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }

        $this->createTable();
        $this->fillTable();
    }

    /**
     * Creates the Test table if it doesn't exist.
     */
    private function createTable()
    {
        $query = "CREATE TABLE IF NOT EXISTS Test (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    script_name VARCHAR(25),
                    start_time DATETIME,
                    end_time DATETIME,
                    result ENUM('normal', 'illegal', 'failed', 'success')
                )";

        $this->db->query($query);
    }

    /**
     * Fills the Test table with random data.
     */
    private function fillTable()
    {
        $scripts = ['ScriptA', 'ScriptB', 'ScriptC', 'ScriptD'];
        $results = ['normal', 'illegal', 'failed', 'success'];

        for ($i = 0; $i < 10; $i++) {
            $script = $scripts[array_rand($scripts)];
            $startTime = date('Y-m-d H:i:s', strtotime('-' . rand(1, 30) . ' days'));
            $endTime = date('Y-m-d H:i:s', strtotime($startTime . ' +' . rand(1, 10) . ' hours'));
            $result = $results[array_rand($results)];

            $query = "INSERT INTO Test (script_name, start_time, end_time, result) 
                      VALUES ('$script', '$startTime', '$endTime', '$result')";

            $this->db->query($query);
        }
    }

    /**
     * Retrieves data from the Test table based on the result.
     *
     * @param string $result
     * @return array
     */
    public function getData($result)
    {
        $query = "SELECT * FROM Test WHERE result = '$result'";
        $result = $this->db->query($query);

        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    /**
     * Closes the database connection.
     */
    public function closeConnection()
    {
        $this->db->close();
    }
}

// Example usage:

// Instantiate the TableCreator class
$tableCreator = new TableCreator();

// Get 'normal' result data from the Test table
$normalResultData = $tableCreator->getData('normal');

// Output the retrieved data
echo "Data with 'normal' result:\n";
print_r($normalResultData);

// Close the database connection
$tableCreator->closeConnection();
?>
