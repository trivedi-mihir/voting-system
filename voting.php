
<?php
session_start();

$candidates = [
    "Candidate A" => 0,
    "Candidate B" => 0,
    "Candidate C" => 0
];

if (!isset($_SESSION['votes'])) {
    $_SESSION['votes'] = $candidates;
}

if (!isset($_SESSION['voted'])) {
    $_SESSION['voted'] = false;
}

$message = "";

if (isset($_POST['vote']) && !$_SESSION['voted']) {

    $candidate = $_POST['candidate'];

    if (isset($_SESSION['votes'][$candidate])) {
        $_SESSION['votes'][$candidate]++;
        $_SESSION['voted'] = true;
        $message = "✅ Your vote has been successfully recorded.";
    }
}

if (isset($_POST['reset'])) {
    $_SESSION['votes'] = $candidates;
    $_SESSION['voted'] = false;
    $message = "🔄 Voting system reset successfully.";
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
<meta charset="UTF-8">
<title>Government Online Voting Portal</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial,sans-serif;
}

body{
    background:#f4f7fb;
}

header{
    background:#003366;
    color:white;
    text-align:center;
    padding:20px;
}

.container{
    width:90%;
    max-width:1100px;
    margin:20px auto;
}

.card{
    background:white;
    padding:20px;
    border-radius:12px;
    margin-bottom:20px;
    box-shadow:0 2px 10px rgba(0,0,0,.1);
}

.dashboard{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:15px;
}

.stat{
    text-align:center;
    background:#eef4ff;
    padding:20px;
    border-radius:10px;
}

.stat h2{
    color:#003366;
}

.candidates{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:15px;
    margin-top:20px;
}

.candidate{
    border:2px solid #ddd;
    border-radius:10px;
    padding:20px;
    text-align:center;
    transition:.3s;
}

.candidate:hover{
    border-color:#007bff;
    transform:translateY(-5px);
}

button{
    padding:12px;
    border:none;
    border-radius:6px;
    cursor:pointer;
    color:white;
}

.vote-btn{
    width:100%;
    background:#007bff;
    margin-top:15px;
}

.reset-btn{
    background:#dc3545;
    width:100%;
}

.progress{
    background:#ddd;
    border-radius:20px;
    overflow:hidden;
    margin:8px 0 15px;
}

.progress-bar{
    background:#28a745;
    color:white;
    text-align:center;
    padding:5px;
}

.message{
    background:#d4edda;
    color:#155724;
    padding:12px;
    border-radius:6px;
    margin-bottom:15px;
}

table{
    width:100%;
    border-collapse:collapse;
}

table th{
    background:#003366;
    color:white;
}

table th,
table td{
    border:1px solid #ccc;
    padding:10px;
    text-align:center;
}

footer{
    text-align:center;
    padding:15px;
    background:#003366;
    color:white;
    margin-top:20px;
}

</style>
</head>

<body>

<header>
    <h1>🗳 Government Online Voting Portal</h1>
    <p>Digital Election Management System</p>
</header>

<div class="container">

<?php
if($message!=""){
    echo "<div class='message'>$message</div>";
}
?>

<div class="card">

<h2>Election Information</h2>

<p><strong>Election:</strong> Student Representative Election 2026</p>
<p><strong>Status:</strong> Voting Open</p>
<p><strong>Total Candidates:</strong> 3</p>

</div>

<div class="dashboard">

<div class="stat">
    <h2><?php echo $totalVotes; ?></h2>
    <p>Total Votes</p>
</div>

<div class="stat">
    <h2>🏆</h2>
    <p><?php echo $winner; ?></p>
</div>

<div class="stat">
    <h2 id="countdown">60</h2>
    <p>Demo Countdown</p>
</div>

</div>

<div class="card">

<h2>Cast Your Vote</h2>

<?php if(!$_SESSION['voted']) { ?>

<form method="POST">

<div class="candidates">

<label class="candidate">
    <input type="radio" name="candidate" value="Candidate A" required>
    <h3>Candidate A</h3>
    <p>Technology & Innovation</p>
</label>

<label class="candidate">
    <input type="radio" name="candidate" value="Candidate B">
    <h3>Candidate B</h3>
    <p>Education & Development</p>
</label>

<label class="candidate">
    <input type="radio" name="candidate" value="Candidate C">
    <h3>Candidate C</h3>
    <p>Community Growth</p>
</label>

</div>

<button class="vote-btn" name="vote">
    Submit Vote
</button>

</form>

<?php } else { ?>

<h3 style="color:green;">
You have already voted.
</h3>

<?php } ?>

</div>

<div class="card">

<h2>Live Results</h2>

<?php

foreach($votes as $candidate=>$count){

    $percentage = ($totalVotes>0)
        ? ($count/$totalVotes)*100
        : 0;

    echo "<h4>$candidate</h4>";

    echo "<div class='progress'>
            <div class='progress-bar'
                 style='width:$percentage%'>
                 ".number_format($percentage,2)."%
            </div>
          </div>";
}
?>

<table>

<tr>
    <th>Candidate</th>
    <th>Votes</th>
    <th>Percentage</th>
</tr>

<?php

foreach($votes as $candidate=>$count){

    $percentage = ($totalVotes>0)
        ? ($count/$totalVotes)*100
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
    <button class="reset-btn" name="reset">
        Reset Voting System
    </button>
</form>

</div>

<footer>
    Election Commission Portal © 2026
</footer>

<script>

let count = 60;

setInterval(function(){

    if(count > 0){
        count--;
        document.getElementById("countdown").innerHTML = count;
    }

},1000);

</script>

</body>
</html>
