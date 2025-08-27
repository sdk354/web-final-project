<?php
class BookingController
{
    private $conn;

    public function __construct($dbConnection)
    {
        $this->conn = $dbConnection;
    }

    // Create a booking
    public function createBooking($studentId, $facilityId, $date, $start, $end, $status = 'Pending')
    {
        // 1) Check if facility exists
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM facilities WHERE facility_id = ?");
        $stmt->bind_param("i", $facilityId);
        $stmt->execute();
        $result = $stmt->get_result();
        if (($result->fetch_assoc()['count'] ?? 0) == 0) {
            $stmt->close();
            return ['success' => false, 'message' => 'Facility not found'];
        }
        $stmt->close();

        // 2) Check for time conflicts (ignore cancelled)
        // Overlap condition: NOT (existing_end <= new_start OR existing_start >= new_end)
        $stmt = $this->conn->prepare("
            SELECT COUNT(*) AS count
            FROM bookings
            WHERE facility_id = ?
              AND date = ?
              AND status <> 'Cancelled'
              AND NOT (end_time <= ? OR start_time >= ?)
        ");
        $stmt->bind_param("isss", $facilityId, $date, $start, $end); // compare with new start/end correctly
        $stmt->execute();
        $result = $stmt->get_result();
        if (($result->fetch_assoc()['count'] ?? 0) > 0) {
            $stmt->close();
            return ['success' => false, 'message' => 'Time slot already booked'];
        }
        $stmt->close();

        // 3) Insert booking
        $stmt = $this->conn->prepare("
            INSERT INTO bookings (student_id, facility_id, date, start_time, end_time, status)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("iissss", $studentId, $facilityId, $date, $start, $end, $status);
        if ($stmt->execute()) {
            $stmt->close();
            return ['success' => true];
        }
        $error = $stmt->error;
        $stmt->close();
        return ['success' => false, 'message' => $error];
    }

    // List bookings for a specific student, with optional limit
    // Note: ensures booking_id is present and uses consistent ordering
    public function listStudentBookings($studentId, $limit = null)
    {
        $sql = "
        SELECT
            b.booking_id,
            b.student_id,
            b.facility_id,
            b.date,
            b.start_time,
            b.end_time,
            b.status,
            f.name AS facility_name
        FROM bookings b
        JOIN facilities f ON b.facility_id = f.facility_id
        WHERE b.student_id = ?
        ORDER BY b.date ASC, b.start_time ASC
    ";

        if ($limit !== null) {
            $sql .= " LIMIT ?";
        }

        $stmt = $this->conn->prepare($sql);
        if ($limit !== null) {
            $limit = (int)$limit;
            $stmt->bind_param("ii", $studentId, $limit);
        } else {
            $stmt->bind_param("i", $studentId);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $bookings = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $bookings;
    }


    // HARD delete (what you asked for): remove a future booking owned by the user
    public function cancelBooking($bookingId, $studentId)
    {
        $stmt = $this->conn->prepare("
            DELETE FROM bookings
            WHERE booking_id = ?
              AND student_id = ?
              AND (
                    date > CURDATE()
                    OR (date = CURDATE() AND end_time > CURRENT_TIME)
                  )
        ");
        $stmt->bind_param("ii", $bookingId, $studentId);
        $stmt->execute();
        $affected = $stmt->affected_rows;
        $stmt->close();
        return $affected > 0; // true if actually deleted
    }

    // OPTIONAL: Soft-cancel (status flag) if you decide not to delete
    public function softCancelBooking($bookingId, $studentId)
    {
        $stmt = $this->conn->prepare("
            UPDATE bookings
            SET status = 'Cancelled'
            WHERE booking_id = ?
              AND student_id = ?
              AND (
                    date > CURDATE()
                    OR (date = CURDATE() AND end_time > CURRENT_TIME)
                  )
        ");
        $stmt->bind_param("ii", $bookingId, $studentId);
        $stmt->execute();
        $affected = $stmt->affected_rows;
        $stmt->close();
        return $affected > 0;
    }
}
