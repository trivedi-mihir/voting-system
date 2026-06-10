
<?php
session_start();

if (!isset($_SESSION['votes'])) {
    $_SESSION['votes'] = [
        "Candidate A" => 0,
        "Candidate B" => 0,
        "Candidate C" => 0
    ];
}

if (!isset($_SESSION['voted'])) {
    $_SESSION['voted'] = false;
}

$message = "";

// Vote Submission
if (isset($_POST['vote']) && !$_SESSION['voted']) {

    if (isset($_POST['candidate'])) {

        $candidate = $_POST['candidate'];

        $_SESSION['votes'][$candidate]++;

        $_SESSION['voted'] = true;

        $message = "Your vote has been recorded successfully!";
    }
}

// Reset Voting
if (isset($_POST['reset'])) {

    $_SESSION['votes'] = [
        "Candidate A" => 0,
        "Candidate B" => 0,
        "Candidate C" => 0
    ];

    $_SESSION['voted'] = false;

    $message = "Voting system has been reset.";
}

$votes = $_SESSION['votes'];

$totalVotes = array_sum($votes);

$winner = "No Votes Yet";

if ($totalVotes > 0) {
    $winner = array_search(max($votes), $votes);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Online Voting System</title>

    <style>

        body{
            font-family: Arial, sans-serif;
            background:#f4f4f4;
            margin:0;
            padding:20px;
        }

        .container{
            max-width:900px;
            margin:auto;
            background:white;
            padding:25px;
            border-radius:10px;
            box-shadow:0 0 10px rgba(0,0,0,0.1);
        }

        h1,h2{
            text-align:center;
            color:#333;
        }

        .message{
            background:#d4edda;
            color:#155724;
            padding:10px;
            border-radius:5px;
            margin-bottom:15px;
        }

        .candidate{
            border:1px solid #ddd;
            padding:15px;
            margin:10px 0;
            border-radius:8px;
        }

        button{
            padding:10px 20px;
            border:none;
            background:#007bff;
            color:white;
            border-radius:5px;
            cursor:pointer;
        }

        button:hover{
            background:#0056b3;
        }

        .reset{
            background:#dc3545;
        }

        .reset:hover{
            background:#b02a37;
        }

        table{
            width:100%;
            border-collapse:collapse;
            margin-top:20px;
        }

        table th{
            background:#007bff;
            color:white;
            padding:10px;
        }

        table td{
            border:1px solid #ddd;
            padding:10px;
            text-align:center;
        }

        .winner{
            background:#fff3cd;
            padding:15px;
            border-radius:8px;
            margin-top:20px;
            text-align:center;
        }

    </style>
</head>

<body>

<div class="container">

    <h1>Online Voting System</h1>

    <?php
    if($message != ""){
        echo "<div class='message'>$message</div>";
    }
    ?>

    <?php if(!$_SESSION['voted']) { ?>

    <form method="POST">

        <h2>Select Your Candidate</h2>

        <div class="candidate">
            <input type="radio" name="candidate" value="Candidate A" required>
            Candidate A
        </div>

        <div class="candidate">
            <input type="radio" name="candidate" value="Candidate B">
            Candidate B
        </div>

        <div class="candidate">
            <input type="radio" name="candidate" value="Candidate C">
            Candidate C
        </div>

        <button type="submit" name="vote">
            Submit Vote
        </button>

    </form>

    <?php } else { ?>

        <h3 style="color:green;text-align:center;">
            You have already voted.
        </h3>

    <?php } ?>

    <hr>

    <h2>Voting Results</h2>

    <p><strong>Total Votes:</strong> <?php echo $totalVotes; ?></p>

    <table>

        <tr>
            <th>Candidate</th>
            <th>Votes</th>
            <th>Percentage</th>
        </tr>

        <?php

        foreach($votes as $candidate => $count){

            $percentage = ($totalVotes > 0)
                ? ($count / $totalVotes) * 100
                : 0;

            echo "<tr>";
            echo "<td>$candidate</td>";
            echo "<td>$count</td>";
            echo "<td>".number_format($percentage,2)."%</td>";
            echo "</tr>";
        }

        ?>

    </table>

    <div class="winner">

        <h2>Winner</h2>

        <h3>
            <?php echo $winner; ?>
        </h3>

    </div>

    <br>

    <form method="POST">
        <button class="reset" name="reset">
            Reset Voting
        </button>
    </form>

</div>

</body>
</html>
