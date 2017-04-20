<?php 

$conn = mysqli_connect("","","","");
session_start();

$msg ="";

if(!isset($_SESSION['submit']))
{
  $_SESSION['exampleInputEmail1'] = '';
  $_SESSION['exampleInputPassword1'] = '';
  $_SESSION['exampleInputPassword2'] = '';
  $_SESSION['securityquestion1'] = '';
  $_SESSION['securityanswer1'] = '';
  $_SESSION['securityquestion2'] = '';
  $_SESSION['securityanswer2'] = '';
  $_SESSION['securityquestion3'] = '';
  $_SESSION['securityanswer3'] = '';
  $_SESSION['securityquestion4'] = '';
  $_SESSION['securityanswer4'] = '';
  $_SESSION['txtIsoTemplate'] = '';
}

define('ENCRYPTION_KEY', 'd0a7e7997b6d5fcd55f4b5c32611b87cd923e88837b63bf2941ef819dc8ca282');
$key = pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");
// Encrypt Function
function encrypt($encrypt, $key)
{
    $encrypt = serialize($encrypt);
    $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC), MCRYPT_DEV_URANDOM);
    //$key = pack('H*', $key);
    $mac = hash_hmac('sha256', $encrypt, substr(bin2hex($key), -32));
    $passcrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $encrypt.$mac, MCRYPT_MODE_CBC, $iv);
    $encoded = base64_encode($passcrypt).'|'.base64_encode($iv);
    return $encoded;
}


