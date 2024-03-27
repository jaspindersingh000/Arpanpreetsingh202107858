<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$Pollutant_type = $Pollution_level = $Location = $Date_or_time = "";
$Pollutant_type_err = $Pollution_level_err = $Location_err = $Date_or_time_err = "";

// Processing form data when form is submitted
if (isset($_POST["id"]) && !empty($_POST["id"])) {
    // Get hidden input value
    $id = $_POST["id"];

     // Validate Pollutant_type
     $input_Pollutant_type = trim($_POST["Pollutant_type"]);
     if (empty($input_Pollutant_type)) {
         $Pollutant_type_err = "Please enter the Pollutant_type.";
     } else {
         $Pollutant_type = $input_Pollutant_type;
     }

    // Validate Pollution_level
    $input_Pollution_level = trim($_POST["Pollution_level"]);
    if (empty($input_Pollution_level)) {
        $Pollution_level_err = "Please enter Pollution_level.";
    } else {
        $Pollution_level = $input_Pollution_level;
    }

    // Validate Location
    $input_Location = trim($_POST["Location"]);
    if (empty($input_Location)) {
        $Location_err = "Please enter Location.";
    } else {
        $Location = $input_Location;
    }

    // Validate Date_or_time
    $input_Date_or_time = trim($_POST["Date_or_time"]);
    if (empty($input_Date_or_time)) {
        $Date_or_time_err = "Please enter Date and Time.";
    } else {
        $Date_or_time = $input_Date_or_time;
    }

    // Check input errors before inserting in database
    if (empty($Pollutant_type_err) && empty($Pollution_level_err) && empty($Locationn_err) && empty($Date_or_time_err)) {
        // Prepare an update statement
$sql = "UPDATE pollution SET Pollutant_type=?, Pollution_level=?, Location=?, Date_or_time=? WHERE id=?";

if ($stmt = mysqli_prepare($link, $sql)) {
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "ssssi", $param_Pollutant_type, $param_Pollution_level, $param_Location, $param_Date_or_time, $param_id);

    // Set parameters
    $param_Pollutant_type = $Pollutant_type;
    $param_Pollution_level = $Pollution_level;
    $param_Location = $Location;
    $param_Date_or_time = $Date_or_time;
    $param_id = $id;

    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
        // Records updated successfully. Redirect to landing page
        header("location: index.php");
        exit();
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }
}

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
} else {
    // Check existence of id parameter before processing further
    if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        // Get URL parameter
        $id =  trim($_GET["id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM pollution WHERE id = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            // Set parameters
            $param_id = $id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1) {
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $Pollutant_type = $row["Pollutant_type"];
                    $Pollution_level = $row["Pollution_level"];
                    $Location = $row["Location"];
                    $Date_or_time = $row["Date_or_time"];
                } else {
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);

        // Close connection
        mysqli_close($link);
    } else {
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper {
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Update Record</h2>
                    <p>Please edit the input values and submit to update the employee record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                    <div class="form-group">
                            <label>Pollutant_type</label>
                            <input type="text" name="Pollutant_type" class="form-control <?php echo (!empty($Pollutant_type_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Pollutant_type; ?>">
                            <span class="invalid-feedback"><?php echo $Pollutant_type_err; ?></span>
                        </div>   
                    <div class="form-group">
                            <label>Pollution_level</label>
                            <input type="text" name="Pollution_level" class="form-control <?php echo (!empty($Pollution_level_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Pollution_level; ?>">
                            <span class="invalid-feedback"><?php echo $Pollution_level_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Location</label>
                            <input type="text" name="Location" class="form-control <?php echo (!empty($Location_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Location; ?>">
                            <span class="invalid-feedback"><?php echo $Location_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Date_or_time</label>
                            <input type="text" name="Date_or_time" class="form-control <?php echo (!empty($Date_or_time_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Date_or_time; ?>">
                            <span class="invalid-feedback"><?php echo $Date_or_time_err; ?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>