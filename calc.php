<?php

header("Content-type:application/json;charset:utf-8");


// validating incoming variables
$error = false;

if ($_POST['sum'] >= 1000 and $_POST['sum'] <= 3000000) {
    $sum = $_POST["sum"];
} else {
    $error = true;

}
        
if ($_POST['percent'] >= 3 and $_POST['percent'] <= 100 and ctype_digit($_POST['percent'])) {
    $percent = $_POST["percent"];
} else {
    $error = true;
}

if ($_POST["mory"] == "month") {
    if ($_POST["term"] >= 1 and $_POST["term"] <= 60) {
        $term = $_POST["term"];
    } else {
        $error = true;
    }
} elseif ($_POST["mory"] == "year") {
    if ($_POST["term"] >= 1 and $_POST["term"] <= 5) {
        $term = $_POST["term"] * 12;
    } else {
        $error = true;
    }
}

$date = DateTime::createFromFormat('d.m.Y', $_POST["startDate"]);
if ($date and $date->format('d.m.Y') === $_POST["startDate"]) {
    $startDate = $_POST["startDate"];
} else {
    $error = true;
}

// stopping execution of php if validation fails
if ($error) {
    die();
}



// setting sumAdd to 0 if it was not specified or checkbox is unticked
if (isset($_POST["monthly"])) {
    if (! isset($_POST["sumAdd"])) {
        $sumAdd = 0;    
    } else {
        if (empty($_POST["sumAdd"])) {
            $sumAdd = 0;
        } else {
            $sumAdd = $_POST["sumAdd"];
        }  
    }
    // $sumAdd = $_POST['sumAdd'] ?: 0;
} else {
    $sumAdd = 0;
}

// converting percents into fraction
$fraction = $percent / 100;

// using for loop to calculate on each consecutive month
$start = $startDate;
$sumN = $sum;
for ($n = 0; $n < $term; $n++) {
    
    // setting end for current month
    $end = date("d.m.Y", strtotime("+1 month, $start"));
    
    // finding daysN in current month
    $st = new DateTime($start);
    $en = new DateTime($end);
    $betw = $st->diff($en);
    $daysN = $betw->days;    

    // setting daysY depending on whether this month lands on a leap year or not
    $year = date("Y", strtotime("$start"));    
    if((0 == $year % 4) & (0 != $year % 100) | (0 == $year % 400)) {
        $daysY = 366;   
    } else {
        $daysY = 365;
    }
    
    // $year = date("L", strtotime("$startDate"));

    // implementing suggested formula with a small fix ;)
    $sumNp = $sumN;
    $sumN = $sumNp + $sumAdd + ($sumNp + $sumAdd) * $daysN * ($fraction / $daysY);
    

    // setting end of current month as start for the next one
    $start = $end;
}

$sumN_rounded = round($sumN, 0);


$result = ["sum" => $sumN_rounded];
echo json_encode($result);