if(isset($_POST['submit']))
{
  $exampleInputEmail1 = mysqli_real_escape_string($conn , $_POST['exampleInputEmail1']);
  $exampleInputPassword1 = mysqli_real_escape_string($conn, $_POST['exampleInputPassword1']);
  $exampleInputPassword2 = mysqli_real_escape_string($conn, $_POST['exampleInputPassword2']);
    $txtIsoTemplate = mysqli_real_escape_string($conn, $_POST['txtIsoTemplate']);

 $query = "SELECT * FROM register WHERE email_id='$exampleInputEmail1'";
 $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
 $count = mysqli_num_rows($result);
  if($count < 1)
  {
if ($exampleInputPassword1 == $exampleInputPassword2) 
{

  
  $exampleInputPassword1 = md5($_POST['exampleInputPassword1']);
  $confirm_password = md5($_POST['exampleInputPassword2']);
  $activation = 1;
  $ip=$_SERVER['REMOTE_ADDR'];
  $my_date = date("Y-m-d H:i:s");

$sql = "insert into register(email_id, password , confirm_password ,activation , ip , date , fingerprint) values('$exampleInputEmail1', '$exampleInputPassword1' ,'$confirm_password', '$activation' , '$ip' , '$my_date' , '$txtIsoTemplate');";
mysqli_query($conn, $sql);

$sql1 = "insert into login(email_id, password, activation) values ('$exampleInputEmail1', '$exampleInputPassword1', '$activation');";
mysqli_query($conn, $sql1);
echo "<script type='text/javascript'>alert('Succesfully Registered..!!')</script>";
}
else
{
  echo "<script type='text/javascript'>alert('password not matched..!!')</script>"; 
}
}
else
echo "<script type='text/javascript'>alert('Email_id Already exist..!!')</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>register page</title>
<link rel="stylesheet" href="../css/register.css">
<link rel="stylesheet" href="../css/bootstrap.min.css">
<link rel="stylesheet" href="../css/AdminLTE.min.css">
<script src="jquery-1.8.2.js"></script>
<script src="mfs100-9.0.2.6.js"></script>
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

<script language="javascript" type="text/javascript">

        var flag =0;
        var quality = 60; //(1 to 100) (recommanded minimum 55)
        var timeout = 10; // seconds (minimum=10(recommanded), maximum=60, unlimited=0 )

//function to initialize the device

        function GetInfo() {
            document.getElementById('tdSerial').innerHTML = "";
            document.getElementById('tdCertification').innerHTML = "";
            document.getElementById('tdMake').innerHTML = "";
            document.getElementById('tdModel').innerHTML = "";
            document.getElementById('tdWidth').innerHTML = "";
            document.getElementById('tdHeight').innerHTML = "";
            document.getElementById('tdLocalMac').innerHTML = "";
            document.getElementById('tdLocalIP').innerHTML = "";
            document.getElementById('tdSystemID').innerHTML = "";
            document.getElementById('tdPublicIP').innerHTML = "";


            var key = document.getElementById('txtKey').value;

            var res;
            if (key.length == 0) {
                res = GetMFS100Info();
            }
            else {
                res = GetMFS100KeyInfo(key);
            }

            if (res.httpStaus) {

                document.getElementById('txtStatus').value = "ErrorCode: " + res.data.ErrorCode + " ErrorDescription: " + res.data.ErrorDescription;

                if (res.data.ErrorCode == "0") {
                    document.getElementById('tdSerial').innerHTML = res.data.DeviceInfo.SerialNo;
                    document.getElementById('tdCertification').innerHTML = res.data.DeviceInfo.Certificate;
                    document.getElementById('tdMake').innerHTML = res.data.DeviceInfo.Make;
                    document.getElementById('tdModel').innerHTML = res.data.DeviceInfo.Model;
                    document.getElementById('tdWidth').innerHTML = res.data.DeviceInfo.Width;
                    document.getElementById('tdHeight').innerHTML = res.data.DeviceInfo.Height;
                    document.getElementById('tdLocalMac').innerHTML = res.data.DeviceInfo.LocalMac;
                    document.getElementById('tdLocalIP').innerHTML = res.data.DeviceInfo.LocalIP;
                    document.getElementById('tdSystemID').innerHTML = res.data.DeviceInfo.SystemID;
                    document.getElementById('tdPublicIP').innerHTML = res.data.DeviceInfo.PublicIP;
                }
            }
            else {
                alert(res.err);
            }
            return false;
        }
//function to capture the finger prints. 

        function Capture() {
            try {
                document.getElementById('txtStatus').value = "";
                document.getElementById('imgFinger').src = "data:image/bmp;base64,";
                document.getElementById('txtImageInfo').value = "";
                document.getElementById('txtIsoTemplate').value = "";
                document.getElementById('txtAnsiTemplate').value = "";
                document.getElementById('txtIsoImage').value = "";
                document.getElementById('txtRawData').value = "";
                document.getElementById('txtWsqData').value = "";

                var res = CaptureFinger(quality, timeout);
                if (res.httpStaus) {
                      flag = 1;
                    document.getElementById('txtStatus').value = "ErrorCode: " + res.data.ErrorCode + " ErrorDescription: " + res.data.ErrorDescription;

                    if (res.data.ErrorCode == "0") {
                        document.getElementById('imgFinger').src = "data:image/bmp;base64," + res.data.BitmapData;
                        var imageinfo = "Quality: " + res.data.Quality + " Nfiq: " + res.data.Nfiq + " W(in): " + res.data.InWidth + " H(in): " + res.data.InHeight + " area(in): " + res.data.InArea + " Resolution: " + res.data.Resolution + " GrayScale: " + res.data.GrayScale + " Bpp: " + res.data.Bpp + " WSQCompressRatio: " + res.data.WSQCompressRatio + " WSQInfo: " + res.data.WSQInfo;
                        document.getElementById('txtImageInfo').value = imageinfo;
                        document.getElementById('txtIsoTemplate').value = res.data.IsoTemplate;
                        document.getElementById('txtAnsiTemplate').value = res.data.AnsiTemplate;
                        document.getElementById('txtIsoImage').value = res.data.IsoImage;
                        document.getElementById('txtRawData').value = res.data.RawData;
                        document.getElementById('txtWsqData').value = res.data.WsqImage;
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

        function Verify() {
            try {
                var isotemplate = document.getElementById('txtIsoTemplate').value;
                var res = VerifyFinger(isotemplate, isotemplate);

                if (res.httpStaus) {
                    if (res.data.Status) {
                        alert("Finger matched");
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

        function Match() {
            try {
                var isotemplate = document.getElementById('txtIsoTemplate').value;
                var res = MatchFinger(quality, timeout, isotemplate);

                if (res.httpStaus) {
                    if (res.data.Status) {
                        alert("Finger matched");
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

        function GetPid() {
            try {
                var isoTemplateFMR = document.getElementById('txtIsoTemplate').value;
                var isoImageFIR = document.getElementById('txtIsoImage').value;

                var Biometrics = Array(); // You can add here multiple FMR value
                Biometrics["0"] = new Biometric("FMR", isoTemplateFMR, "UNKNOWN", "", "");

                var res = GetPidData(Biometrics);
                if (res.httpStaus) {
                    if (res.data.ErrorCode != "0") {
                        alert(res.data.ErrorDescription);
                    }
                    else {
                        document.getElementById('txtPid').value = res.data.PidData.Pid
                        document.getElementById('txtSessionKey').value = res.data.PidData.Sessionkey
                        document.getElementById('txtHmac').value = res.data.PidData.Hmac
                        document.getElementById('txtCi').value = res.data.PidData.Ci
                        document.getElementById('txtPidTs').value = res.data.PidData.PidTs
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
        function GetProtoPid() {
            try {
                var isoTemplateFMR = document.getElementById('txtIsoTemplate').value;
                var isoImageFIR = document.getElementById('txtIsoImage').value;

                var Biometrics = Array(); // You can add here multiple FMR value
                Biometrics["0"] = new Biometric("FMR", isoTemplateFMR, "UNKNOWN", "", "");

                var res = GetProtoPidData(Biometrics);
                if (res.httpStaus) {
                    if (res.data.ErrorCode != "0") {
                        alert(res.data.ErrorDescription);
                    }
                    else {
                        document.getElementById('txtPid').value = res.data.PidData.Pid
                        document.getElementById('txtSessionKey').value = res.data.PidData.Sessionkey
                        document.getElementById('txtHmac').value = res.data.PidData.Hmac
                        document.getElementById('txtCi').value = res.data.PidData.Ci
                        document.getElementById('txtPidTs').value = res.data.PidData.PidTs
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
        function GetRbd() {
            try {
                var isoTemplateFMR = document.getElementById('txtIsoTemplate').value;
                var isoImageFIR = document.getElementById('txtIsoImage').value;

                var Biometrics = Array();
                Biometrics["0"] = new Biometric("FMR", isoTemplateFMR, "LEFT_INDEX", 2, 1);
                Biometrics["1"] = new Biometric("FMR", isoTemplateFMR, "LEFT_MIDDLE", 2, 1);
                // Here you can pass upto 10 different-different biometric object.


                var res = GetRbdData(Biometrics);
                if (res.httpStaus) {
                    if (res.data.ErrorCode != "0") {
                        alert(res.data.ErrorDescription);
                    }
                    else {
                        document.getElementById('txtPid').value = res.data.RbdData.Rbd
                        document.getElementById('txtSessionKey').value = res.data.RbdData.Sessionkey
                        document.getElementById('txtHmac').value = res.data.RbdData.Hmac
                        document.getElementById('txtCi').value = res.data.RbdData.Ci
                        document.getElementById('txtPidTs').value = res.data.RbdData.RbdTs
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

        function GetProtoRbd() {
            try {
                var isoTemplateFMR = document.getElementById('txtIsoTemplate').value;
                var isoImageFIR = document.getElementById('txtIsoImage').value;

                var Biometrics = Array();
                Biometrics["0"] = new Biometric("FMR", isoTemplateFMR, "LEFT_INDEX", 2, 1);
                Biometrics["1"] = new Biometric("FMR", isoTemplateFMR, "LEFT_MIDDLE", 2, 1);
                // Here you can pass upto 10 different-different biometric object.


                var res = GetProtoRbdData(Biometrics);
                if (res.httpStaus) {
                    if (res.data.ErrorCode != "0") {
                        alert(res.data.ErrorDescription);
                    }
                    else {
                        document.getElementById('txtPid').value = res.data.RbdData.Rbd
                        document.getElementById('txtSessionKey').value = res.data.RbdData.Sessionkey
                        document.getElementById('txtHmac').value = res.data.RbdData.Hmac
                        document.getElementById('txtCi').value = res.data.RbdData.Ci
                        document.getElementById('txtPidTs').value = res.data.RbdData.RbdTs
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
       
        function validateform()
        {

        var password1=document.myform.exampleInputPassword1;
        var email=document.myform.exampleInputEmail1.value;
        
        if(email.length>20)
        {
            alert("username can be max 25 char");
            return false;
        }

        if (password1.length>8) 
        {
          alert("password should be max 8 char");
          return false;
        }

        // password matching
        if (password1==password2) 
        {
          return true;
        }
        else
         {
            alert("password not matched");
            return false;
         } 

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
        <div class="panel-body margin">
                <form method = "post" action="#" name="myform">
            
            <div class="row">
              <div class="col-md-6">
                  <div class="form-group">
                      <label for="exampleInputEmail1"> Email address</label>
                      <input type="email"  id="exampleInputEmail1" name="exampleInputEmail1" placeholder="Email" class="form-control" autocomplete="off" required >
                 </div>
              </div>
            </div>



        <div class="row">
             <div class="col-md-5">
                <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                   <input pattern="[A-Za-z0-9]+" title="8 characters" placeholder="8 characters" name="exampleInputPassword1" type="password" autocomplete="off" 
                   id="exampleInputPassword1" class="form-control" required />
                 </div>
            </div>
        </div>

          <div class="row">
             <div class="col-md-5">
                 <div class="form-group">
                    <label for="exampleInputPassword2">Confirm Password</label>
                    <input type="password" class="form-control" autocomplete="off" id="exampleInputPassword2" name="exampleInputPassword2" placeholder="Confirm_Password" required>
                 </div>
            </div>
          </div>  

                    <table width="100%" style="padding-top:0px;">
                        <tr>
                            <td width="200px;">
                                <table align="left" border="0" width="100%">
                                    <tr>
                                        <td>
                                            <input type="submit" id="btnCapture" value="Capture" class=" capturebuttonpadding btn btn-primary btn-lg submit_buttom_padding" onclick="return Capture()" />
                                        </td>
                                    </tr>
                  
                                </table>
                            </td>
                            <td width="150px" height="190px" align="center" class="img">
                                <img id="imgFinger" width="145px" height="188px" Falt="Finger Image" class="padd_top" />
                            </td>
                            <td>
                                <table align="left" border="0" style="width:100%; padding-right:20px;">
                    
                                </table>
                            </td>
                        </tr>
                    </table>
    <div class="panel">
        <table width="100%">
            <tr class="hide">
                <td>
                    <input type="text" value="" id="txtStatus" class="form-control hide" />
                </td>
            </tr>
            <tr class="hide">
                <td>
                    Quality:
                </td>
                <td>
                    <input type="text" value="" id="txtImageInfo" class="form-control" />
                </td>
            </tr>
            
            <tr class="hide">
                <td>
                    Base64Encoded ISO Template
                </td>
                <td>
                    <textarea id="txtIsoTemplate" name="txtIsoTemplate" style="width: 100%; height:50px;" value="" class="form-control"> </textarea>
                </td>
            </tr>
            <tr class="hide">
                <td>
                    Base64Encoded ANSI Template
                </td>
                <td>
                    <textarea id="txtAnsiTemplate" style="width: 100%; height:50px;" class="form-control"> </textarea>
                </td>
            </tr>
            <tr class="hide">
                <td>
                    Base64Encoded ISO Image
                </td>
                <td>
                    <textarea id="txtIsoImage" style="width: 100%; height:50px;" class="form-control"> </textarea>
                </td>
            </tr>
            <tr class="hide">
                <td>
                    Base64Encoded Raw Data
                </td>
                <td>
                    <textarea id="txtRawData" style="width: 100%; height:50px;" class="form-control"> </textarea>
                </td>
            </tr>
            <tr class="hide">
                <td>
                    Base64Encoded Wsq Image Data
                </td>
                <td>
                    <textarea id="txtWsqData" style="width: 100%; height:50px;" class="form-control"> </textarea>
                </td>
            </tr>
            <tr class="hide">
                <td>
                    Encrypted Base64Encoded Pid/Rbd
                </td>
                <td>
                    <textarea id="txtPid" style="width: 100%; height:50px;" class="form-control"> </textarea>
                </td>
            </tr>
            <tr class="hide">
                <td>
                    Encrypted Base64Encoded Session Key
                </td>
                <td>
                    <textarea id="txtSessionKey" style="width: 100%; height:50px;" class="form-control"> </textarea>
                </td>
            </tr>
            <tr class="hide">
                <td>
                    Encrypted Base64Encoded Hmac
                </td>
                <td>
                    <input type="text" value="" id="txtHmac" class="form-control" />

                </td>
            </tr>
            <tr class="hide">
                <td>
                    Ci
                </td>
                <td>
                    <input type="text" value="" id="txtCi" class="form-control" />
                </td>
            </tr>
            <tr class="hide">
                <td>
                    Pid/Rbd Ts
                </td>
                <td>
                    <input type="text" value="" id="txtPidTs" class="form-control" />
                </td>
            </tr>
        </table> 
                    <button type="submit" class="btn btn-primary btn-lg submit_buttom_padding" value="submit" onclick=" return validateform()" name="submit" id="sub">Submit</button>
               </form>
          </div>
       </div>
    </div>
</body>
</html>
