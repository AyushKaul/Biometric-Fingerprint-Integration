<script>
history.pushState(null, document.title, location.href);
window.addEventListener('popstate', function (event)
{
  history.pushState(null, document.title, location.href);
});
</script>
<?php 

$conn = mysqli_connect("","","","");

session_start();

if(!isset($_SESSION['username']))
{
   header("Location: login.php");
} 



if(!isset($_SESSION['submit']))
{
  $_SESSION['securityquestion1'] = '';
  $_SESSION['securityanswer1'] = '';
  $_SESSION['securityquestion2'] = '';
  $_SESSION['securityanswer2'] = '';
}

$username01 = $_SESSION['username'];
// echo "<script type='text/javascript'>alert('Welcome $username01')</script>";

mysqli_query($conn, $sql1);

$query01 = "SELECT * FROM register WHERE  email_id='$username01'";
$result01 = mysqli_query($conn, $query01); 

if ($result01->num_rows >= 0)
{
  while($row01 = $result01->fetch_assoc()) 
  {
    $fp = $row01["fingerprint"];
  }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>user-login</title>
<link rel="stylesheet" href="../css/register.css">
<link rel="stylesheet" href="../css/bootstrap.min.css">
<link rel="stylesheet" href="../css/AdminLTE.min.css">
<link rel="apple-touch-icon" sizes="57x57" href="../favicons/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="../favicons/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="../favicons/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="../favicons/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="../favicons/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="../favicons/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="../favicons/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="../favicons/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="../favicons/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="../favicons/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="../favicons/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="manifest" href="/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">
<script src="jquery-1.8.2.js"></script>
<script src="mfs100-9.0.2.6.js"></script>

<script language="javascript" type="text/javascript">


        var quality = 60; //(1 to 100) (recommanded minimum 55)
        var timeout = 10; // seconds (minimum=10(recommanded), maximum=60, unlimited=0 )
        var flag = 0;

// Function used to match fingerprint using jason object 

function Match() {

            try {
              //fingerprint stored as isotemplate

                var isotemplate = <?php echo json_encode($fp); ?>;
                var res = MatchFinger(quality, timeout, isotemplate);

                if (res.httpStaus) {
                    if (res.data.Status) {
                        alert("Finger matched");
                        
                        //variable flag used for authentication 
                        
                        flag=1;
                    }
                    else {
                        if (res.data.ErrorCode != "0") {
                            alert(res.data.ErrorDescription);
                        }
                        else {
                            alert("Finger not matched");
                        }
                    }
                }
                else {
                    alert(res.err);
                }
            }
            catch (e) {
                alert(e);
            }
            return false;

        }

//function to redirect to next page upon fingerprint matching

function redirect(){

    
    if(flag){ 
    window.location.assign("url"); 
    }
    else{
      alert("Scan Your Finger");
    }

  return false;
}

</script>

</head>
<body class="mainbody">
  <div class="header">

    <img class="left" src="../favicons/apple-icon-60x60.png" height="40" width="40">
  </div>

    <div class="register_panel">
      <div class="panel panel-primary">
          <div class="panel-heading font"> </div>
          <div class="panel-body">
                <form method = "post" name="myForm" action="#">
                    
                    <div class="hide">
                      <table>
                        <tr>
                          <td>
                              Base64Encoded ISO Image
                          </td>
                          <td>
                             <textarea id="txtIsoTemplate" style="width: 100%; height:50px;" class="form-control"> </textarea>
                          </td>
                        </tr>
                      </table>
                    </div>
                   
                    
                    <div class="finger_print padd fingerpadd" style="border:solid">

                    <div>
                    <figure>
                    <img src="https://www.larsonjewelers.com/Images/larson-jewelers-fingerprint-engraving-ring.png" alt="finger_print" width="100" height="100">
                    </figure>
                    </div>


                    <div>
                      <button type="input" onclick="return Match()" class="btn btn-default padd" >start scanning</button>
                    </div>
                    
                    </div>
                    

                    
                    <div>
                      <button type="submit" onclick="return redirect()" class="btn btn-primary btn-lg  padd submit_buttom_padding btn-block" value="submit" name="submit">Submit</button>
                    </div>
                    

                    </div>
                    </div>
               </form>
          </div>
       </div>
    </div>
</body>
</html>