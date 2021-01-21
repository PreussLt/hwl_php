<?php
include("sql.php");
include("../Template.class.php");

$tpl = new Template("../includes/");
$tpl->load("reservedCalendar.html");

$m_str = "";
$year = 0000;
$month = "";
$month_num = 00;
$num_days = 0;
$fid = -1;

if (isset($_GET['fid']) && !empty($_GET['fid'])) {
    $fid = $_GET['fid'];
} else {
    header("Location: ./");
}

if (isset($_GET["y"]) && !empty($_GET["y"])) {
    $year = $_GET["y"];
} else {
    $year = date("Y");
}

if (isset($_GET["m"]) && !empty($_GET['m'])) {
    $month_num = $_GET["m"];
    $m_str = ($_GET["m"] < 10) ? "0" . $_GET["m"] : $_GET["m"];
    $timestamp = $year . "-" . $m_str . "-01";
    $dateTime = new DateTime($timestamp);
    $month = $dateTime->format("F");
    $num_days = $dateTime->format("t");
} else {
    $month = date("F");
    $month_num = date("n");
    $m_str = date("m");
    $num_days = date("t");
}

$timestamp = $year . "-" . $m_str . "-01"; //1er Tag des aktuellen monats
$dateTime = new DateTime($timestamp);
$firstDayOfMonth = $dateTime->format("N"); //1er Tag als zahl

$prevDate = getPreviousDate($year, $month_num);
$prevYear = $prevDate["year"];
$prevMonth = $prevDate["month"];

$prevMonthStr = ($prevMonth < 10) ? "0" . $prevMonth : $prevMonth;

$timestamp = $prevYear . "-" . $prevMonth . "-01";
$dateTime = new DateTime($timestamp);
$num_day_prev_month = $dateTime->format("t"); //anzahl der tage des voherigen monats

$days_html = "";

$sql = "SELECT res_datum_start,res_datum_end FROM reservierung WHERE f_ID=$fid";
$conn = mysql();
$res = $conn->query($sql) or die($conn->error); // anfangs und end datum aller reservierungen holen
$reservations = array();
while ($row = $res->fetch_assoc()) {
    $reservations[] = $row;
}

for ($i = 1; $i < $firstDayOfMonth; $i++) { //Tage des Vorherigen Monats anzeigen Beispiel !{Mo: 30 Di: 31}! Mi: 1
    $num = $num_day_prev_month - $firstDayOfMonth + $i + 1;

    $tmp = ($num < 10) ? "0" . $num : $num;

    $ts = $prevYear . "-" . $prevMonthStr . "-" . $tmp;
    if (isReserved($reservations, $ts)) {
        $str = "<li><span class='active'>$num</span></li>\n"; // Reservierte tage markieren
    } else {
        $str = "<li>$num</li>\n";
    }
    $days_html .= $str;
}


for ($i = 1; $i <= $num_days; $i++) {

    $tmp = ($i < 10) ? "0" . $i : $i;
    $ts = $year . "-" . $m_str ."-". $tmp;
    if (isReserved($reservations, $ts)) {
        $str = "<li><span class='active'>$i</span></li>\n"; // Reservierte tage markieren
    } else {
        $str = "<li>$i</li>\n";
    }


    $days_html .= $str;
}

$tpl->assign("fid", $fid);
$tpl->assign("month_num", $month_num);
$tpl->assign("month_name", $month);
$tpl->assign("year", $year);
$tpl->assign("days", $days_html);
$tpl->display();

function getPreviousDate($year, $month) //vorheriges datum
{
    $return = array(
        "year" => $year,
        "month" => $month
    );

    if ($month == 1) {
        $return["year"] = $year - 1;
        $return["month"] = 12;
    } else {
        $return["month"] = $month - 1;
    }

    return $return;
}

function isReserved($reservations, $timestamp) // ob tag reserviert ist
{


    $reserved = false;
    if (!empty($reservations)) {
        $date = new DateTime($timestamp);
        foreach ($reservations as $res) {
            $resStart = new DateTime($res['res_datum_start']);
            $resEnd = new DateTime($res['res_datum_end']);
            if ($date > $resStart && $date < $resEnd) {
                $reserved = true;
            } else if ($date == $resStart || $date == $resEnd) {
                $reserved = true;
            }
        }
    }

    return $reserved;
}
