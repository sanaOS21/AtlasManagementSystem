<?php

namespace App\Calendars\Admin;

use Carbon\Carbon;
use App\Models\Users\User;

class CalendarView
{
  private $carbon;

  function __construct($date)
  {
    $this->carbon = new Carbon($date);
  }

  public function getTitle()
  {
    return $this->carbon->format('Y年n月');
  }

  public function render()
  {
    $html = [];
    $html[] = '<div class="calendar text-center">';
    $html[] = '<table class="table m-auto border">';
    $html[] = '<thead>';
    $html[] = '<tr>';
    $html[] = '<th class="border">月</th>';
    $html[] = '<th class="border">火</th>';
    $html[] = '<th class="border">水</th>';
    $html[] = '<th class="border">木</th>';
    $html[] = '<th class="border">金</th>';
    $html[] = '<th class="border">土</th>';
    $html[] = '<th class="border">日</th>';
    $html[] = '</tr>';
    $html[] = '</thead>';
    $html[] = '<tbody>';

    // 週カレンダーオブジェクトの配列を取得
    $weeks = $this->getWeeks();
    // 週カレンダーを一周ずつ処理
    foreach ($weeks as $week) {
      $html[] = '<tr class="' . $week->getClassName() . '">';
      // 週カレンダーオブジェクトから、日カレンダーオブジェクトの配列を取得します。
      $days = $week->getDays();
      foreach ($days as $day) {
        $startDay = $this->carbon->format("Y-m-01");
        $toDay = $this->carbon->format("Y-m-d");
        if ($startDay <= $day->everyDay() && $toDay >= $day->everyDay()) {
          $html[] = '<td class="past-day border">';
        } else {
          $html[] = '<td class="border ' . $day->getClassName() . '">';
        }
        $html[] = $day->render();
        $html[] = $day->dayPartCounts($day->everyDay());
        // <td>の中に日カレンダーを出力していきます。
        $html[] = '</td>';
      }
      $html[] = '</tr>';
    }
    $html[] = '</tbody>';
    $html[] = '</table>';
    $html[] = '</div>';

    return implode("", $html);
  }

  protected function getWeeks()
  {
    $weeks = [];
    // 初日
    $firstDay = $this->carbon->copy()->firstOfMonth();
    // 月末まで
    $lastDay = $this->carbon->copy()->lastOfMonth();
    // 1週間
    $week = new CalendarWeek($firstDay->copy());
    $weeks[] = $week;
    // 作業用の日
    $tmpDay = $firstDay->copy()->addDay(7)->startOfWeek();
    // 月末までループさせる
    while ($tmpDay->lte($lastDay)) {
      // 週カレンダーviewを作成
      $week = new CalendarWeek($tmpDay, count($weeks));
      $weeks[] = $week;

      // 次の週の（+7日）計算する
      $tmpDay->addDay(7);
    }
    return $weeks;
  }
}
