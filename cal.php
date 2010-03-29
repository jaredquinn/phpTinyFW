<html>
<!-- 
  Calendar Example for HTSG PHP Test
  Jared Quinn
  Mon Mar 22 16:22

  No external files used.
  All code here is original.

  For convenience this is in a single file, in the real world it wouldn't be.

-->
<head>
  <title>Calendar Example</title>
  <!-- eek internal css.. but it's okay for this simple example -->
  <style type="text/css">
   table.calendar   { border: 1px solid #333;  border-collapse: collapse; }
   table.calendar thead tr th { border: 1px solid #333; }
   table.calendar td,
   table.calendar th { padding: 3px 10px;  text-align: center; border: 1px solid #333; }
   table.calendar td.anotherMonth { color: #ccc; }
   table.calendar td.hilight { background: yellow; }
   table.calendar td.today { font-weight: bold; }
  </style>
</head>
<body>
<?

	// create the calendar and set some options
	$cal = new Calendar();

	$today = $cal->setToday();
	//$today = $cal->setToday('28 Feb 2010') // testing;

	// work out what we want to hilight. 
	$hilightFrom = mktime(1,0,0,$today['mon'], $today['mday']-$today['wday']-7,$today['year']);
	$hilightTo   = mktime(1,0,0,$today['mon'], $today['mday']-$today['wday'],$today['year']);

	$cal->setHilightRange( $hilightFrom, $hilightTo );
	$cal->setDisplayOffsetWeeks(-1); // always make our hilighted week appear vertically centered

	// if older php this may need to be changed to $cal->renderCalendar()
	print $cal;
	
?>
<br/><br/>
<small>Current time on this server is <?= date('r', $today[0]) ?></small>
</body>

<? 
// PHP Magic Happens Here
//
// Calendar Class handles the calendar logic

class Calendar {

	protected $today;

	protected $displayOffset;

 	protected $hilightDays = array();

	public function __construct() {
	  $this->today = getdate();
	}

	// calendar defaults to today, however this is useful for testing
	// handles common php time formats (timestamps, getdate arrays and strings
	public function setToday($timeStuff = null) {
	  if($timeStuff == null) { $timeStuff = getdate(); } 
	    elseif(is_string($timeStuff)) { $timeStuff = getdate(strtotime($timeStuff)); }

	  $this->today = $timeStuff;
          return $this->today;
	}	

	// week offset used for determining which week gets outputted in the middle
	public function setDisplayOffsetWeeks($weeks) {
	  $this->displayOffset = $weeks;
	}

	public function setHilight($date) {
	    if(is_array($date)) { $date = $date[0]; }
	    $this->hilightDays[] = date('d-M-y', $date);
	
	}

	public function setHilightRange($beginStamp, $endStamp) {
	  for($i = $beginStamp; $i < $endStamp; $i = $i + 86400) {
	    $this->setHilight($i);
	  }
	}

	// this is the guts of the whole operation
	public function renderCalendar() {

	  $weekStart = $this->today['mday'] - $this->today['wday'] + ($this->displayOffset*7);

	  $calS = mktime(1,0,0,$this->today['mon'], $weekStart-14,$this->today['year']);
	  $calE = mktime(1,0,0,$this->today['mon'], $weekStart+21,$this->today['year']);

	  $render = new Calendar_Helper();
	  $r = $render->beginCalendar($this->today);

	  for($i = $calS; $i < $calE; $i = $i + 86400) {

	    $thisDay = getdate($i);
	    if($thisDay['wday'] == 0) {
	      $r.= $render->endRow() . $render->beginRow();
	    }

	    // some logic to work out the display attributes to apply (html classes)
	    $class = array();
	    if(date('n', $i) != $this->today['mon']) { $class[] = 'anotherMonth'; }
	    if(in_array(date('d-M-y', $i), $this->hilightDays, true)) { $class[] = 'hilight'; }
	    if(date('d-M-y', $i) == date('d-M-y', $this->today[0])) { $class[] = 'today'; }

	    // render the actual day element
      	    $r .= $render->renderDay($i, $class);

	  }

	  $r .= $render->endRow();
	  $r .= $render->endCalendar();
	  return $r;
	}

	// cheat function allowing you to "print $calendarObject" to generate output
	public function __toString() {
	  return $this->renderCalendar();
	}

}

// Calendar_Helper class handles the rendering of the calendar

class Calendar_Helper {

	public static function getDayOfWeek($i) {
	  // ideally we'd make a call to an i18n library here and get the correct
	  // short name for the current locale
	  $dows = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
	  return $dows[$i];
	}
	
	public static function beginCalendar($date) {
	  if(is_array($date)) { $date = $date[0]; } // support input in php date array format
	  $r  = '<table class="calendar">';
	  $r .= '<thead>';
	  $r .= '<tr><th colspan="7" class="calendarTitle">' . date('F Y', $date) . '</th></tr>';
	  $r .= '<tr>';
	  for($i = 0; $i < 7; $i++) {
	    $r .= '<th class="calendarDayTitle">' . self::getDayOfWeek($i) . '</th>';
	  }
	  $r .= '</tr>';
	  $r .= '</thead>';
	  $r .= '<tbody>';
	  return $r;
	}

	public static function endCalendar() {
	  return '</tbody></table>';
	}

	public static function beginRow() {
	  return '<tr>';
	}
	
	public static function endRow() {
	  return '</tr>';
	}

	public static function renderDay($timeStamp, array $class) {
	  return '<td class="' . join(' ', $class) . '">' . date('j', $timeStamp) . '</td>';
	}
}

?>
