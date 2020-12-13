<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in'])) {
    header('Location: ../login.php');
    exit;
}
require_once('utils/auth.php');
require '../../connect.php';
$id=$_SESSION['user_id'];
$başlangıç=$pdo->prepare("SELECT * FROM users WHERE id = '$id'");
$başlangıç->execute();
$girişler=$başlangıç-> fetchAll(PDO::FETCH_OBJ);
foreach ($girişler as $giriş) {
    $fn = $giriş->firstname;
    $ln = $giriş->lastname;
    $ume = $giriş->username;
    $picture = $giriş->picture;
    $auth_ = $giriş->auth;
}
if ($auth_ == 5 || $auth_ == 7) {
    header('Location: ../403.php');
}

$sql = "SELECT id, title, description, start, end, color FROM events WHERE `user_id` = '$id' ";

$req = $auth->prepare($sql);
$req->execute();

$events = $req->fetchAll();

?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="../assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="../assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <link href='css/fullcalendar.min.css' rel='stylesheet' />
    <title>Takvim</title>
    <link rel="shortcut icon" href="../../images/osgb_amblem.ico"></link>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">

	 <style>
    	#calendar {
    		max-width: %100;
    	}
    	.nocheckbox {
        display: none;
    }

    .label-on {
        border-radius: 3px;
        background: red;
        color: #ffffff;
        padding: 10px;
        border: 1px solid red;
        display: table-cell;
    }

    .label-off {
        border-radius: 3px;
        background: white;
        border: 1px solid red;
        padding: 10px;
        display: table-cell;
    }

    	#calendar a.fc-event {
      color: #fff; /* bootstrap default styles make it black. undo */
      background-color: #0065A6;
    }

    .dropdown {
  position: relative;
  display: inline-block;
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f1f1f1;
  min-width: 160px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
}

.dropdown-content a {
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}

