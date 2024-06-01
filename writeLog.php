<?php
// Function to get the client IP address
class Log {
    private function getClientIP() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        } elseif (isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        } else {
            $ipaddress = 'UNKNOWN';
        }
        return $ipaddress;
    }
    private function getContentTypeRequest() {
        $header = apache_request_headers();
        if(isset($header['Content-Type']))
            return $header['Content-Type'];
        return null;
    }
    private function getBodyRequest() {
        return json_decode(file_get_contents('php://input'), true);
    }
    public function writeLogReq() {

        // Get the client IP address
        $client_ip = $this->getClientIP();

        // Create or open the log file in append mode
        $file = fopen("client_ips.txt", "a");

        // Check if file is opened successfully
        if ($file) {
            // Write the IP address and the current timestamp to the file
            $log_entry = "ip: " . date('Y-m-d H:i:s') . " - " . $client_ip . PHP_EOL;
            
            $headerType = $this->getContentTypeRequest();
            $dataSent = $this->getBodyRequest();
            $log_entry .= "Body: ".json_encode($dataSent) . PHP_EOL;
            $log_entry .= "Header: ". $headerType . PHP_EOL;
            $log_entry .= "requestURI : ". $_SERVER['REQUEST_URI'] . PHP_EOL;
            $log_entry .= "requestMethod : ". $_SERVER['REQUEST_METHOD'] . PHP_EOL;

            fwrite($file, $log_entry);

            // Close the file
            fclose($file);
        } 
    }
    public function writeLogRes($Res) {
        $file = fopen("client_ips.txt", "a");
        if($file) {
            $log_entry = "Response: ".json_encode($Res).PHP_EOL;
            fwrite($file, $log_entry);
            fclose($file);
        }
    }
}

// Return a response to the client

?>