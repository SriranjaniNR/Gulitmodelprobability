<?php 
session_start();

$con = mysqli_connect("localhost","root","");

if (!$con)
    echo('Could not connect: ' . mysqli_error());
else {
    mysqli_select_db($con,"dataleakage" );

    $qry1="SELECT * from leaker";
    $result1=mysqli_query($con, $qry1);
     
    //leaked data set S
    $Set=["UY","web","article","afh"];
    //$S="dk";
    $p=0.2; // most probable value of p

    
    
    $product=[];
    $finalAgents=[];
    while($w1=mysqli_fetch_array($result1)){
        array_push($product,1);
        array_push($finalAgents,$w1["name"]);
    }

    foreach($Set as $S){
        $agents=[];
        $data=[];

        $qry="SELECT * from askkey";
        $result=mysqli_query($con, $qry);
        //all agents and their data
        while($w1=mysqli_fetch_array($result) ){
            if($w1["filename"]==$S){
                if(!in_array($agents,$w1["user"])) {
                    array_push($agents,$w1["user"]);}
            }
            }
        
       
        $num=count($agents);
        //set data as null if obj not present
       
        //calc product
        for($i =0;$i<count($agents);$i++) {
            $key=array_search($agents[$i], $finalAgents);
           
                $product[$key]*=1-(1-$p)/$num;
            
        }
        print_r($product);
        echo "<br/>";

    }
  
    for($i =0;$i<4;$i++){
        $prob=1-$product[$i];
        $sql6 = "UPDATE leaker SET probability='$prob' WHERE name='$finalAgents[$i]' ";
        $result6 = mysqli_query($con,$sql6) or die ("Could not send data into DB: " . mysqli_error($con));
    }
  
    header("Location: leakfile.php");
}
?>