.dropdown-content a:hover {background-color: #ddd;}

.dropdown:hover .dropdown-content {display: block;}

.dropdown:hover .dropbtn {background-color: #3e8e41;}

.navbar a.active {
  background-color: red;
  color: white;
}
  </style>
</head>

<body id="page-top">
  <nav class="navbar shadow navbar-expand mb-3 bg-warning topbar static-top">
    <img width="55" height="40" class="rounded-circle img-profile" src="../assets/img/nav_brand.jpg" />
    <a class="navbar-brand" title="Anasayfa" style="color: black;" href="index.php"><b>Özgür OSGB</b></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span></button>

    <ul class="navbar-nav navbar-expand mr-auto">
      <li class="nav-item">
      <div class="dropdown no-arrow">
        <a style="color:black;" class="nav-link btn btn-warning dropdown-toggle"type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-building"></i><span>&nbsp;İşletmeler</span></a>
            <div class="dropdown-content" aria-labelledby="dropdownMenu2">
              <a class="dropdown-item" type="button" href="../companies.php"><i class="fas fa-stream"></i><span>&nbsp;İşletme Listesi</span></a>
              <a class="dropdown-item" type="button" href="../deleted_companies.php"><i class="fas fa-eraser"></i><span>&nbsp;Silinen İşletmeler</span></a>
              <?php
              if($auth_ == 1){?>
              <a class="dropdown-item" type="button" href="../change_validate.php"><i class="fas fa-exchange-alt"></i><span>&nbsp;Onay Bekleyenler</span></a>
              <?php }?>
            </div>
      </div>
      </li>
      <li class="nav-item">
        <a style="color: black;" class="nav-link btn-warning" href="../reports.php"><i
            class="fas fa-folder"></i><span>&nbsp;Raporlar</span></a>
      </li>
      <li class="nav-item">
          <a style="color: black;" class="nav-link btn-warning" href="../calendar/index.php"><i class="fas fa-calendar-alt"></i><span>&nbsp;Takvim</span></a>
        </li>
      <?php
          if ($auth_ == 1) {
        ?>
      <li class="nav-item"><a style="color: black;" class="nav-link btn-warning" href="../settings.php"><i
            class="fas fa-wrench"></i><span>&nbsp;Ayarlar</span></a></li>
      <li class="nav-item">
      <div class="dropdown no-arrow">
        <button style="color:black;" class="nav-link btn btn-warning dropdown-toggle"type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
            class="fas fa-users"></i><span>&nbsp;Çalışanlar</span></button>
            <div class="dropdown-content" aria-labelledby="dropdownMenu2">
              <a class="dropdown-item" type="button" href="../osgb_users.php"><i class="fas fa-stream"></i><span>&nbsp;Çalışan Listesi</span></a>
              <a class="dropdown-item" type="button" href="../deleted_workers.php"><i class="fas fa-eraser"></i><span>&nbsp;Silinen Çalışanlar</span></a>
              <a class="dropdown-item" type="button" href="../authentication.php"><i class="fas fa-user-edit"></i><span>&nbsp;Yetkilendir</span></a>
            </div>
      </div>
      </li>
      <?php
          }
        ?>
    </ul>
    <ul class="nav navbar-nav navbar-expand flex-nowrap ml-auto">
      <li class="nav-item dropdown no-arrow mx-1">
        <div class="nav-item dropdown no-arrow">
          <?php
            $bildirim_say = $pdo->query("SELECT COUNT(*) FROM `notifications` WHERE `user_id` = '$id' ORDER BY reg_date")->fetchColumn();
                  ?>
        <a href="../notifications.php" title="Bildirimler" class="nav-link"
          data-bs-hover-animate="rubberBand">
          <i style="color: black;" class="fas fa-bell fa-fw"></i>
          <span class="badge badge-danger badge-counter"><?= $bildirim_say ?></span></a>
        </div>
      </li>
      <li class="nav-item dropdown no-arrow mx-1">
        <div class="nav-item dropdown no-arrow">
          <a style="color: black;" title="Mesajlar" href="../messages.php" class="dropdown-toggle nav-link"
            data-bs-hover-animate="rubberBand">
            <i style="color: black;" class="fas fa-envelope fa-fw"></i>
            <?php
              $mesaj_say = $pdo->query("SELECT COUNT(*) FROM `message` WHERE `kime` = '$ume' ORDER BY tarih")->fetchColumn();
                    ?>
            <span class="badge badge-danger badge-counter"><?=$mesaj_say?></span></a>
        </div>
        <div class="shadow dropdown-list dropdown-menu dropdown-menu-right" aria-labelledby="alertsDropdown"></div>
      </li>
      <div class="d-none d-sm-block topbar-divider"></div>
      <li class="nav-item">
        <div class="nav-item">
          <a href="../profile.php" class="nav-link" title="Profil">
            <span style="color:black;" class="d-none d-lg-inline mr-2 text-600"><?=$fn?> <?=$ln?></span><img
              class="rounded-circle img-profile" src="../assets/users/<?=$picture?>"></a>
      </li>
      <div class="d-none d-sm-block topbar-divider"></div>
        <li class="nav-item"><a style="color: black;" title="Çıkış" class="nav-link" href="../logout.php"><i class="fas fa-sign-out-alt"></i><span>&nbsp;Çıkış</span></a></li>
    </ul>
    </div>
  </nav>
    <div class="card shadow-lg">
        <div class="card-header border bg-light">
          <h1 class="text-dark" style="text-align: center; width:%100;"><b>Takvim</b></h1>
        </div>
        <div class="card-body border">
          <div id="calendar" style="margin: auto;" class="col-centered"></div>
        </div>
      </div>
    <div class="modal fade" id="ModalAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
         <form class="form-horizontal" method="POST" action="./core/add-event.php">

    			  <div class="modal-header">
    			  <h4 class="modal-title" id="myModalLabel">Etkinlik Ekle</h4>
    				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    			  </div>
    			  <div class="modal-body">
    				  <div class="form-group">
    					<label for="title" class="col-sm-2 control-label">Başlık</label>
    					<div class="col-sm-10">
    					  <input type="text" name="title" class="form-control" id="title" placeholder="Başlık">
    					</div>
    				  </div>
    				  <div class="form-group">
    					<label for="description" class="col-sm-2 control-label">Açıklama</label>
    					<div class="col-sm-10">
    					  <input type="text" name="description" class="form-control" id="description" placeholder="Açıklama">
    					</div>
    				  </div>
    				  <div class="form-group">
    					<label for="color" class="col-sm-2 control-label">Renk</label>
    					<div class="col-sm-10">
    					  <select name="color" class="form-control" id="color">
    						  <option style="color:#0071c5;" value="#0071c5">Lacivert</option>
    						  <option style="color:#40E0D0;" value="#40E0D0">Turkuaz</option>
    						  <option style="color:#008000;" value="#008000">Yeşil</option>
    						  <option style="color:#FFD700;" value="#FFD700">Sarı</option>
    						  <option style="color:#FF8C00;" value="#FF8C00">Turuncu</option>
    						  <option style="color:#FF0000;" value="#FF0000">Kırmızı</option>
    						  <option style="color:#000;" value="#000">Siyah</option>
    						</select>
    					</div>
    				  </div>
    				  <div class="container">
    				  <div class="row">
    				  <div class="form-group">
    					<label for="start" class="col-sm-12 control-label">Başlangıç Tarihi</label>
    					<div class="col-sm-12">
    					  <input type="text" name="start" class="form-control" id="start" readonly>
    					</div>
    				  </div>
    				  <div class="form-group">
    					<label for="end" class="col-sm-12 control-label">Sonlanma Tarihi</label>
    					<div class="col-sm-12">
    					  <input type="text" name="end" class="form-control" id="end" readonly>
                <input type="number" name="user_id" class="form-control" id="user_id" value="<?= $id ?>" hidden readonly>

    					</div>
    				  </div>
    				</div>
    				</div>
    			  </div>
    			  <div class="modal-footer">
    				<button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
    				<button type="submit" class="btn btn-primary">Kaydet</button>
    			  </div>
    			</form>
        </div>
      </div>
    </div>

    <div class="modal fade" id="ModalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
    			<form class="form-horizontal" method="POST" action="./core/editEventTitle.php">
    			  <div class="modal-header">
    			  <h4 class="modal-title" id="myModalLabel">Etkinliği Düzenle</h4>
    				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    			  </div>
    			  <div class="modal-body">

    				  <div class="form-group">
    					<label for="title" class="col-sm-2 control-label">Başlık</label>
    					<div class="col-sm-10">
    					  <input type="text" name="title" class="form-control" id="title" placeholder="Başlık">
    					</div>
    				  </div>
    				  <div class="form-group">
    					<label for="description" class="col-sm-2 control-label">Açıklama</label>
    					<div class="col-sm-10">
    					  <input type="text" name="description" class="form-control" id="description" placeholder="Açıklama">
    					</div>
    				  </div>
    				  <div class="form-group">
    					<label for="color" class="col-sm-2 control-label">Renk</label>
    					<div class="col-sm-10">
    					  <select name="color" class="form-control" id="color">
                  <option style="color:#0071c5;" value="#0071c5">Lacivert</option>
    						  <option style="color:#40E0D0;" value="#40E0D0">Turkuaz</option>
    						  <option style="color:#008000;" value="#008000">Yeşil</option>
    						  <option style="color:#FFD700;" value="#FFD700">Sarı</option>
    						  <option style="color:#FF8C00;" value="#FF8C00">Turuncu</option>
    						  <option style="color:#FF0000;" value="#FF0000">Kırmızı</option>
    						  <option style="color:#000;" value="#000">Siyah</option>

    						</select>
    					</div>
    				  </div>
    				    <div class="form-group">
    						<div class="col-sm-2">
    						  <label onclick="toggleCheck('check1');" class="label-off" for="check1" id="check1_label">
    						  Sil
    						</label>
    						<input class="nocheckbox" type="checkbox" id="check1" name="delete">
    						</div>
    					</div>
    					<script>
      					function toggleCheck(check) {
      						if ($('#'+check).is(':checked')) {
      							$('#'+check+'_label').removeClass('label-on');
      							$('#'+check+'_label').addClass('label-off');
      						} else {
      							$('#'+check+'_label').addClass('label-on');
      							$('#'+check+'_label').removeClass('label-off');
      						}
      					}
      				</script>
    				  <input type="hidden" name="id" class="form-control" id="id">
    			  </div>
    			  <div class="modal-footer">
    				<button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
    				<button type="submit" class="btn btn-primary">Kaydet</button>
    			  </div>
    			</form>
			</div>
		  </div>
		</div>

    <footer class="bg-white sticky-footer">
        <div class="container my-auto">
            <div class="text-center my-auto copyright"><span>Copyright © ÖzgürOSGB 2020</span></div>
        </div>
    </footer>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js'></script>
    <script src='js/moment.min.js'></script>
    <script
      src="https://code.jquery.com/jquery-1.9.1.min.js"
      integrity="sha256-wS9gmOZBqsqWxgIVgA8Y9WcQOa7PgSIX+rPA0VL2rbQ="
      crossorigin="anonymous">
    </script>
    <script src='js/fullcalendar.min.js'></script>
    <script src='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js'></script>
    <script>
     $(function() {
      $('#calendar').fullCalendar({
        monthNames: ['Ocak','Şubat','Mart','Nisan','Mayıs','Haziran','Temmuz','Ağustos','Eylül','Ekim','Kasım','Aralık'],
        monthNamesShort: ['Ocak','Şubat','Mart','Nisan','Mayıs','Haziran','Temmuz','Ağustos','Eylül','Ekim','Kasım','Aralık'],
        dayNames: ['Pazar','Pazartesi','Salı','Çarşamba','Perşembe','Cuma','Cumartesi'],
        dayNamesShort: ['Pazar','Pazartesi','Salı','Çarşamba','Perşembe','Cuma','Cumartesi'],
        editable:true,
        buttonText: {
              today:    'Bugün',
              month:    'Ay',
              week:     'Hafta',
              day:      'Gün',
              list:     'Liste',
              listMonth: 'Aylık Liste',
              listYear: 'Yıllık Liste',
              listWeek: 'Haftalık Liste',
              listDay: 'Günlük Liste'
      },
        header: {
          left: 'prev,next,today',
          center: 'title',
          right: 'month,agendaWeek,agendaDay,listWeek'
        },
        height: 590,
        businessHours: {
          dow: [ 1, 2, 3, 4, 5 ],

          start: '8:00',
          end: '17:00',
        },
        nowIndicator: true,
        scrollTime: '08:00:00',
        editable: true,
        navLinks: true,
        eventLimit: true, // allow "more" link when there are too many events
        selectable: true,
        selectHelper: true,
        select: function(start, end) {

          $('#ModalAdd #start').val(moment(start).format('YYYY-MM-DD HH:mm:ss'));
          $('#ModalAdd #end').val(moment(end).format('YYYY-MM-DD HH:mm:ss'));
          $('#ModalAdd').modal('show');
        },
        eventAfterRender: function(eventObj, $el) {
          var request = new XMLHttpRequest();
          request.open('GET', '', true);
          request.onload = function () {
            $el.popover({
              title: eventObj.title,
              content: eventObj.description,
              trigger: 'hover',
              placement: 'top',
              container: 'body'
            });
          }
        request.send();
        },

        eventRender: function(event, element) {
          element.bind('click', function() {
            $('#ModalEdit #id').val(event.id);
            $('#ModalEdit #title').val(event.title);
            $('#ModalEdit #description').val(event.description);
            $('#ModalEdit #color').val(event.color);
            $('#ModalEdit').modal('show');
          });
        },
        eventDrop: function(event, delta, revertFunc) { // si changement de position

          edit(event);

        },
        eventResize: function(event,dayDelta,minuteDelta,revertFunc) { // si changement de longueur

          edit(event);

        },
        events: [
        <?php foreach ($events as $event):

          $start = explode(" ", $event['start']);
          $end = explode(" ", $event['end']);
          if ($start[1] == '00:00:00') {
              $start = $start[0];
          } else {
              $start = $event['start'];
          }
          if ($end[1] == '00:00:00') {
              $end = $end[0];
          } else {
              $end = $event['end'];
          }
        ?>
          {
            id: '<?php echo $event['id']; ?>',
            title: '<?php echo $event['title']; ?>',
            description: '<?php echo $event['description']; ?>',
            start: '<?php echo $start; ?>',
            end: '<?php echo $end; ?>',
            color: '<?php echo $event['color']; ?>',
          },
        <?php endforeach; ?>
        ]
      });

      function edit(event){
        start = event.start.format('YYYY-MM-DD HH:mm:ss');
        if(event.end){
          end = event.end.format('YYYY-MM-DD HH:mm:ss');
        }else{
          end = start;
        }

        id =  event.id;

        Event = [];
        Event[0] = id;
        Event[1] = start;
        Event[2] = end;

        $.ajax({
         url: './core/edit-date.php',
         type: "POST",
         data: {Event:Event},
         success: function(rep) {
            if(rep == 'OK'){
              alert('Saved');
            }else{
              alert('Could not be saved. try again.');
            }
          }
        });
      }

    });</script>
    <script src="../assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="../assets/js/chart.min.js"></script>
    <script src="../assets/js/bs-init.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js"></script>
    <script src="../assets/js/theme.js"></script>

</body>

</html>
