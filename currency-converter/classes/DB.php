<?php
    class DB
    {
        private mysqli $conn;

        public function __construct(string $host, string $user, string $pass, string $db)
        {
            $this->conn = new mysqli($host, $user, $pass, $db);
            if ($this->conn->connect_error) {
                throw new Exception('DB connection failed: ' . $this->conn->connect_error);
            }
            $this->conn->set_charset('utf8mb4');
        }

        /**
         * Logs a conversion in conversion_logs table
         */
        public function logConversion(
            string $from,
            string $to,
            float  $inputAmount,
            float  $resultAmount
        ): void {
            $stmt = $this->conn->prepare(
                "INSERT INTO conversion_logs
                (from_currency, to_currency, input_amount, result_amount)
                VALUES (?, ?, ?, ?)"
            );
            $stmt->bind_param('ssdd', $from, $to, $inputAmount, $resultAmount);
            $stmt->execute();
            $stmt->close();
        }

        public function __destruct()
        {
            $this->conn->close();
        }
    }
?>