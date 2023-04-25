<!doctype html>
<html lang="ru">
<head><title>Телефонный справочник из Active Directory</title>
<!-- Semantic -->
<link rel="stylesheet" type="text/css" href="semantic/semantic.min.css">
<script src="semantic/jquery-3.1.1.min.js"></script>
<script src="semantic/semantic.js"></script>

<!-- Data tables-->
<link rel="stylesheet" href="datatables/datatables.min.css" type="text/css">
<script type="text/javascript" language="javascript" src="datatables/datatables.min.js"></script>

<!--Datatables для отображения таблицы-->
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
    $('.example_phone').DataTable( {
        "language": {
            "url": "semantic/Russian.json"
        },
        "pageLength": 25,
    } );
} );
		</script>
<!-- Modal windows-->
<script type="text/javascript" language="javascript" src="semantic/components/modal.js"></script>
<link rel="stylesheet" href="semantic/components/modal.css" type="text/css">
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
$('.show-modal').click(function (e) {
  e.preventDefault();

  var modal_id = $(this).attr('data-modal');

  $(modal_id).modal('show');
});
} );
</script>	
</head>
<body>

<div class="ui segment">
<h1 class="ui header">Телефонный справочник из Active Directory</h1>

<?php
$srv = "sdc2.OFFICE"; // заполняем IP адрес или FQDN контроллера домена, например, 192.168.1.1
$srv_domain = "office"; // заполняем суффикс домена, например, example.com
$srv_login = "larionov@office"; // заполняем логин пользователя, имеющего право читать ldap, например, admin@example.com
$srv_password = "qwettewQqQ!q"; // его пароль
$dn = "dc=office"; // заполняем базу, из которой будем читать пользователей, например, ou=Company,dc=example,dc=com

// Фильтр для выгрузки из AD, здесь указываем группу, в которую входят нужные нам пользователи, например, memberof=CN=Spravochnik,OU=Company,DC=example,DC=com
$filter = "(&(objectCategory=user)(!(userAccountControl:1.2.840.113556.1.4.803:=2))(memberof=CN=Spravochnik,OU=Company,DC=example,DC=com))";
$attr = array("cn","mail","title","department","company","telephonenumber","thumbnailphoto","jpegphoto","samaccountname","info","manager");

$dc = ldap_connect($srv);
ldap_set_option($dc, LDAP_OPT_PROTOCOL_VERSION, 3);
ldap_set_option($dc, LDAP_OPT_REFERRALS, 0);

if ($dc) {
	ldap_bind($dc,$srv_login,$srv_password);
	$result = ldap_search($dc,$dn,$filter,$attr);
	$result_entries = ldap_get_entries($dc,$result);
	ldap_unbind($dc);
}

echo ("<table cellpadding='0' cellspacing='0' border='0' class='ui celled striped table example_phone' >
            <thead>
                <tr>
                    <th>ФИО</th>
                    <th>Email</th>
                    <th>Тел</th>
                    <th>Должность</th>
                    <th>Отдел</th>
                    <th>Компания</th>
                    
                    
                </tr>
            </thead>
            <tbody>");


//Фильтруем данные из AD и выгружаем только тех, у кого есть телефон
 for ($i=0;$i<$result_entries['count'];$i++) {
    if (($result_entries[$i]['telephonenumber'][0])) {
        
		$cn = $result_entries[$i]['cn'][0];
        $mail = htmlentities($result_entries[$i]['mail'][0]);
        $title = htmlentities($result_entries[$i]['title'][0]);
        $department = $result_entries[$i]['department'][0];
        $company = $result_entries[$i]['company'][0];
        $telephonenumber = $result_entries[$i]['telephonenumber'][0];
        $thumbnailphoto = $result_entries[$i]['thumbnailphoto'][0];
        $jpegphoto = $result_entries[$i]['jpegphoto'][0];
        $samaccountname = $result_entries[$i]['samaccountname'][0];
        $info = $result_entries[$i]['info'][0];
        $manager = $result_entries[$i]['manager'][0]; 
		
    // Выделяем жирным текстом руководителей, директоров, начальников
    echo ("<tr style='"); if ((strpos($title, 'уководитель'))||(strpos($title, 'иректор')) ||(strpos($title, 'ачальник'))) {echo ("font-weight: bold;'>");} else{ echo ("font-weight: normal;'>");} 
    echo ("
            <td>
            <div class='ui modal' id='item-modal-".$i."'>
            <i class='close icon'></i>
            <div class='header' style='margin-bottom: 20px;'>Информация о сотруднике</div>
            <div class='image content '>");
			if (isset($jpegphoto)) {
				echo ("<img class='ui medium rounded image fluid' src='data:image/jpeg;base64,".base64_encode($jpegphoto)."'/>");
			}
			else {
				echo ("<img class='ui medium rounded image fluid' src='images/no_photo.png'/>");
			}
			      
            echo ("</div>
            <div class='description fluid'>
            <div class='ui message fluid'>
            <div class='header'>
                <h4>".$cn."</h4>
            </div>
            <div class='ui segments fluid'>
                <div class='ui segment'><i class='map marker alternate icon'></i>".$department."</div>
                <div class='ui segment'><i class='address card outline icon'></i>".$title."</div>
                <div class='ui segment'><a href='mailto:".$mail."'><i class='mail icon'></i>".$mail."</a></div>
                <div class='ui segment'><i class='phone icon'></i>".$telephonenumber."</div>
                <div class='ui segment'><i class='street view icon'></i>Непосредственный руководитель:<br><span style='margin-left: 20px; font-weight: bold;'>");
        $oldmanager = $manager; // получаем  манагера
        preg_match_all('#CN=(.+?),OU#is', $oldmanager, $arr); // обрезаем лишнее
        $newmanager = implode(”, $arr[1]); // преобразуем в строку
        $newmanager1 = mb_substr("$newmanager", 0, 1); //разбиваем фамилию, чтобы не искало по руководителю
        $newmanager2 = mb_substr("$newmanager", 1);
        
        echo ("
            ".$newmanager1." ".$newmanager2."</span></div>
            </div>
            </div>
            
            </div>
            </div>
            
            </div>
            
            <a class='ui show-modal' data-modal='#item-modal-".$i."'>".$cn."</a></td>
            <td><i class='envelope outline icon'></i> <a href='mailto:".$mail."'>".$mail."</a></td>
            <td><i class='phone icon'></i> ".$telephonenumber."</td>
            <td>".$title."</td>
            <td>".$department."</td>
            <td>".$company."</td>
            </tr>
            
    "); 
 
        }
    }  
	

echo ("</tbody>
        </table>");

?>
</div>
</body>
</html>	