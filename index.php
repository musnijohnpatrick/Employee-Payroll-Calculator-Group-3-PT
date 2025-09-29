<?php

$employee_name = '';
$hoursWorked_input = '';
$RatePerHour_input = '';
$errors = [];
$basicPay = 0.0;
$overtime_hours = 0;
$overtime_pay = 0.0;
$total_pay = 0.0;
$regularHours = 0;
$regularPay = 0.0;
$calculated = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	$employee_name  = trim($_POST['employee_name'] ?? '');
	$hoursWorked_input = $_POST['hours_worked'] ?? '';
   	$RatePerHour_input  = $_POST['hourly_rate'] ?? '';
    $hours_worked = is_numeric($hoursWorked_input) ? (float)$hoursWorked_input : null;
    $hourlyRate = is_numeric($RatePerHour_input) ? (float)$RatePerHour_input : null;

    if ($employee_name === '' || $hours_worked === null || $hours_worked <= 0 || $hourlyRate === null || $hourlyRate <= 0) {
        $errors[] = 'Please fill all fields with valid values.';
        
    } else {
        $basicPay = $hours_worked * $hourlyRate;

        if ($hours_worked > 40) {
            $regularHours = 40;
            $regularPay = $regularHours * $hourlyRate;
            $overtime_hours = $hours_worked - 40;
            $overtime_pay = $overtime_hours * ($hourlyRate * 1.5);
            $total_pay = (40 * $hourlyRate) + $overtime_pay;
        } else {
          
            $total_pay = $basicPay;
        }
        $calculated = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Employee Payroll Calculator</title>
    
</head>
<body>
    <h1>Employee Payroll Calculator</h1>

    <?php if ($calculated === false) { ?>
        <?php if (!empty($errors)) { ?>
            <p><?php echo htmlspecialchars($errors[0]); ?></p>
        <?php } ?>
        <form method="post" action="">
            <p>
                <label for="employee_name">Employee Name</label>
                <input type="text" id="employee_name" name="employee_name" placeholder="Enter Employee Name" value="<?php echo htmlspecialchars($employee_name); ?>" required />
            </p>
            <p>
                <label for="hours_worked">Hours Worked</label>
                <input type="number" id="hours_worked" name="hours_worked" placeholder="Enter Hours Worked" step="0.01" min="0" value="<?php echo htmlspecialchars($hoursWorked_input); ?>" required />
            </p>
            <p>
                <label for="hourly_rate">Hourly Rate (₱)</label>
                <input type="number" id="hourly_rate" name="hourly_rate" placeholder="Enter Hourly Rate" step="0.01" min="0" value="<?php echo htmlspecialchars($RatePerHour_input); ?>" required />
            </p>
            <p><input type="submit" value="Calculate Salary" /></p>
        </form>


    <?php } else { ?>
        <h2>Payroll Calculator Complete</h2>
		
        <h3>Employee details</h3>
		<br>

        <strong>Employee Name:</strong> <br>
        <?php echo htmlspecialchars($employee_name); ?> <br>

        <strong>Hour/s Worked:</strong>
        <div><?php echo number_format((float)$hours_worked); ?> hour/s</div>

        <strong>Hourly Rate:</strong>
        <div>₱<?php echo number_format((float)$hourlyRate, 2); ?></div>
		
        <?php if ($hours_worked > 40) { ?>
            <div><strong>Regular Hour/s:</strong></div>
            <div><?php echo number_format((float)$regularHours); ?> hour/s</div>

            <div><strong>Overtime Hour/s:</strong></div>
            <div><?php echo number_format((float)$overtime_hours); ?> hour/s</div>

            <div><strong>Regular Pay:</strong></div>
            <div>₱<?php echo number_format((float)$regularPay, 2); ?></div>

            <div><strong>Overtime Pay:</strong></div>
            <div>₱<?php echo number_format((float)$overtime_pay, 2); ?></div>
        <?php } ?>


        <hr />
        <div><strong>Total Pay:</strong></div>
        <div><strong>₱<?php echo number_format((float)$total_pay, 2); ?></strong></div>
        <p><a href="">Calculate Again</a></p>
    <?php } ?>
</body>
</html>


