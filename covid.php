<?php
$jsonurl = "csvjson.json";
$jsondata = file_get_contents($jsonurl);
$noncovid = json_decode($jsondata);
$country=$_GET["country"];
for ($i=0,$j=0; $i < count($noncovid); $i=$i+1)
{
$countries[$i]=$noncovid[$i]->Country;
if(!strcasecmp($noncovid[$i]->Country,$country))
{
     $dates[$j] = date("d-m-Y", strtotime($noncovid[$i]->Date_reported));
        $deaths[$j] = $noncovid[$i]->New_cases;
        $totalcases[$j] = $noncovid[$i]->Cumulative_cases;
        $totaldeaths[$j] = $noncovid[$i]->Cumulative_deaths;
        $todaydeaths[$j] = $noncovid[$i]->New_deaths;
    $j++;
}
}
$countries=array_unique($countries);
sort($countries);
$today=count($dates);

$dataPoints = array();
for ($i=0; $i <count($dates) ; $i++)
{
  array_push($dataPoints,array("labels"=>$dates[$i],"data"=>$deaths[$i]));
} 
?>
<!DOCTYPE HTML>
<html>
<head>
<title>Covid Statistics. Source : WHO</title>
  <meta charset="utf-8">
  <meta name="description" content="Covid Statistics of Every Country and Related Graphs using the WHO Covid Data.">
  <meta name="keywords" content="Covid, Statistics, Graph, WHO, Data, Cases, Deaths, Recovered, New, Total">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>
</head>

<body>
<div class="container pt-5">
 <div class="card">
  <div class="card-header">
  <form class="form-row">
  <div class="col">
  <input id="search" class="form-control" type="text" name="country"
  value="<?php echo $country;?>" placeholder="Type Any Country Name">
   </div>
   <div class="col">
   <button type="button" class="btn btn-success" onclick="getdata()">Show Graph</button>
   </div>
   <div class="col">
   <span class="badge badge-primary">Updated on <?php echo $dates[$today-1];?></span></label>
   </div>
   </form>
  </div>
  <div class="card-body">
  <div class="chart-container">
  <canvas id="myChart" aria-label="Covid Stats" height="350px" role="img"></canvas>
  </div>
  </div>
  <div class="card-footer">
    <div class="row">
    <div class="col-sm">
        Total Cases  - <b class="text-primary"><?php echo $totalcases[$today-1]; ?></b><br>
        Total Deaths - <b class="text-danger"><?php echo $totaldeaths[$today-1]; ?></b><br>
        Data Source : <a href="https://www.who.int/" target="_blank">WHO</a>
    </div>
    <div class="col-sm">
        Max number of new cases: 
        <b class="text-danger"><?php
        $max=max($deaths);
        echo max($deaths);
        $reqval=array_search($max,$deaths);
        ?></b> are identified on: <b class="text-info"><?php echo $dates[$reqval];
        ?></b>.
        <br>
        New cases on <?php echo $dates[$today-1];?>  : <b class="text-primary"><?php echo $deaths[$today-1];?></b>
        <br>
        New Deaths on <?php echo $dates[$today-1];?> : <b class="text-danger"><?php echo $todaydeaths[$today-1];?></b>
    </div> 
    </div>
  </div>
  </div>
 </div>
</body>

<script>
var countries = <?php echo json_encode($countries); ?>;
var dates = <?php echo json_encode($dates); ?>;
var newcases = <?php echo json_encode($deaths); ?>;
$('#search').typeahead({source: countries});

var ctx = document.getElementById('myChart').getContext('2d');
var chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'bar',

    // The data for our dataset
    data: {
        labels: dates,
        datasets: [{
            label: 'New cases per day',
            backgroundColor: 'rgb(38, 179, 236)',
            data: newcases
        },
        {
            label: 'New Cases per day',
            type:'line',
            borderColor: 'rgb(255, 102, 0)',
            data: newcases
        }]
    },

    // Configuration options go here
    options: {
        responsive: true, 
        maintainAspectRatio: false,
        legend:{
            display: false
        },
        title: {
            display: true,
            text: 'Covid Stats of <?php echo $country;?>'
        },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});

</script>
<script>
function getdata() {

	var country = document.getElementById("search").value;
	window.location.href = "http://www.buyprojects.tech/covid.php?country="+country;
}
</script>
</html>