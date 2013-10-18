<?php 
/*
CLASS Base:

PPOPERTIES: // all properties except idb and name are private, use output to get them
  idb
  name
  info
  notifications
  bookings
  schedules
  users
  photos
  equipment
    roomNames

GENERAL METHODS:
  isOwner() output true/false
 
GET METHODS: all get methods are private, use output methods instead EXCEPT getInfo
  getInfo()  
  getNotifications()  
  getBookings()  
  getSchedules() 
  getUsers() 
  getPhotos() 
  getEquipment() 


OUTPUT METHODS: all methods are public
  outputInfo()  
  outputNotifications()  
  outputNotificationCount()
  outputBookings()   

  outputSchedules() 
  outputUsers() 
  outputPhotos() 
  outputEquipment() 
    outputRoomNames() 
  outputTime()

UPDATE METHODS:
  updateInfo()  1
    updateConditions()  1
  updateNotifications()  mark as seen && delete older than 2 months? 
    notify($type,$room,$start,$end,$date,$vkid)  1
  updateBookings()  
    deleteBooking($data)
  updateSchedules() ?
  updateUsers() ?
  updatePhotos() ?
  updateEquipment()

  updatePassword()
  updateBaseName()
  deleteBase()

*/
Class Base {

  /*  PROPERTIES  */
  public $idb = 0;
  public $name = null;
  private $info = null;
  private $notifications = null;
  private $bookings = null;
  private $schedules = null;
  private $users = null;
  private $photos = null;
  private $equipment = null;
  /*  PROPERTIES  */


  /*  CONSTRUCTOR  

    it gets business id, 
    sets it to this->idb 

    returns false if there was mysql query error or business id DNE
  */

  public function __construct($idb,$name,$pid) {
    $idb = intval($idb);
    $pid = intval($pid);
    $name = check($name);

    if ($name != "") {
      $r = mysql_query("SELECT id, name FROM bases WHERE name='$name'");
    } else if ($pid != 0) {
      $r = mysql_query("SELECT id, name FROM bases WHERE pid='$pid'");
    } else if ($idb != 0) {
       $r = mysql_query("SELECT id, name FROM bases WHERE id='$idb'");
    }
    
    if (!$r) {
      return false;
    }

    $r = mysql_fetch_array($r);

    if (isset($r['name'])) {
      $this->idb = $r['id'];
      $this->name = $r['name'];
    } else {
      return false;
    }

    return true;
  }
  /*  CONSTRUCTOR  */

  public function isOwner() {
    $this->getInfo();
    $this->info['pid'] = intval($this->info['pid']);
    if (isset($_SESSION['login']) && $_SESSION['login'] != "" && $_SESSION['pid'] == $this->info['pid']) {
      $this->info['active'] = 1;
      return true;
    } else {
      if ($this->info['pid'] != 0) {
        unset($this->info['pid']);
        $this->info['active'] = 1;
      } else {
        unset($this->info['pid']);
        $this->info['active'] = 0;
      }
      return false;
    }
  }
  

  /* getInfo() gets basic info and stores it into this->info

  */
  public function getInfo() {
    $info = array();
    $query = "SELECT * FROM bases WHERE id=".$this->idb;
    $r = mysql_query($query);
    if ($r == false ){ 
      return false;
    }  

    $r = mysql_fetch_array($r);
    $info['idb'] = clears($r['id']);
    $info['name'] = clears($r['name']);
    $info['type'] = clears($r['type']);
    $info['komn'] = clears($r['komn']);
    $info['description'] = clears($r['descript']);
    $info['town'] = clears($r['town']);
    $info['station'] = clears($r['station']);
    $info['address'] = clears($r['adress']);
    $info['NF'] = clears($r['NF']);
    $info['timezone'] = clears($r['timezone']);
    //?

    $info['pid'] = clears($r['pid']);


    $info['phone'] = clears($r['phone']);
    $info['how'] = clears($r['how']);
    $info['booking'] = clears($r['booking']);
    $info['max'] = clears($r['max']);
    $info['maxPrime'] = clears($r['maxPrime']);
    $info['deadline'] = clears($r['deadline']);

    $info['firstHour'] = clears($r['firstHour']);
    $info['lastHour'] = clears($r['lastHour']);

    $info['vk'] = clears($r['vk']);
    $info['vk'] = formatLinkForHumans($info['vk']);

    $info['website'] = clears($r['website']);
    $info['website'] = formatLinkForHumans($info['website']);
    
    $this->info = $info;
    return true;
  }

  /* getNotifications gets notifications and stores it into this->notifications

  */

  /* TO DO 
  1. Clarify if I gonna use getName($vkid) from utils OR rewrite it as function of the user

  */
  private function getNotifications() {
    $notifications = array();
    
    $query = "SELECT  * FROM ".$this->idb."_notification";
    $r = mysql_query($query);
    if ($r == false ){ 
      return false;
    }

    $i = 0;
    $arr = array();
    while ($row = mysql_fetch_array($r)){

      $notifications[$i]['type'] = clears($row ['type']);
      $notifications[$i]['vkid'] = clears($row['vkid']);
      $notifications[$i]['seen'] = clears($row['seen']);
      $notifications[$i]['room'] = clears($row['room']);
      $notifications[$i]['start'] = clears(formatTime($row['start']));
      $notifications[$i]['end'] = clears(formatTime($row['end']));
      $notifications[$i]['date'] = clears($row['date']);
      $notifications[$i]['odate'] = clears($row['odate']);
      $notifications[$i]['otime'] = clears(formatTime($row['otime']));
      
      $arr = getName($notifications[$i]['vkid']);
      $notifications[$i]['name'] = $arr[0];
      $notifications[$i]['lastname'] = $arr[1];
      $arr[0] = "";
      $arr[1] = "";
      $i++; 
    }

    $this->notifications = $notifications;
    return true;
  }

  private function outputNotificationCount() {
    if (is_null($this->notifications)) {
      $this->getNotifications();
    }

    return json_encode(count($this->notifications));
  }

  /* getBookings gets bookings and stores it into this->bookings

  */

  private function getBookings() {
    $bookings = array();
    
    $query = "SELECT  * FROM ".$this->idb."_booking";
    $r = mysql_query($query);
    if ($r == false ){ 
      return false;
    }

    $i = 0;

    //for timestamp;
    $hour = 0;
    $minutes = 0;
    $month = 0;
    $year = 0;
    $day = 0;
    $timestamp = 0;
    $dtemp = array();

    while ($row = mysql_fetch_array($r)) {  
      $dtemp = explode(".",$row['date']);
      
      $hour =  clears($row['start']) % 60;
      $minutes = clears($row['start']) - $hour * 60;
      $day = $dtemp[0];
      $month = $dtemp[1];
      $year = $dtemp[2];
      $timestamp = mktime($hour,$minutes,0,$month,$day,$year);

      $bookings[$i]['timestamp'] = $timestamp;
      $bookings[$i]['date'] = clears($row['date']);
      $bookings[$i]['start'] = clears($row['start']);
      $bookings[$i]['end'] = clears($row['end']);
      $bookings[$i]['room'] = clears($row['room']);
      $bookings[$i]['vkid'] = clears($row['vkid']);
      $bookings[$i]['name'] = clears($row['name']);
      $bookings[$i]['lastname'] = clears($row['lastname']);
      $bookings[$i]['phone'] = clears($row['phone']);
      $bookings[$i]['band'] = clears($row['band']);
      $bookings[$i]['done'] = clears($row['done']);
      $bookings[$i]['accept'] = clears($row['accept']);
      $bookings[$i]['admin'] = clears($row['admin']);
      $bookings[$i]['price'] = clears($row['price']);    
      $i++;
    }
    
    $this->bookings = $bookings;
    return true;
  }

  private function getSchedules() {
    $schedules = array();
    
    $query = "SELECT  * FROM ".$this->idb."_schedule";
    $r = mysql_query($query);
    if ($r == false ){ 
      return false;
    }

    $i = 0;
    while ($row = mysql_fetch_array($r,MYSQL_ASSOC)) {  
      $schedules[$i][0] = $row['d1'];
      $schedules[$i][1] = $row['d2'];
      $schedules[$i][2] = $row['d3'];
      $schedules[$i][3] = $row['d4'];
      $schedules[$i][4] = $row['d5'];
      $schedules[$i][5] = $row['d6'];
      $schedules[$i][6] = $row['d7'];
      $schedules[$i]['room'] = $row['rooms'];


      $i++;
    }

    $this->schedules = $schedules;
    return true;
  }

  private function getUsers() {
    $users = array();
    
    $query = "SELECT  * FROM ".$this->idb."_list";
    $r = mysql_query($query);
    if ($r == false ){ 
      return false;
    }

    $i = 0;
    while ($row = mysql_fetch_array($r,MYSQL_ASSOC)) {  
      $users[$i] = $row;
      $i++;
    }

    $this->users = $users;
    return true;
  }

  private function getPhotos() {
    $photos = array();
    
    $query = "SELECT  * FROM ".$this->idb."_photo";
    $r = mysql_query($query);
    if ($r == false ){ 
      return false;
    }

    $i = 0;
    while ($row = mysql_fetch_array($r)) {  
      $photos[$i] = $row['name'];
      $i++;
    }

    $this->photos = $photos;
    return true;
  }

  private function getEquipment() {
    $equipment = array();
    
    $query = "SELECT  * FROM ".$this->idb."_equip";
    $r = mysql_query($query);
    if ($r == false ){ 
      return false;
    }

    $i = 0;

    while ($row = mysql_fetch_array($r)) {  
      $equipment[$i]['id'] = clears($row['id']);
      $equipment[$i]['guitar'] = clears($row['guitar']);
      $equipment[$i]['bass'] = clears($row['bass']);
      $equipment[$i]['drum'] = clears($row['drum']);
      $equipment[$i]['line'] = clears($row['line']);
      $equipment[$i]['extra'] = clears($row['extra']);
      $equipment[$i]['name'] = clears($row['name']);
      $equipment[$i]['price'] = clears($row['price']);
      $i++;
    }

    $this->equipment = $equipment;
    return true;
  }

  public function outputInfo() {
    if (is_null($this->info)) {
      $this->getInfo();
    }
    $output = json_encode($this->info);
    return $output;
  }

  public function outputNotifications() {
    if (is_null($this->notifications)) {
      $this->getNotifications();
    }
    $output = json_encode($this->notifications);
    return $output;
  }
  
  //type == admin/user
  public function outputBookings() {
    if (is_null($this->bookings)) {
      $this->getBookings();
    }
    $outputBases = $this->bookings;

    if (!$this->isOwner()) {
      $limit = count($outputBases);
      
      for ($i = 0; $i < $limit; $i++) {
        $outputBases[$i] = array();
        $outputBases[$i]['timestamp'] = $this->bookings[$i]['timestamp'];
        $outputBases[$i]['date'] = $this->bookings[$i]['date'];
        $outputBases[$i]['start'] = $this->bookings[$i]['start'];
        $outputBases[$i]['end'] = $this->bookings[$i]['end'];
        $outputBases[$i]['room'] = $this->bookings[$i]['room'];
        $outputBases[$i]['done'] = $this->bookings[$i]['done'];
      }

    }


    $output = json_encode($outputBases);
    return $output;
  }

  public function outputSchedules() {
    if (is_null($this->schedules)) {
      $this->getSchedules();
    }
    $output = json_encode($this->schedules);
   return $output;
  }

  public function outputUsers() {
    if (is_null($this->users)) {
      $this->getUsers();
    }
    $output = json_encode($this->users);
    return $output;
  }

  public function outputPhotos() {
    if (is_null($this->photos)) {
      $this->getPhotos(); 
    }
    $output = json_encode($this->photos);
    return $output;
  }

  public function outputEquipment() {
    if (is_null($this->equipment)) {
      $this->getEquipment();
    }
    $output = json_encode($this->equipment);
    return $output;
  }

  public function outputRoomNames () {
    if (is_null($this->equipment)) {
      $this->getEquipment();
    }

    $limit = count($this->equipment);
    $roomNames = array();


    for ($i = 0; $i < $limit; $i++) {
      
      $roomNames[$this->equipment[$i]['id']] = $this->equipment[$i]['name'];
     
      if ($this->equipment[$i]['name'] == "") {
        $roomNames[$this->equipment[$i]['id']] = $this->equipment[$i]['id'];
      }
    }
    $output = json_encode($roomNames);
    return $output;
  }

  public function outputTime() {
    if (is_null($this->info)) {
      $this->getInfo();
    }
    $output = array();

    $timezone = intVal($this->info['timezone']);

    date_default_timezone_set("Europe/London");
    $time = time() + $timezone * 3600;
    
    $output[0] = $timezone;
    $output[1] = time();
    $output[2] = $time;

    $output = json_encode($output);
    return $output;
  }

  public function updateInfo($info) {
    if (!$this->isOwner()) {
      return false;
    }
    $this->getInfo();

    $descript = check($info['descript']);
    $adress   = check($info['address']);
    $town     = check($info['town']);
    $website  = check($info['website']);
    $phone    = check($info['phone']);
    $vk       = check($info['vk']);
    $station  = check($info['station']);
    $komn     = check($info['komn']);
    $how      = check($info['how']);

    /*  FORMAT WEBSITE FOR FUTURE PROPER USE IN HREF  */
    $website = formatLink($website);
    $vk = formatLink($vk);
    /*  FORMAT WEBSITE FOR FUTURE PROPER USE IN HREF  */



    if ($komn > $this->info['komn']){
      for ($i = $this->info['komn'] + 1; $i <= $komn; $i++){
        $sc = mysql_query("INSERT INTO `b108859_wordpress`.`{$this->idb}_schedule` (`rooms`) VALUES ('$i')");
        $sc = mysql_query("INSERT INTO `b108859_wordpress`.`{$this->idb}_equip` (`id`) VALUES ('$i')");
      }
    } else if ($komn < $this->info['komn']) {
      for ($i = $this->info['komn']; $i > $komn; $i--) {
        $sc = mysql_query("DELETE FROM {$this->idb}_schedule WHERE rooms='$i'");
        $sc = mysql_query("DELETE FROM {$this->idb}_equip WHERE id='$i'");
      }
    }

    $query = "UPDATE bases SET `descript`='".$descript."', `adress`='".$adress."', `town`='".$town."', `station`='".$station."', `phone`='".$phone."', `type`='".$type."',`how`='".$how."', `website`='".$website."', `vk`='".$vk."', `komn`='".$komn."' WHERE id='".$this->idb."'";
    $r = mysql_query($query);

    if ($r) {
      return true;
    } else {
      return false;
    }
  }

  public function updateConditions($info) {
    if (!$this->isOwner()) {
      return false;
    }

    $maxPrime = intval(check($info['maxPrime']));
    $max = intval(check($info['max']));
    $deadline = intval(check($info['deadline']));

    if ($maxPrime < 0) {
      $maxPrime = 0;
    }
    if ($max < 0) {
      $max = 0;
    }
    if ($deadline < 0) {
      $deadline = 0;
    }

    $query = "UPDATE bases SET `maxPrime`='".$maxPrime."',`max`='".$max."',`deadline`='".$deadline."' WHERE id='".$this->idb."'";
    $r = mysql_query($query);

    if ($r) {
      return true;
    } else {
      return false;
    }
  }

  public function updateNotifications() {
    $r = mysql_query("UPDATE {$this->idb}_notification SET `seen`='1' WHERE seen='0'");
    $count = count($this->notifications);

    /* TODO ?????*/
    /*for ($i = 0; $i < $count; $i++) {
      
    }*/
  }
  
  public function deleteBooking($data) {
    $start = $data['start'];
    $end = $data['end'];
    $room = $data['room'];
    $date = $data['date'];
    $vkid = $data['vkid'];
    $idb = $this->idb;
    $output = array();

    $notexist = bookingNotExist($idb,$start,$end,$date,$room);
 
  
    if ($notexist) {
      $output[0] = false;
      $output[1] = "Бронирование не существует";
      return $output;
    }
    
    $deadlineViolation = pastDeadline($date,$start,$idb);

    $del = mysql_query("DELETE FROM {$idb}_booking   WHERE room='$room' and end='$end' and start='$start' and date='$date' ");
    
    if ($data['vkid'] != 0 ) {
      $del2 = mysql_query("DELETE FROM {$vkid}_history  WHERE room='$room' and end='$end' and start='$start' and date='$date' and idb='$idb'");
      userNotify(2,$idb,$room,$start,$end,$date,$vkid);
      changeCurrent($idb,$vkid,-1);
    } 
    
    if (!$del) {
      $output[0] = false;
      $output[1] = "Отмена бронирования не удалась. Попробуйте снова.";
      return $output;
    } 

    if ($deadlineViolation) {
      $output[0] = true;
      $output[1] = "debt";
    } else {
      $output[0] = true;
      $output[1] = "Бронирование было успешно отменено";
    }
    return $output;
  }

  public function updateEquipment($info) {
    if (!$this->isOwner()) {
      return false;
    }

    $id = intval(check($info['id']));
    $name = check($info['name']);
    $price = check($info['price']);
    $bass = check($info['bass']);
    $drum = check($info['drum']);
    $guitar = check($info['guitar']);
    $line = check($info['line']);
    $extra = check($info['extra']);

    $query = "UPDATE ".$this->idb."_equip SET `name`='".$name."', `price`='".$price."', `bass`='".$bass."', `drum`='".$drum."', `guitar`='".$guitar."', `line`='".$line."', `extra`='".$extra."' WHERE id='".$id."'";
    $r = mysql_query($query);
    
    if ($r) {
      return true;
    } else {
      return false;
    } 
  }

  //type 1 = booking, 2 = cancellation
  public function notify($type,$room,$start,$end,$date,$vkid) {
    $today = 10000*date("Y")+100*date("n")+date("j");
    $otime = currentTime($this->idb, $today);
    
    $dateY = ($today-$today%10000)/10000;
    $dateM = ($today%10000 - $today%100)/100;
    $dateD = $today%100;
      
    $dateH  = floor($otime/60);
    $dateMin = $otime - $dateH*60;
    $format = $dateD.".".$dateM.".".$dateY;
    $formatTime = formatTime($dateH).":".$dateMin;
    
    $r = mysql_query("INSERT INTO `b108859_wordpress`.`{$this->idb}_notification` (`seen`,`type`,`vkid`,`room`,`start`,`end`,`date`,`odate`,`otime`) VALUES ('0','1','$vkid','$room','$start','$end','$date','$format','$otime')");

    if ($r) {
      return true;
    } else {
      return false;
    } 
  }

}




