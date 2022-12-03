
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">



<?php
///
$host       = "localhost";
$username   = "iot";
$password   ="";
$database   ="iot";
///
$koneksi = mysqli_connect($host, $user, $password, $database);
?>

<center>
    <h2>DATA SUHU</h2>
</center>

<table border=="1" class="table table-striped">
    <thead class="thead-dark">
        <tr><th>TANGGAL</th><th> SUHU </th> <th> KETERANGAN </th> </tr>
    </thead>
    <?php
    $data = mysqli_query($koneksi, "SELECT * from suhu");
    $no=1;
    foreach ($data as $row){
        echo "<tr>";
        if ($row['suhu'] > 30) $ket="PANAS"; else $ket="NORMAL";
        echo "
                <td>".$row['jam']."</td>
                <td>".$row['suhu']."</td>
                <td>".$ket."</td>
                </tr>";
                $no++;

                $jamx[]=@$row['jam'];
                $jamx[]=@$row['suhu'];
    } 

    $jam_json=json_encode($jamx); $suhu_json=json_encode($suhux);
    ?>
</table>

<div>
    <canvas id="myChart"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('MyChart');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?=$jam_json;?>,
            datasets: [{
                label: '# SUHU'
        data: <?=$suhu_json;?>
        borderWidth: 1
            }]
        },
        options: {
            scales:{
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    </script>
