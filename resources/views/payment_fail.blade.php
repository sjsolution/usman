<!DOCTYPE html>
<html lang="en">
<head>
  <title>Maak Payment Status</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <div class="jumbotron" style="margin-top:10%">
    <h2>Payment Status</h2>
    <br>
    <p>Status : Fail</p>
    <p>Payment Token : {{ $_GET['PaymentToken'] }} </p>
    <p>Payment Id : {{ $_GET['PaymentId']}}</p> 
    <p>PaidOn : {{ $_GET['PaidOn'] }}</p>
  </div>
</div>

</body>
</html>
