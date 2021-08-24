<?php
require_once __DIR__ . '/vendor/autoload.php';
use Phpml\Classification\KNearestNeighbors;
use Phpml\Classification\NaiveBayes;
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CEK DIABETES</title>
</head>
<body>
    <h1>INPUT DATA PASIEN BARU</h1>
    <form action="" action="GET">

    Pregnancies: <input type="number" name="pregnancies"><br><br>
    Glucose: <input type="number" name="glucose"><br><br>
    Blood Pressure: <input type="number" name="bloodpres"><br><br>
    SkinThickness: <input type="number" name="skinthick"><br><br>
    Insulin: <input type="number" name="insulin"><br><br>
    BMI: <input type="number" name="bmi"><br><br>
    Diabetes Pedigree: <input type="text" name="diabetes"><br><br>
    Age: <input type="number" name="age"><br><br>

    <p><b>Pilih Metode Klasifikasi</b></p>
    <input type="radio" id="naive-bayes" name="metode" value="naive">
    <label for="naive-bayes">Naive Bayes</label>
    <input type="radio" id="knn" name="metode" value="knn">
    <label for="knn">KNN</label><br><br>
    <input type="submit" name="submit" value="Check">
    </form>
</body> 
<?php 

 $conn = new mysqli("localhost", "root", "", "quiz_nas");
 if ($conn->connect_error)
 {
    die("koneksi gagal: " . $conn->connect_error);
 }
 if(isset($_GET['submit']))
 {
    $labels = array();
    $samples = array();

    $newPreg = $_GET['pregnancies'];
    $newGlucose = $_GET['glucose']; 
    $newBlood = $_GET['bloodpres']; 
    $newSkin = $_GET['skinthick']; 
    $newInsulin = $_GET['insulin']; 
    $newBmi = $_GET['bmi']; 
    $newDiabetes = $_GET['diabetes']; 
    $newAge = $_GET['age']; 
    $metode = $_GET['metode']; 

    // echo "<table border = '1'>";
    // echo "<th align='center'>Pregnancies</th>";
    // echo "<th align='center'>Glucose</th>";
    // echo "<th align='center'>BloodPressure</th>";
    // echo "<th align='center'>SkinThickness</th>";
    // echo "<th align='center'>Insulin</th>";
    // echo "<th align='center'>BMI</th>";
    // echo "<th align='center'>DiabetesPedigree</th>";
    // echo "<th align='center'>Age</th>";
    // echo "<th align='center'>Outcome</th></tr>";

    $i = 0;
    $sql = "SELECT * FROM pasien";
    $result = mysqli_query($conn,$sql);
    if(mysqli_num_rows($result)>0)
    {
        while ($row = mysqli_fetch_assoc($result)) 
        {
            $pregnancies = $row["Pregnancies"];
            $glucose = $row["Glucose"];
            $bloodpres = $row["BloodPressure"];
            $skinthick = $row["SkinThickness"];
            $insulin = $row["Insulin"];
            $bmi = $row["BMI"];
            $diabetes = $row["DiabetesPedigree"];
            $age = $row["Age"];

            $labels[$i] = $row["Outcome"];
            $samples[$i] = [$pregnancies,$glucose,$bloodpres,$skinthick,$insulin,$bmi,$diabetes,$age];

            // echo "<tr><td>".$pregnancies."</td>";
            // echo "<td>".$glucose."</td>";
            // echo "<td>".$bloodpres."</td>";
            // echo "<td>".$skinthick."</td>";
            // echo "<td>".$insulin."</td>";
            // echo "<td>".$bmi."</td>";
            // echo "<td>".$diabetes."</td>";
            // echo "<td>".$age."</td>";
            // echo "<td>".$row["Outcome"]."</td><tr>";
            $i++;
        }
    }
    echo "</table><br>";
    
    $newdata = [$newPreg,$newGlucose,$newBlood,$newSkin,$newInsulin,$newBmi,$newDiabetes,$newAge];
    // print_r($newdata); 
    $hasil = "";
    if($metode == "knn")
    {   
        $K = count($samples)/2;
        $classifier = new KNearestNeighbors($K);
        $classifier->train($samples,$labels);
        $hasil = $classifier->predict($newdata);
    }
    else 
    {    
        $classifier = new NaiveBayes();
        $classifier->train($samples,$labels);
        $hasil = $classifier->predict($newdata);
    }
    echo "<h3>Hasil Prediksi Pasien Baru, Diabetes : ".$hasil. "</h3>"; 
    $sql = 'INSERT INTO pasien(Pregnancies, Glucose, BloodPressure, SkinThickness, Insulin, BMI, DiabetesPedigree, Age, Outcome) VALUES ('.$newPreg.','.$newGlucose.','.$newBlood.','.$newSkin.','.$newInsulin.','.$newBmi.','.$newDiabetes.','.$newAge.',"'.$hasil.'")';
    $result = mysqli_query($conn,$sql);
}
?>