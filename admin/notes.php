session_start();

$con = mysqli_connect("localhost","root","");

if (!$con)
    echo('Could not connect: ' . mysqli_error());
else {
    mysqli_select_db($con,"dataleakage" );

    $qry1="SELECT * from register";
    $result1=mysqli_query($con, $qry1);
     
    //leaked data set S
    $Set=["UY","web","article","afh"];
    //$S="dk";
    $p=0.2; // most probable value of p

    $qry5="SELECT * from askkey";
    $result5=mysqli_query($con, $qry5);
    
    $product=[];
    $finalAgents=[];
    while($w1=mysqli_fetch_array($result5)){
        array_push($product,1);
        array_push($finalAgents,$w1["user"]);
    }

    foreach($Set as $S){
        $agents=[];
        $data=[];

        $qry="SELECT * from askkey";
        $result=mysqli_query($con, $qry);
        //all agents and their data
        while($w1=mysqli_fetch_array($result) ){
            if($w1["status"]=="Yes")
            {$currentAgent=$w1["user"];
            if(!in_array($currentAgent,$agents)) {
                array_push($agents, $currentAgent);
                $sub=$w1["filename"];
                $sql=mysqli_query($con,"SELECT * from presentation WHERE subject = '$sub'");
                $w=mysqli_fetch_array($sql);
                $key=$w["subject"];
                array_push($data, $key);
            }}
        }
       
        $num=count($agents);
        //set data as null if obj not present
        for($i =0;$i<count($agents);$i++){
            $myArray = explode(',', $data[$i]);
            if(!in_array($S,$myArray)){
              $data[$i]="";
              $num--; 
            }
        }
        //calc product
        for($i =0;$i<count($agents);$i++) {
            if($data[$i]!==""){
                $product[$i]*=1-(1-$p)/$num;
            }
        }
        print_r($product);
        echo "<br/>";

    }
    $result6 = mysqli_query($con,$sql6) or die ("Could not send data into DB: " . mysqli_error($con));