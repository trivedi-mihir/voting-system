
<?php
session_start();

/* Initialize Votes */
if (!isset($_SESSION['votes'])) {
    $_SESSION['votes'] = [
        "Candidate A" => 0,
        "Candidate B" => 0,
        "Candidate C" => 0
    ];
}

/* Track User Vote */
if (!isset($_SESSION['voted'])) {
    $_SESSION['voted'] = false;
}

/* Vote Submission */
$message = "";

if (isset($_POST['vote']) && !$_SESSION['voted']) {

    $candidate = $_POST['candidate'];

    if (array_key_exists($candidate, $_SESSION['votes'])) {
        $_SESSION['votes'][$candidate]++;
        $_SESSION['voted'] = true;

        $message = "✅ Your vote has been successfully recorded!";
    }
}

/* Reset Voting */
if (isset($_POST['reset'])) {

    $_SESSION['votes'] = [
        "Candidate A" => 0,
        "Candidate B" => 0,
        "Candidate C" => 0
    ];

    $_SESSION['voted'] = false;

    $message = "🔄 Voting system has been reset.";
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
    <title>Professional Online Voting System</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial, sans-serif;
        }

        body{
            background:#f4f7fc;
            padding:30px;
        }

        .container{
            max-width:1000px;
            margin:auto;
        }

        .header{
            text-align:center;
            margin-bottom:30px;
        }

        .header h1{
            color:#2c3e50;
            margin-bottom:10px;
        }

        .header p{
            color:#666;
        }

        .message{
            background:#d4edda;
            color:#155724;
            padding:15px;
            border-radius:8px;
            margin-bottom:20px;
        }

        .vote-section,
        .result-section{
            background:white;
            padding:25px;
            border-radius:15px;
            box-shadow:0 5px 15px rgba(0,0,0,0.1);
            margin-bottom:25px;
        }

        .candidate-grid{
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
            gap:20px;
            margin-top:20px;
        }

        .candidate-card{
            border:2px solid #ddd;
            border-radius:12px;
            padding:20px;
            text-align:center;
            transition:0.3s;
            cursor:pointer;
        }

        .candidate-card:hover{
            border-color:#007bff;
            transform:translateY(-5px);
        }

        .candidate-card input{
            margin-bottom:10px;
        }

        .candidate-card h3{
            color:#333;
            margin-bottom:10px;
        }

        .candidate-card p{
            color:#777;
            font-size:14px;
        }

        .btn{
            width:100%;
            padding:12px;
            border:none;
            border-radius:8px;
            color:white;
            font-size:16px;
            cursor:pointer;
            margin-top:20px;
        }

        .vote-btn{
            background:#007bff;
        }

        .vote-btn:hover{
            background:#0056b3;
        }

        .reset-btn{
            background:#dc3545;
        }

        .reset-btn:hover{
            background:#b52a37;
        }

        .stats{
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
            gap:20px;
            margin-bottom:25px;
        }

        .card{
            background:#eef5ff;
            padding:20px;
            border-radius:10px;
            text-align:center;
        }

        .card h2{
            color:#007bff;
            margin-bottom:10px;
        }

        .winner{
            background:#fff8e1;
            border-left:5px solid #f39c12;
        }

        .progress-container{
            background:#ddd;
            border-radius:30px;
            overflow:hidden;
            margin-top:8px;
            margin-bottom:15px;
        }

        .progress-bar{
            background:#28a745;
            color:white;
            text-align:center;
            padding:6px;
        }

        table{
            width:100%;
            border-collapse:collapse;
            margin-top:20px;
        }

        table th{
            background:#007bff;
            color:white;
            padding:12px;
        }

        table td{
            padding:12px;
            border:1px solid #ddd;
            text-align:center;
        }

        @media(max-width:600px){
            body{
                padding:15px;
            }
        }

    </style>
</head>

<body>

<div class="container">

    <div class="header">
        <h1>🗳 Online Voting System</h1>
        <p>Vote for your favorite candidate</p>
    </div>

    <?php
    if($message != ""){
        echo "<div class='message'>$message</div>";
    }
    ?>

    <div class="vote-section">

        <h2>Cast Your Vote</h2>

        <?php if(!$_SESSION['voted']) { ?>

        <form method="POST">

            <div class="candidate-grid">

                <label class="candidate-card">
                    <input type="radio" name="candidate" value="Candidate A" required>
                    <h3>Candidate A</h3>
                    <p>Technology & Innovation</p>
                </label>

                <label class="candidate-card">
                    <input type="radio" name="candidate" value="Candidate B">
                    <h3>Candidate B</h3>
                    <p>Education & Growth</p>
                </label>

                <label class="candidate-card">
                    <input type="radio" name="candidate" value="Candidate C">
                    <h3>Candidate C</h3>
                    <p>Community Development</p>
                </label>

            </div>

            <button type="submit" name="vote" class="btn vote-btn">
                Submit Vote
            </button>

        </form>

        <?php } else { ?>

            <h3 style="color:green;">
                You have already voted.
            </h3>

        <?php } ?>

    </div>

    <div class="result-section">

        <h2>📊 Voting Results</h2>

        <div class="stats">

            <div class="card">
                <h2><?php echo $totalVotes; ?></h2>
                <p>Total Votes</p>
            </div>

            <div class="card winner">
                <h2>🏆</h2>
                <p><?php echo $winner; ?></p>
            </div>

        </div>

        <?php

        foreach($votes as $candidate => $count){

            $percentage = ($totalVotes > 0)
                ? ($count / $totalVotes) * 100
                : 0;

            ?>

            <h3><?php echo $candidate; ?></h3>

            <p>
                Votes:
                <strong><?php echo $count; ?></strong>
                (<?php echo number_format($percentage,2); ?>%)
            </p>

            <div class="progress-container">
                <div
                    class="progress-bar"
                    style="width:<?php echo $percentage; ?>%">
                    <?php echo number_format($percentage,2); ?>%
                </div>
            </div>

            <?php
        }
        ?>

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

    </div>

    <form method="POST">
        <button type="submit" name="reset" class="btn reset-btn">
            Reset Voting System
        </button>
    </form>

</div>

</body>
</html>
