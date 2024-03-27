<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$Pollutant_type = $Pollution_level = $Location = $Date_or_time = "";
$Pollutant_type_err = $Pollution_level_err = $Location_err = $Date_or_time_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate Pollutant_type
    $input_Pollutant_type = trim($_POST["Pollutant_type"]);
    if (empty($input_Pollutant_type)) {
        $Pollutant_type_err = "Please enter a Pollutant type.";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $input_Pollutant_type)) {
        $Pollutant_type_err = "Please enter a valid Pollutant type.";
    } else {
        $Pollutant_type = $input_Pollutant_type;
    }

    // Validate Pollution_level
    $input_Pollution_level = trim($_POST["Pollution_level"]);
    if (empty($input_Pollution_level)) {
        $Pollution_level_err = "Please enter a Pollution level.";
    } else {
        $Pollution_level = $input_Pollution_level;
    }

    // Validate Location
    $input_Location = trim($_POST["Location"]);
    if (empty($input_Location)) {
        $Location_err = "Please enter the Location.";
    } else {
        $Location = $input_Location;
    }

    // Validate Date_or_time
    $input_Date_or_time = trim($_POST["Date_or_time"]); // Fixed variable name
    if (empty($input_Date_or_time)) {
        $Date_or_time_err = "Please enter a Date or time.";
    } else {
        $Date_or_time = $input_Date_or_time; // Fixed variable assignment
    }

    // Check input errors before inserting in database
    if (empty($Pollutant_type_err) && empty($Pollution_level_err) && empty($Location_err) && empty($Date_or_time_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO pollution (Pollutant_type, Pollution_level, Location, Date_or_time) VALUES (?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $param_Pollutant_type, $param_Pollution_level, $param_Location, $param_Date_or_time);

            // Set parameters
            $param_Pollutant_type = $Pollutant_type;
            $param_Pollution_level = $Pollution_level;
            $param_Location = $Location;
            $param_Date_or_time = $Date_or_time;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records created successfully. Redirect to landing page
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
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
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
                    <h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to add a pollution record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Pollutant type</label> <!-- Corrected label -->
                            <input type="text" name="Pollutant_type" class="form-control <?php echo (!empty($Pollutant_type_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Pollutant_type; ?>">
                            <span class="invalid-feedback"><?php echo $Pollutant_type_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Pollution level</label> <!-- Corrected label -->
                            <input type="text" name="Pollution_level" class="form-control <?php echo (!empty($Pollution_level_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Pollution_level; ?>">
                            <span class="invalid-feedback"><?php echo $Pollution_level_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Location</label>
                            <input type="text" name="Location" class="form-control <?php echo (!empty($Location_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Location; ?>">
                            <span class="invalid-feedback"><?php echo $Location_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Date or time</label> <!-- Corrected label -->
                            <input type="text" name="Date_or_time" class="form-control <?php echo (!empty($Date_or_time_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Date_or_time; ?>">
                            <span class="invalid-feedback"><?php echo $Date_or_time_err; ?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>