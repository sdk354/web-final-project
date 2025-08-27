<?php
class FacilityController
{
    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    // List all facilities
    public function listFacilities()
    {
        $result = $this->conn->query("SELECT * FROM facilities ORDER BY name ASC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Get details for a single facility
    public function getFacility($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM facilities WHERE facility_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Admin: Add a new facility
    public function addFacility($name, $description)
    {
        $stmt = $this->conn->prepare("INSERT INTO facilities (name, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $description);
        return $stmt->execute();
    }

    // Admin: Update facility details
    public function updateFacility($id, $name, $description)
    {
        $stmt = $this->conn->prepare("UPDATE facilities SET name = ?, description = ? WHERE facility_id = ?");
        $stmt->bind_param("ssi", $name, $description, $id);
        return $stmt->execute();
    }

    // Admin: Delete a facility
    public function deleteFacility($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM facilities WHERE facility_id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
