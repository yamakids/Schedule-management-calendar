<?php

namespace MyApp;

require_once('config.php');
require_once('functions.php');

class Calendar {
  public $prev;
  public $next;
  public $yearMonth;
  public $month;
  private $_thisMonth;
  private $_dbh;
  private $_myname;

  public function __construct() {
    try {
      if (!isset($_GET['t']) || !preg_match('/\A\d{4}-\d{2}\z/', $_GET['t'])) {
        throw new \Exception();
      }
      $this->_thisMonth = new \DateTime($_GET['t']);
    } catch (\Exception $e) {
      $this->_thisMonth = new \DateTime('first day of this month');
    }
    $this->prev = $this->_prevLink();
    $this->next = $this->_nextLink();
    $this->month =  $this->_thisMonth->format('m');
    $this->yearMonth = $this->_thisMonth->format('F Y');
    $this->_dbh = connectDb();
    $this->_myname = myname();
  }

  // 前月へのリンク
  private function _prevLink() {
    $dt = clone $this->_thisMonth;
    return $dt->modify('-1 month')->format('Y-m');
  }


  // 翌月へのリンク
  private function _nextLink() {
    $dt = clone $this->_thisMonth;
    return $dt->modify('+1 month')->format('Y-m');
  }

  // ログイン中のユーザーに関するテーブル作成
  public function user($myname){
    $sql = "create table if not exists {$myname}(id int not null auto_increment,
            time text,
            seq int not null,
            type enum('notyet', 'done', 'deleted') default 'notyet',
            title text,
            created datetime,
            modified datetime,
            KEY type(type),
            KEY seq(seq),
            primary key(id))";
    $stmt = $this->_dbh->prepare($sql);
    $stmt->execute();
  }

  // todoリスト
  private function _todoList($time){
    $sql = "select * from {$this->_myname} where time = '{$time}' and type != 'deleted' order by seq";
    $stmt=$this->_dbh->prepare($sql);
    $stmt->execute();
    $tasks = $stmt->fetchAll();
    $time = str_replace('-', '_', $time);
    if(count($tasks)==0){
      $content = "<ul id='{$time}'></ul>";
     return $content;
    }

    $i = 0;
  foreach ($tasks as $task){
     $x = h($task['id']);
     $y = h($task['type']);
     $z = $y=='done' ? 'checked':'';
     $q = $y=='notyet' ?'editTask':'';
     $r = h($task['title']);
     $i++;
     $content .= "<li id='task_{$x}' data-id='{$x}'>
        <input type='checkbox' class='checkTask' {$z}>
        <span class='{$y}'>$r</span>
        <span class='{$q}'>[編集]</span>
        <span class='deleteTask'>[削除]</span>
        <span class='drag'>[drag]</span>
      </li>";
      if($i==count($tasks)){
        $content = "<ul id='{$time}'>{$content}</ul>";
       return $content;
     }
    }
  }

  // カレンダー表示
  public function show() {
    $tail = $this->_tail();
    $body = $this->_body();
    $head = $this->_head();
    $html = '<tr>' . $tail . $body . $head . '</tr>';
    echo $html;
  }

  // 前月の末日以前数日
  private function _tail() {
    $tail = '';
    $lastDayOfPrevMonth = new \DateTime('last day of ' . $this->yearMonth . ' -1 month');
    while ($lastDayOfPrevMonth->format('w') < 6) {
      $x=$this->_todoList($lastDayOfPrevMonth->format( "Y-m-d"));
      $y=$lastDayOfPrevMonth->format( "Y-m-d");
        $tail = sprintf('<td id="'.$y.'" class="gray" valign="top"><span>%d</span><br>'.$x.'
        </td>', $lastDayOfPrevMonth->format('d')) . $tail;
      $lastDayOfPrevMonth->sub(new \DateInterval('P1D'));
    }
    return $tail;
  }

  // 当月の日にち
  private function _body() {
    $body = '';
    $period = new \DatePeriod(
      new \DateTime('first day of ' . $this->yearMonth),
      new \DateInterval('P1D'),
      new \DateTime('first day of ' . $this->yearMonth . ' +1 month')
    );
    $today = new \DateTime('today');
    foreach ($period as $day) {
      if ($day->format('w') === '0') { $body .= '</tr><tr>'; }
      $todayClass = ($day->format('Y_m_d') === $today->format('Y_m_d')) ? 'today' : '';
      $x=$this->_todoList($day->format('Y-m-d'));
      $y=$day->format('Y-m-d');
      $body .= sprintf('<td id="'.$y.'" valign="top"><span class="youbi_%d  %s"><span>%d</span></span><br>'
          .$x.'</td>', $day->format('w'), $todayClass, $day->format('d'));
    }
    return $body;
  }

  // 翌月の初日以降数日
  private function _head() {
    $head = '';
    $firstDayOfNextMonth = new \DateTime('first day of ' . $this->yearMonth . ' +1 month');
    while ($firstDayOfNextMonth->format('w') > 0) {
      $x=$this->_todoList($firstDayOfNextMonth->format('Y-m-d'));
      $y=$firstDayOfNextMonth->format('Y-m-d');
      $head .= sprintf('<td id="'.$y.'" class="gray" valign="top"><span>%d</span><br>'.$x.'</td>', $firstDayOfNextMonth->format('d'));
      $firstDayOfNextMonth->add(new \DateInterval('P1D'));
    }
    return $head;
  }

}
