<?php 
/*  CLASS User

PPOPERTIES:

GENERAL METHODS:

GET METHODS: all get methods are private, use output methods instead
  getInfo()
  getNotifications()
  getBookings()
OUTPUT METHODS: all methods are public
  outputInfo()
  outputNotifications()
  outputBookings()

UPDATE METHODS:
  updateInfo()
  updateNotifications()

  cancelBooking()


*/

Class User {

  /*  PROPERTIES  */
  public $vkid = 0;
  private $info = null;
  private $notifications = null;
  private $bookings = null;
  /*  PROPERTIES  */


   /*  CONSTRUCTOR  

    it gets business vkid, 
    sets it to this->vkid 

    returns false if there was mysql query error or business id DNE
  */

  public function __construct($vkid) {
    $vkid = intval($vkid);
    $r = mysql_query("SELECT vkid FROM mus_users WHERE vkid='$vkid'");
    
    if (!$r) {
      return false;
    }

    $r = mysql_fetch_array($r);

    if (isset($r['vkid'])) {
      $this->vkid = $r['vkid'];
    } else {
      return false;
    }

    return true;
  }
  /*  CONSTRUCTOR  */

  public function getInfo() {
    if (is_null($this->info)) {
      $info = array();
      $query = "SELECT * FROM mus_users WHERE vkid=".$this->vkid;
      $r = mysql_query($query);
      if ($r == false ){ 
        return false;
      }  

      $r = mysql_fetch_array($r);
      $info['name'] = clears($r['name']);
      $info['lastname'] = clears($r['lastname']);
      $info['vkid'] = clears($r['vkid']);
      $info['curr_book_num'] = clears($r['curr_book_num']);
      $info['past_book_num'] = clears($r['past_book_num']);
      $info['phone'] = clears($r['phone']);
      
      $this->info = $info;
    }

    
    return $this->info;
  }

  private function getNotifications() {
    $notifications = array();
    $query = "SELECT * FROM  WHERE id=".$this->vkid;
    $r = mysql_query($query);
    if ($r == false ){ 
      return false;
    }  

    while ($row = mysql_fetch_array($r)){
      $notifications[$i]['type'] = clears($row ['type']);
      $notifications[$i]['idb'] = clears($row['idb']);
      $notifications[$i]['seen'] = clears($row['seen']);
      $notifications[$i]['room'] = clears($row['room']);
      $notifications[$i]['start'] = clears(formatTime($row['start']));
      $notifications[$i]['end'] = clears(formatTime($row['end']));
      $notifications[$i]['date'] = clears($row['date']);
      $notifications[$i]['odate'] = clears($row['odate']);
      $notifications[$i]['otime'] = clears(formatTime($row['otime']));
      
      $name = getBaseName($notifications[$i]['idb']);
      $notifications[$i]['basename'] = $name;
      $name = "";
      
      $i++; 
    }

    
    $this->notifications = $notifications;
    return true;
  }

  private function getBookings() {
    $bookings = array();
    
    $query = "SELECT  * FROM ".$this->vkid."_history";
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
      $bookings[$i]['idb'] = intval($row['idb']);
      
      $bookings[$i]['name'] = getBaseName($bookings[$i]['idb']);
      
      $bookings[$i]['done'] = clears($row['done']);
      $bookings[$i]['accept'] = clears($row['accept']);
      $bookings[$i]['price'] = clears($row['price']);    
      $i++;
    }
    
    $this->bookings = $bookings;
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

  public function outputBookings() {
    if (is_null($this->bookings)) {
      $this->getBookings();
    }

    $output = json_encode($this->bookings);
    return $output;
  }



}

?>