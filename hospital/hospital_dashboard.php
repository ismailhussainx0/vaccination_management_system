
<?php

include "../config/db_connection.php";
include "hospital_auth.php";

date_default_timezone_set("Asia/Karachi");

// STEP 1: HANDLE APPOINTMENT STATUS UPDATE

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST["appointment_id"], $_POST["appointment_status"])) {

        $hospitalId = $_SESSION["hospitalId"];
        $appointmentId = filter_var($_POST["appointment_id"], FILTER_VALIDATE_INT);
        $updatedStatus = $_POST["appointment_status"];

        // Validate appointment ID
        if ($appointmentId === false || $appointmentId <= 0) {
            exit("Invalid Appointment ID");
        }

        // Validate appointment status
        $allowedStatuses = ["Pending", "Confirmed", "Cancelled", "Completed"];

        if (!in_array($updatedStatus, $allowedStatuses, true)) {
            exit("Wrong Status Chosen");
        }

        try {

            // STEP 2: UPDATE APPOINTMENT STATUS

            $updateSql = "UPDATE appointments
                          SET appointment_status = ?
                          WHERE appointment_id = ?
                          AND hospital_id = ?";

            $updateStmt = mysqli_prepare($connect, $updateSql);

            $updateStmt->bind_param(
                "sii",
                $updatedStatus,
                $appointmentId,
                $hospitalId
            );

            $updateStmt->execute();

            // STEP 3: CREATE VACCINATION RECORD IF COMPLETED

            if ($updatedStatus === "Completed") {

                // Verify appointment belongs to logged-in hospital
                // and its status is Completed

                $verifySql = "SELECT appointment_id
                              FROM appointments
                              WHERE appointment_id = ?
                              AND hospital_id = ?
                              AND appointment_status = 'Completed'";

                $verifyStmt = mysqli_prepare($connect, $verifySql);

                $verifyStmt->bind_param(
                    "ii",
                    $appointmentId,
                    $hospitalId
                );

                $verifyStmt->execute();

                $verifyResult = $verifyStmt->get_result();

                if (mysqli_num_rows($verifyResult) > 0) {

                    // Check whether vaccination record already exists

                    $checkSql = "SELECT record_id
                                 FROM vaccination_record
                                 WHERE appointment_id = ?";

                    $checkStmt = mysqli_prepare($connect, $checkSql);

                    $checkStmt->bind_param("i", $appointmentId);

                    $checkStmt->execute();

                    $checkResult = $checkStmt->get_result();

                    // Insert only if record does not exist

                    if (mysqli_num_rows($checkResult) === 0) {

                        $vaccinationDate = date("Y-m-d");
                        $vaccinationStatus = "Vaccinated";

                        $vaccinationRecordSql = "INSERT INTO vaccination_record
                        (appointment_id, vaccination_status, vaccination_date)
                        VALUES (?, ?, ?)";

                        $recordStmt = mysqli_prepare(
                            $connect,
                            $vaccinationRecordSql
                        );

                        $recordStmt->bind_param(
                            "iss",
                            $appointmentId,
                            $vaccinationStatus,
                            $vaccinationDate
                        );

                        $recordStmt->execute();

                        $recordStmt->close();
                    }

                    $checkStmt->close();
                }

                $verifyStmt->close();
            }

            $updateStmt->close();

            // STEP 4: REDIRECT AFTER POST

            header("Location: hospital_dashboard.php");
            exit;

        } catch (mysqli_sql_exception $error) {

            echo "Something Went Wrong!";

        }

    } else {

        echo "Values are not set properly";

    }
}


// STEP 5: FETCH APPOINTMENTS FOR DASHBOARD
try {

    $appointmentSql = "SELECT
        appointments.appointment_id,
        child.child_name,
        vaccines.vaccine_name,
        appointments.appointment_date,
        appointments.appointment_time,
        appointments.appointment_status

        FROM appointments

        JOIN child
        ON appointments.child_id = child.child_id

        JOIN vaccines
        ON appointments.vaccine_id = vaccines.vaccine_id

        WHERE appointments.hospital_id = ?";

    $appointmentStmt = mysqli_prepare($connect, $appointmentSql);

    $appointmentStmt->bind_param(
        "i",
        $_SESSION["hospitalId"]
    );

    $appointmentStmt->execute();

    $result = $appointmentStmt->get_result();

} catch (mysqli_sql_exception $error) {

    exit("Something Went Wrong!");

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Dashboard</title>
</head>

<body>

    <h1>Hospital Dashboard</h1>

    <h2>Appointments</h2>

    <?php if (mysqli_num_rows($result) > 0) { ?>

        <table border="1" cellpadding="10">

            <tr>
                <th>Appointment ID</th>
                <th>Child Name</th>
                <th>Vaccine</th>
                <th>Appointment Date</th>
                <th>Appointment Time</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                <tr>

                    <td>
                        <?php echo $row["appointment_id"]; ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($row["child_name"]); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($row["vaccine_name"]); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($row["appointment_date"]); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($row["appointment_time"]); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($row["appointment_status"]); ?>
                    </td>

                    <td>

                        <form action="" method="post">

                            <input
                                type="hidden"
                                name="appointment_id"
                                value="<?php echo $row["appointment_id"]; ?>"
                            >

                            <select name="appointment_status" required>

                                <option value="Pending"
                                    <?php if ($row["appointment_status"] === "Pending") echo "selected"; ?>>
                                    Pending
                                </option>

                                <option value="Confirmed"
                                    <?php if ($row["appointment_status"] === "Confirmed") echo "selected"; ?>>
                                    Confirmed
                                </option>

                                <option value="Cancelled"
                                    <?php if ($row["appointment_status"] === "Cancelled") echo "selected"; ?>>
                                    Cancelled
                                </option>

                                <option value="Completed"
                                    <?php if ($row["appointment_status"] === "Completed") echo "selected"; ?>>
                                    Completed
                                </option>

                            </select>

                            <button type="submit" name="update_status">
                                Update
                            </button>

                        </form>

                    </td>

                </tr>

            <?php } ?>

        </table>

    <?php } else { ?>

        <p>No Appointment Found!</p>

    <?php } ?>

</body>
</html>
