<?php

require __DIR__.'/php/connection.php';
require_once 'php/utils.php';
session_start();

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("Location: login.php");
  exit;
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <base href="<?php echo dirname($_SERVER['PHP_SELF']); ?>/" />
    <meta charset="UTF-8" />
  	<meta name="csrf_token" content="<?php echo createToken(); ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon">  
    <title>IQGS</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
      rel="stylesheet"
    />
    <!-- icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    <!--  dataTables-->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/jq-3.6.0/jszip-2.5.0/dt-1.11.3/b-2.0.1/b-colvis-2.0.1/b-html5-2.0.1/b-print-2.0.1/r-2.2.9/datatables.min.css"/>
    
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/jq-3.6.0/jszip-2.5.0/dt-1.11.3/b-2.0.1/b-colvis-2.0.1/b-html5-2.0.1/b-print-2.0.1/r-2.2.9/datatables.min.js"></script>
    <!-- xlsx -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.2/xlsx.full.min.js"></script>
    <!-- styles -->
    <link rel="stylesheet" href="./css/globalStyle.css" />
    <link rel="stylesheet" href="./css/topBar.css" />
    <link rel="stylesheet" href="./css/datatable.css" />
    <link rel="stylesheet" href="./css/form.css" />
  </head>
  <body>
    <!-- ------------------------------------------------------------------------------- -->
    <div class="topbar">
      <div class="topbarWrapper">
        <div class="topLeft">
          <span class="logo">IRAQ GAUGING STATIONS</span>
        </div>
        <div class="topRight">
          <div class="topbarIconContainer">
            <div class="logout-btn" onclick="logout();">Log Out</div>
          </div>
        </div>
      </div>
    </div>
    <!-- ------------------------------------------------------------------------------- -->
    <div class="table-container">
      <div class="MainDiv">
        <table
          id="IGS-table"
          class="table table-striped table-bordered table-sm"
          width="100%"
        >
          <thead class="table-header">
            <tr>
              <th>Station</th>
              <th>TDS</th>
              <th>TUR</th>
              <th>PH</th>
              <th>LEVEL</th>
              <th>DATE</th>
              <th>TIME</th>
              <th class ='nosort'>ACTIONS</th>
            </tr>
          </thead>
        </table>

        <div id='modal-delete-id' class="modal">
          <div class="modal-content">
            <div class="popupContainer">
              <h1>Delete Row</h1>
              <p>Are you sure you want to delete?</p>
              <div class="clearfix">
                <button type="button" class="cancelbtn btn btn-secondary btn-lg" onclick="document.getElementById('modal-delete-id').style.display='none'" >Cancel</button>
                <button type="button"  class="deletebtn btn btn-danger btn-lg" onclick="confirmationDeleteButton()">Delete</button>
              </div>
            </div>
          </div>
        </div>  

        <div id='modal-edit-id' class="modal-edit">
          <div class="modal-content-edit">
            <div class="popupContainer-edit">
              <h1 class='edit-title'>EDIT</h1>
              <form class = 'row g-3' action="">
                <div class="input-1 col-12">
                  <label class="form-label">Station</label>
                  <input id='station-input' type="text" class="form-control station" value=''/>
                </div>
                <div class="input-2 col-12">
                  <label class="form-label" >TDS</label>
                  <input id='tds-input' type="text" class="form-control tds" value=''/>
                </div>
                <div class="input-3 col-12">
                  <label class="form-label">TUR</label>
                  <input id='tur-input' type="text" class="form-control tur" value=''/>
                </div>
                <div class="input-4 col-12">
                  <label class="form-label">PH</label>
                  <input id='ph-input' type="text" class="form-control ph" value='' />
                </div>
                <div class="input-5 col-12">
                  <label class="form-label">LEVEL</label>
                  <input id='level-input' type="text" class="form-control level" value=''/>
                </div>
                <div class="input-6 col-12">
                  <label class="form-label">DATE</label>
                  <input id='date-input' type="text" class="form-control date" value=''/>
                </div>
                <div class="input-7 col-12">
                  <label class="form-label">TIME</label>
                  <input id='time-input' type="text" class="form-control time" value=''/>
                </div>
                <div class="clearfix-edit">
                  <button type="button" class="cancelbtn btn btn-secondary btn-lg" onclick="document.getElementById('modal-edit-id').style.display='none'" >Cancel</button>
                  <button type="button"  class="deletebtn btn btn-success btn-lg" onclick="confirmationEditButton()">Edit</button>
                </div>
              </form>
              
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- ------------------------------------------------------------------------------- -->
    <div class="formContainer">
      <div class="form-1">
        <form action="">
          <label id = 'importLabel'class="form-label" for="customFile">Import Excel File</label>
          <input type="file" class="form-control" id="fileInput"  />
        </form>
      </div>
    </div>
    <!-- Messages log -->
    <div id='import-log' class="modal-import">
      <div class="modal-content-import">
        <div class="popupContainer-import">
          <h1>Done</h1>
          <p>Your Excel file has been imported</p>
        </div>
      </div>
    </div>

    <div id='delete-log' class="modal-delete-log">
      <div class="modal-content-delete-log">
        <div class="popupContainer-delete-log">
          <h1>Done</h1>
          <p>Your record has been deleted</p>
        </div>
      </div>
    </div>

    <div id='edit-log' class="modal-edit-log">
      <div class="modal-content-edit-log">
        <div class="popupContainer-edit-log">
          <h1>Done</h1>
          <p>Your record has been updated</p>
        </div>
      </div>
    </div>

    <div id='fail-log' class="modal-fail-log">
      <div class="modal-content-fail-log">
        <div class="popupContainer-fail-log">
          <h1 id ='oops-title'>Oops :(</h1>
          <p>something went wrong</p>
        </div>
      </div>
    </div>
              
    <!-- ------------------------------------------------------------------------------- -->
    <script src="./js/dataTable.js"></script>
    <script src="./js/form.js"></script>
    <script src="./js/account.js"></script>

  </body>
  <link rel="stylesheet" href="./css/overRide.css" />

</html>
