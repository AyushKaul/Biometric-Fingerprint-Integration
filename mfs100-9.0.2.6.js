var uri = "https://localhost:8003/mfs100/";  //Secure
//var uri = "http://localhost:8004/mfs100/"; //Non-Secure

var KeyFlag = "";
var isGetSuccess = false;

function GetMFS100Info() {
    KeyFlag = "";
    return GetMFS100Client("info");
}

function GetMFS100KeyInfo(key) {
    KeyFlag = key;
    if (!PrepareScanner())
    {
        return getFalseRes();
    }
    var MFS100Request = {
        "Key": key,
    };
    var jsondata = JSON.stringify(MFS100Request);
    return PostMFS100Client("keyinfo", jsondata);
}
function CaptureFinger(quality, timeout) {
    if (!PrepareScanner()) {
        return getFalseRes();
    }
    var MFS100Request = {
        "Quality": quality,
        "TimeOut": timeout
    };
    var jsondata = JSON.stringify(MFS100Request);
    return PostMFS100Client("capture", jsondata);
}
function VerifyFinger(ProbFMR, GalleryFMR) {
    if (!PrepareScanner()) {
        return getFalseRes();
    }
    var MFS100Request = {
        "ProbTemplate": ProbFMR,
        "GalleryTemplate": GalleryFMR,
        "BioType": "FMR" // you can paas here BioType as "ANSI" if you are using ANSI Template
    };
    var jsondata = JSON.stringify(MFS100Request);
    return PostMFS100Client("verify", jsondata);
}
function MatchFinger(quality, timeout, GalleryFMR) {
    if (!PrepareScanner()) {
        return getFalseRes();
    }
    var MFS100Request = {
        "Quality": quality,
        "TimeOut": timeout,
        "GalleryTemplate": GalleryFMR,
        "BioType": "FMR" // you can paas here BioType as "ANSI" if you are using ANSI Template
    };
    var jsondata = JSON.stringify(MFS100Request);
    return PostMFS100Client("match", jsondata);
}
function GetPidData(BiometricArray) {
    if (!PrepareScanner()) {
        return getFalseRes();
    }
    var req = new MFS100Request(BiometricArray);
    var jsondata = JSON.stringify(req);
    return PostMFS100Client("getpiddata", jsondata);
}
function GetProtoPidData(BiometricArray) {
    if (!PrepareScanner()) {
        return getFalseRes();
    }
    var req = new MFS100Request(BiometricArray);
    var jsondata = JSON.stringify(req);
    return PostMFS100Client("getppiddata", jsondata);
}
function GetRbdData(BiometricArray) {
    if (!PrepareScanner()) {
        return getFalseRes();
    }
    var req = new MFS100Request(BiometricArray);
    var jsondata = JSON.stringify(req);
    return PostMFS100Client("getrbddata", jsondata);
}
function GetProtoRbdData(BiometricArray) {
    if (!PrepareScanner()) {
        return getFalseRes();
    }
    var req = new MFS100Request(BiometricArray);
    var jsondata = JSON.stringify(req);
    return PostMFS100Client("getprbddata", jsondata);
}

function PostMFS100Client(method, jsonData) {
    var res;
    $.support.cors = true;
    var httpStaus = false;
    $.ajax({
        type: "POST",
        async: false,
        crossDomain: true,
        url: uri + method,
        contentType: "application/json; charset=utf-8",
        data: jsonData,
        dataType: "json",
        processData: false,
        success: function (data) {
            httpStaus = true;
            res = { httpStaus: httpStaus, data: data };
        },
        error: function (jqXHR, ajaxOptions, thrownError) {
            res = { httpStaus: httpStaus, err: getHttpError(jqXHR) };
        },
    });
    return res;
}
function GetMFS100Client(method) {
    var res;
    $.support.cors = true;
    var httpStaus = false;
    $.ajax({
        type: "GET",
        async: false,
        crossDomain: true,
        url: uri + method,
        contentType: "application/json; charset=utf-8",
        processData: false,
        success: function (data) {
            httpStaus = true;
            res = { httpStaus: httpStaus, data: data };
        },
        error: function (jqXHR, ajaxOptions, thrownError) {
            res = { httpStaus: httpStaus, err: getHttpError(jqXHR) };
        },
    });
    return res;
}
function getHttpError(jqXHR) {
    var err = "Unhandled Exception";
    if (jqXHR.status === 0) {
        err = 'Service Unavailable';
    } else if (jqXHR.status == 404) {
        err = 'Requested page not found';
    } else if (jqXHR.status == 500) {
        err = 'Internal Server Error';
    } else if (thrownError === 'parsererror') {
        err = 'Requested JSON parse failed';
    } else if (thrownError === 'timeout') {
        err = 'Time out error';
    } else if (thrownError === 'abort') {
        err = 'Ajax request aborted';
    } else {
        err = 'Unhandled Error';
    }
    return err;
}


/////////// Classes

function Biometric(BioType, BiometricData, Pos, Nfiq, Na) {
    this.BioType = BioType;
    this.BiometricData = BiometricData;
    this.Pos = Pos;
    this.Nfiq = Nfiq;
    this.Na = Na;
}

function MFS100Request(BiometricArray) {
    this.Biometrics = BiometricArray;
}

function PrepareScanner() {
    try
    {
        if (!isGetSuccess) {
            var resInfo = GetMFS100Client("info");
            if (!resInfo.httpStaus) {
                //alert(resInfo.err);
                return false;
            }
            else {
                isGetSuccess = true;
            }
           
            if (KeyFlag !=  null && KeyFlag != 'undefined' && KeyFlag.length > 0) {
                var MFS100Request = {
                    "Key": KeyFlag,
                };
                var jsondata = JSON.stringify(MFS100Request);
                PostMFS100Client("keyinfo", jsondata);
            }
        }
    }
    catch(e) {}
    return true;
}
function getFalseRes()
{
    var res;
    res = { httpStaus: false, err: "Error while calling service" };
    return res;
}